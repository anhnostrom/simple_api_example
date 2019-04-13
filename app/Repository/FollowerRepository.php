<?php

namespace Repository;

use App\AbstractRepository;

class FollowerRepository extends AbstractRepository
{
    const REDIS_FOLLOWER_PREFIX = 'followers#';
    const REDIS_SUBSCRIPTION_PREFIX = 'subscriptions#';

    public function getFollowerIds(int $userId): array
    {
        $redis = $this->getContext()->getRedis();
        return $redis->sMembers(self::REDIS_FOLLOWER_PREFIX . $userId) ?: [];
    }

    public function addFollower(int $userId, int $followUserId)
    {
        $redis = $this->getContext()->getRedis();
        $redis->multi();
        $redis->sAdd(self::REDIS_FOLLOWER_PREFIX . $followUserId, $userId);
        $redis->sAdd(self::REDIS_SUBSCRIPTION_PREFIX . $userId, $followUserId);
        $redis->exec();
    }
}