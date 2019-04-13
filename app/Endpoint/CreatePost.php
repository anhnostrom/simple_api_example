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

class CreatePost extends AbstractEndpoint
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
        $body = $request->getBody();
        if (!$body) {
            throw APIException::HTTPError(Response::HTTP_STATUS_BAD_REQUEST, 'request body is empty');
        }

        $data = json_decode($body, true);
        if (!$data) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_BAD_REQUEST,
                'request body is not valid'
            );
        }

        $text = trim($data['text'] ?? '');
        if (!$text) {
            throw APIException::HTTPError(Response::HTTP_STATUS_BAD_REQUEST, 'text field required');
        }

        if (mb_strlen($text) > 140) {
            throw APIException::HTTPError(
                Response::HTTP_STATUS_BAD_REQUEST,
                'text must be 140 symbols or less'
            );
        }

        $context = $request->getContext();
        $user = $context->getUser();
        $timeline = new TimelineService(
            new PostRepository($context),
            new TimelineRepository($context),
            new FollowerRepository($context)
        );
        $post = $timeline->createPost($user, $text);
        return new Response($post);
    }
}