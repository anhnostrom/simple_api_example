<?php

namespace App;

use Entity\User;
use Memcached;
use PDO;
use Redis;

class Context
{
    private static $instance;
    private $pdo;
    private $redis;
    private $memcached;
    private $user;

    private function __construct()
    {
    }

    public static function i(): Context
    {
        if (null === self::$instance) {
            self::$instance = new Context();
        }
        return self::$instance;
    }

    public function getPDO(): PDO
    {
        if (null === $this->pdo) {
            $this->pdo = new PDO(
                'mysql:host=mysql;dbname=' . getenv('MYSQL_DATABASE'),
                getenv('MYSQL_USER'),
                getenv('MYSQL_PASSWORD'),
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }
        return $this->pdo;
    }

    public function getRedis(): Redis
    {
        if (null === $this->redis) {
            $redis = new Redis();
            $redis->connect('redis');
            $this->redis = $redis;
        }
        return $this->redis;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getMemcached()
    {
        if (null === $this->memcached) {
            $memcached = new Memcached();
            $memcached->addServer('memcached', 11211);
            $this->memcached = $memcached;
        }

        return $this->memcached;
    }
}