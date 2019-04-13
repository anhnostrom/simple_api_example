<?php

namespace App;

use Exception;

class APIException extends Exception
{
    private $httpStatusCode;

    /**
     * @param int $httpCode \App\Response::HTTP_STATUS_*
     * @param string $message
     * @return APIException
     */
    public static function HTTPError(int $httpCode, string $message = "unknown error"): APIException
    {
        $exception = new APIException($message);
        $exception->setHTTPStatusCode($httpCode);
        return $exception;
    }

    public function getHTTPStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function setHTTPStatusCode(int $code)
    {
        $this->httpStatusCode = $code;
    }

    public function getResponse(): Response
    {
        return new Response($this->getMessage(), $this->getCode());
    }
}