<?php

namespace Repository;

use App\AbstractRepository;
use Entity\User;
use Memcached;

class UserRepository extends AbstractRepository
{
    const MEM_KEY = 'users:';
    const MEM_TTL = 60 * 60 * 24;

    /**
     * CREATE TABLE `users` (
     * `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
     * `name` varchar(100) NOT NULL DEFAULT '',
     * `created_at` datetime NOT NULL,
     * `updated_at` datetime NOT NULL,
     * PRIMARY KEY (`id`)
     * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
     */
    private $table = 'users';

    public function find(int $id): ?User
    {
        $memcached = $this->getContext()->getMemcached();
        $memKey = $this->getMemcachedKey($id);
        $cached = $memcached->get($memKey);
        if (Memcached::RES_NOTFOUND !== $memcached->getResultCode()) {
            return $cached ? (new User())->fillData($cached) : null;
        }

        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare("SELECT * FROM `$this->table` WHERE id = ?");
        $query->execute([$id]);

        /** @var User $user */
        $user = $query->fetchObject(User::class);
        $memcached->set($memKey, $user ? $user->serializeToArray() : null, self::MEM_TTL);
        return $user ?: null;
    }

    public function create(string $name): int
    {
        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare(
            "INSERT INTO `$this->table` (name, created_at, updated_at) VALUES (?, NOW(), NOW())"
        );
        $query->execute([$name]);
        return $pdo->lastInsertId();
    }

    private function getMemcachedKey(int $postId): string
    {
        return self::MEM_KEY . $postId;
    }
}