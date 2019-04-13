<?php

namespace Repository;

use App\AbstractRepository;
use Entity\Post;

class TimelineRepository extends AbstractRepository
{
    const REDIS_HOME_PREFIX = 'home_timeline#';
    const REDIS_USER_PREFIX = 'user_timeline#';
    const BUNCH_SIZE = 500;

    public function addPost(array $userIds, Post $post)
    {
        $redis = $this->getContext()->getRedis();
        $timestamp = $post->getCreatedAt()->getTimestamp();
        $postId = $post->getId();

        $redis->zAdd(self::REDIS_USER_PREFIX . $post->getUserId(), $timestamp, $postId);

        foreach (array_chunk($userIds, self::BUNCH_SIZE) as $chunk) {
            $redis->multi();
            foreach ($chunk as $userId) {
                $redis->zAdd(self::REDIS_HOME_PREFIX . $userId, $timestamp, $postId);
            }
            $redis->exec();
        }
    }

    public function removePost(array $userIds, Post $post)
    {
        $redis = $this->getContext()->getRedis();
        $postId = $post->getId();

        $redis->zDelete(self::REDIS_USER_PREFIX . $post->getUserId(), $postId);

        foreach (array_chunk($userIds, self::BUNCH_SIZE) as $chunk) {
            $redis->multi();
            foreach ($chunk as $userId) {
                $redis->zDelete(self::REDIS_HOME_PREFIX . $userId, $postId);
            }
            $redis->exec();
        }
    }

    public function getHomeTimelinePostIds(int $userId, int $start, int $end): array
    {
        $redis = $this->getContext()->getRedis();
        return $redis->zRevRange(self::REDIS_HOME_PREFIX . $userId, $start, $end) ?: [];
    }

    public function getUserTimelinePostIds(int $userId, int $start, int $end): array
    {
        $redis = $this->getContext()->getRedis();
        return $redis->zRevRange(self::REDIS_USER_PREFIX . $userId, $start, $end) ?: [];
    }
}