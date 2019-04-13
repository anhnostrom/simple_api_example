<?php

namespace App;

use Exception;
use Repository\AccessTokenRepository;
use Repository\UserRepository;
use Service\AuthService;

class Application
{
    private $request;

    private function getRequest(): Request
    {
        if (null === $this->request) {
            $request = new Request($_REQUEST, $_SERVER, Context::i());

            $this->request = $request;
        }
        return $this->request;
    }

    private function handleRequest(): Response
    {
        $url = $_SERVER['REQUEST_URI'];
        if (!$url) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'endpoint does not exist');
        }

        $urlParts = explode('/', parse_url($url)['path']);
        if (count($urlParts) < 3) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'endpoint does not exist');
        }

        [, $version, $endpointName] = $urlParts;

        $className = '\\Endpoint\\' . ucfirst($endpointName);
        if (!class_exists($className)) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'endpoint does not exist');
        }

        $classMethod = $version . 'Action';
        if (!method_exists($className, $classMethod)) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'endpoint does not exist');
        }

        /** @var AbstractEndpoint $endpoint */
        $endpoint = new $className();
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        if ($endpoint->getHTTPMethod() !== $httpMethod) {
            throw APIException::HTTPError(Response::HTTP_STATUS_NOT_FOUND, 'endpoint does not exist');
        }

        if ($endpoint->isAuthRequired()) {
            $accessToken = $this->getRequest()->getHeader('X-Access-Token');
            if (!$accessToken) {
                throw APIException::HTTPError(
                    Response::HTTP_STATUS_UNAUTHORIZED,
                    'access token required'
                );
            }

            $context = Context::i();
            $authService = new AuthService(
                new AccessTokenRepository($context),
                new UserRepository($context)
            );
            $user = $authService->findUser($accessToken);
            if (!$user) {
                throw APIException::HTTPError(
                    Response::HTTP_STATUS_UNAUTHORIZED,
                    'access token not valid or expired'
                );
            }
            $context->setUser($user);
        }

        $response = $endpoint->$classMethod($this->getRequest());
        if (!$response instanceof Response) {
            throw APIException::HTTPError(Response::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    public function run()
    {
        /** @var Response $response */
        $response = null;
        try {
            $response = $this->handleRequest();
        } catch (APIException $e) {
            $response = new Response($e->getMessage(), $e->getHTTPStatusCode());
            $response->setIsError(true);
        } catch (Exception $e) {
            $response = new Response($e->getMessage(), Response::HTTP_STATUS_INTERNAL_SERVER_ERROR);
            $response->setIsError(true);
        }

        $this->sendResponse($response);
    }

    private function sendResponse(Response $response)
    {
        http_response_code($response->getHTTPStatusCode());
        header('Content-type: application/json');
        $data = $response->getIsError()
            ? ['error' => $response->getBody()]
            : ['result' => $response->getBody()];
        echo json_encode($data);
    }

    public function fatalHandler()
    {
        $error = error_get_last();
        if (null !== $error) {
            return;
        }

        if (E_ERROR !== $error['type']) {
            return;
        }

        $response = new Response("unknown error", Response::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        $response->setIsError(true);
        $this->sendResponse($response);
    }

}