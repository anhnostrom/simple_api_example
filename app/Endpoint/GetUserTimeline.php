<?php

namespace Endpoint;

use App\AbstractEndpoint;
use App\APIException;
use App\Request;
use App\Response;
use Repository\FollowerRepository;
use Repository\PostRepository;
use Repository\TimelineRepository;
use Repository\UserRepository;
use Service\TimelineService;

class GetUserTimeline extends AbstractEndpoint
{
    const MAX_POST_LIMIT = 25;

    public function getHTTPMethod(): string
    {
        return self::HTTP_METHOD_GET;
    }

    public function isAuthRequired(): bool
    {
        return false;
    }

    public function v1Action(Request $request): Response
    {
        $userId = (int) $request->getParam('user_id');
        if (!$userId) {
            throw APIException::HTTPError(Response::HTTP_STATUS_BAD_REQUEST, "user_id required");
        }

        $limit = (int) $request->getParam('limit', self::MAX_POST_LIMIT);
        if ($limit < 1) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_BAD_REQUEST,
                "limit must be greater than 0"
            );
        }
        if ($limit > self::MAX_POST_LIMIT) {
            $limit = self::MAX_POST_LIMIT;
        }

        $offset = (int) $request->getParam('offset', 0);
        if ($offset < 0) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_BAD_REQUEST,
                "offset must be greater than 0"
            );
        }

        $context = $request->getContext();
        $userRepo = new UserRepository($context);
        $user = $userRepo->find($userId);
        if (!$user) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, "user doesn't exist");
        }

        $timeline = new TimelineService(
            new PostRepository($context),
            new TimelineRepository($context),
            new FollowerRepository($context)
        );

        return new Response($timeline->getUserTimeline($user, $limit, $offset));
    }
}