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

class DeletePost extends AbstractEndpoint
{
    public function getHTTPMethod(): string
    {
        return self::HTTP_METHOD_POST;
    }

    public function isAuthRequired(): bool
    {
        return true;
    }

    public function v1Action(Request $request): Response
    {
        $postId = (int) $request->getParam('post_id');
        if (!$postId) {
            throw APIException::HTTPError(Response::HTTP_STATUS_BAD_REQUEST, 'post_id is required');
        }

        $timeline = new TimelineService(
            new PostRepository($request->getContext()),
            new TimelineRepository($request->getContext()),
            new FollowerRepository($request->getContext())
        );

        $timeline->deletePost($request->getContext()->getUser(), $postId);

        return new Response(['status' => 'ok']);
    }

}