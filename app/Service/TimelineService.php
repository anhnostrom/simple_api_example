<?php

namespace Service;

use App\APIException;
use App\Response;
use Entity\Post;
use Entity\User;
use Repository\FollowerRepository;
use Repository\PostRepository;
use Repository\TimelineRepository;

class TimelineService
{
    /**
     * @var PostRepository
     */
    private $postRepo;

    /**
     * @var TimelineRepository
     */
    private $timelineRepo;

    /**
     * @var FollowerRepository
     */
    private $followerRepo;

    public function __construct(
        PostRepository $postRepo,
        TimelineRepository $timelineRepo,
        FollowerRepository $followerRepo
    )
    {
        $this->postRepo = $postRepo;
        $this->timelineRepo = $timelineRepo;
        $this->followerRepo = $followerRepo;
    }

    public function createPost(User $user, string $text): Post
    {
        $postId = $this->postRepo->create($user, $text);
        $post = $this->postRepo->find($postId);
        if (!$post) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'post is not found');
        }

        $followerIds = $this->followerRepo->getFollowerIds($user->getId());
        if (!$followerIds) {
            return $post;
        }

        $this->timelineRepo->addPost($followerIds, $post);
        return $post;
    }

    public function deletePost(User $user, int $postId): bool
    {
        $post = $this->postRepo->find($postId);
        if (!$post) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'post is not found');
        }

        if ($post->getUserId() !== $user->getId()) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_ACCESS_DENIED,
                'you are not the owner of the post'
            );
        }

        if (!$this->postRepo->delete($postId)) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_INTERNAL_SERVER_ERROR,
                'can not delete post'
            );
        }

        $followerIds = $this->followerRepo->getFollowerIds($user->getId());
        if (!$followerIds) {
            return true;
        }

        $this->timelineRepo->removePost($followerIds, $post);
        return true;
    }

    public function editPost(User $user, int $postId, string $text): bool
    {
        $post = $this->postRepo->find($postId);
        if (!$post) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'post is not found');
        }

        if ($post->getUserId() !== $user->getId()) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_ACCESS_DENIED,
                'you are not the owner of the post'
            );
        }

        if (!$this->postRepo->edit($post->getId(), $text)) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_INTERNAL_SERVER_ERROR,
                'can not update post'
            );
        }

        return true;
    }

    public function getHomeTimeline(User $user, int $limit = 25, int $offset = 0): array
    {
        $postIds = $this->timelineRepo->getHomeTimelinePostIds($user->getId(), $offset, $offset + $limit - 1);
        if (!$postIds) {
            return [];
        }

        return $this->postRepo->findByIds($postIds);
    }

    public function getUserTimeline(User $user, int $limit = 25, int $offset = 0): array
    {
        $postIds = $this->timelineRepo->getUserTimelinePostIds($user->getId(), $offset, $offset + $limit - 1);
        if (!$postIds) {
            return [];
        }

        return $this->postRepo->findByIds($postIds);
    }
}