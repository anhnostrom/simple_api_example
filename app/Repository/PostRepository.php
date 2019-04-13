<?php

namespace Repository;

use App\AbstractRepository;
use Entity\Post;
use Entity\User;
use Memcached;
use PDO;

class PostRepository extends AbstractRepository
{
    const MEM_KEY = 'posts:';
    const MEM_TTL = 60 * 60 * 24;

    /**
     * CREATE TABLE `posts` (
     * `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
     * `user_id` int(11) NOT NULL,
     * `user_name` varchar(100) DEFAULT NULL,
     * `text` varchar(280) NOT NULL DEFAULT '',
     * `created_at` datetime NOT NULL,
     * `updated_at` datetime NOT NULL,
     * `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
     * PRIMARY KEY (`id`),
     * KEY `id_deleted` (`is_deleted`),
     * KEY `user_id` (`user_id`,`is_deleted`)
     * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
     */
    private $table = 'posts';

    public function find(int $postId): ?Post
    {
        $memcached = $this->getContext()->getMemcached();
        $memKey = $this->getMemcachedKey($postId);
        $cached = $memcached->get($memKey);
        if (Memcached::RES_NOTFOUND !== $memcached->getResultCode()) {
            return $cached ? (new Post())->fillData($cached) : null;
        }

        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare("SELECT * FROM `$this->table` WHERE id = ? AND is_deleted = ?");
        $query->execute([$postId, 0]);

        /** @var Post $post */
        $post = $query->fetchObject(Post::class);
        $memcached->set($memKey, $post ? $post->serializeToArray() : null, self::MEM_TTL);
        return $post ?: null;
    }

    private function getMemcachedKey(int $postId): string
    {
        return self::MEM_KEY . $postId;
    }

    public function findByIds(array $postIds): array
    {
        $result = [];
        $memcached = $this->getContext()->getMemcached();
        $memKeys = [];
        foreach ($postIds as $postId) {
            $memKeys[$this->getMemcachedKey($postId)] = $postId;
        }
        $cached = $memcached->getMulti(array_keys($memKeys), Memcached::GET_PRESERVE_ORDER);
        $notCachedIds = [];
        foreach ($cached as $key => $value) {
            if (!$value) {
                $notCachedIds[] = $memKeys[$key];
                continue;
            }

            $result[] = (new Post())->fillData($value);
        }

        if (!$notCachedIds) {
            return $result;
        }

        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare(
            "SELECT * FROM `$this->table` WHERE id IN (" . join(', ', array_fill(0, count($notCachedIds), '?')) . ")"
        );
        $query->execute($notCachedIds);
        /** @var Post[] $posts */
        $posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
        if (!$posts) {
            return $result;
        }

        $dataToCache = [];
        foreach ($posts as $post) {
            $dataToCache[$this->getMemcachedKey($post->getId())] = $post->serializeToArray();
            $result[] = $post;
        }

        $memcached->setMulti($dataToCache, self::MEM_TTL);

        usort($result, [$this, 'sortByCreatedAt']);

        return $result;
    }

    public function findByUserId(int $userId, int $limit = 25, int $lastId = 0): array
    {
        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare(
            "SELECT * FROM `$this->table` WHERE user_id = ? AND is_deleted = 0 AND id > ? LIMIT ?"
        );
        $query->bindParam(1, $userId, PDO::PARAM_INT);
        $query->bindParam(2, $lastId, PDO::PARAM_INT);
        $query->bindParam(3, $limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, Post::class);
    }

    public function create(User $user, string $text): int
    {
        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare(
            "INSERT INTO `$this->table` (user_id, user_name, text, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())"
        );
        $query->execute([$user->getId(), $user->getName(), $text]);
        $id = $pdo->lastInsertId();

        if ($id) {
            $this->getContext()->getMemcached()->delete($this->getMemcachedKey($id));
        }

        return $id;
    }

    public function delete(int $id): bool
    {
        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare(
            "UPDATE `$this->table` SET is_deleted = 1, updated_at = NOW() WHERE id = ? AND is_deleted = 0"
        );
        $query->execute([$id]);
        $result = $query->rowCount() > 0;

        if ($result) {
            $this->getContext()->getMemcached()->delete($this->getMemcachedKey($id));
        }

        return $result;
    }

    public function edit(int $id, string $text): bool
    {
        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare(
            "UPDATE `$this->table` SET text = ?, updated_at = NOW() WHERE id = ? AND is_deleted = 0"
        );
        $query->execute([$text, $id]);
        $result = $query->rowCount() > 0;

        if ($result) {
            $this->getContext()->getMemcached()->delete($this->getMemcachedKey($id));
        }

        return $result;
    }

    private function sortByCreatedAt(Post $a, Post $b)
    {
        return $a->getCreatedAt() < $b->getCreatedAt();
    }
}