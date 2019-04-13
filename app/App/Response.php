<?php

namespace App;

class Response
{
    public const HTTP_STATUS_OK = 200;
    public const HTTP_STATUS_NOT_FOUND = 404;
    public const HTTP_STATUS_BAD_REQUEST = 400;
    public const HTTP_STATUS_UNAUTHORIZED = 401;
    public const HTTP_STATUS_ACCESS_DENIED = 403;
    public const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;

    private $body;

    private $httpStatusCode;

    private $isError = false;

    public function __construct($result = null, int $httpCode = self::HTTP_STATUS_OK)
    {
        $this->body = $result;
        $this->httpStatusCode = $httpCode;
    }

    public function setBody($data)
    {
        $this->body = $data;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setHTTPStatusCode(int $code)
    {
        $this->httpStatusCode = $code;
    }

    public function getHTTPStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getIsError(): bool
    {
        return $this->isError;
    }

    public function setIsError(bool $isError)
    {
        $this->isError = $isError;
    }
}