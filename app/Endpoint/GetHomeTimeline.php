<?php

namespace Endpoint;

use App\AbstractEndpoint;
use App\APIException;
use App\Request;
use App\Response;
use Repository\FollowerRepository;
use Repository\PostRepository;
use Repository\TimelineRepository;
use Service\TimelineService;

class GetHomeTimeline extends AbstractEndpoint
{
    const MAX_POST_LIMIT = 25;

    public function getHTTPMethod(): string
    {
        return self::HTTP_METHOD_GET;
    }

    public function isAuthRequired(): bool
    {
        return true;
    }

    public function v1Action(Request $request): Response
    {
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
        $timeline = new TimelineService(
            new PostRepository($context),
            new TimelineRepository($context),
            new FollowerRepository($context)
        );

        return new Response($timeline->getHomeTimeline($context->getUser(), $limit, $offset));
    }

}