<?php

namespace Repository;

use App\AbstractRepository;
use Entity\AccessToken;

class AccessTokenRepository extends AbstractRepository
{
    /**
     * CREATE TABLE `access_tokens` (
     * `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
     * `token_hash` int(11) unsigned NOT NULL,
     * `token` char(60) NOT NULL DEFAULT '',
     * `user_id` int(11) unsigned NOT NULL,
     * `created_at` datetime NOT NULL,
     * `expire_at` datetime NOT NULL,
     * PRIMARY KEY (`id`),
     * KEY `token_hash` (`token_hash`)
     * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
     */
    private $table = 'access_tokens';

    public function find(string $accessToken): ?AccessToken
    {
        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare("SELECT * FROM `$this->table` WHERE token_hash = ? AND token = ? AND expire_at > NOW()");
        $query->execute([crc32($accessToken), $accessToken]);
        return $query->fetchObject(AccessToken::class) ?: null;
    }

    public function create(int $userId): int
    {
        $accessToken = password_hash(random_bytes(10), PASSWORD_DEFAULT);
        $pdo = $this->getContext()->getPDO();
        $query = $pdo->prepare(
            "INSERT INTO `$this->table` (token_hash, token, user_id, created_at, expire_at) VALUES (?, ?, ?, NOW(), NOW() + INTERVAL 2 WEEK)"
        );
        $query->execute([crc32($accessToken), $accessToken, $userId]);
        return $pdo->lastInsertId();
    }

}