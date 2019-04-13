<?php

namespace Endpoint;

use App\AbstractEndpoint;
use App\APIException;
use App\Request;
use App\Response;
use Repository\PostRepository;

class GetPost extends AbstractEndpoint
{
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
        $postId = (int) $request->getParam('post_id');
        if (!$postId) {
            throw APIException::HTTPError(Response::HTTP_STATUS_BAD_REQUEST, 'post_id is required');
        }

        $postRepo = new PostRepository($request->getContext());
        $post = $postRepo->find($postId);
        if (!$post) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'post is not found');
        }

        return new Response($post);
    }
}