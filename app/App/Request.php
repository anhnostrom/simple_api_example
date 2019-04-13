<?php

namespace App;

class Request
{
    private $params = [];
    private $headers = [];

    private $body;
    private $context;

    public function __construct(array $params, array $serverParams, Context $context)
    {
        $this->params = $params;

        foreach ($serverParams as $key => $val) {
            if (0 === strpos($key, 'HTTP_')) {
                $headerName = str_replace(
                    ' ',
                    '-',
                    ucwords(
                        str_replace(
                            '_',
                            ' ',
                            strtolower(substr($key, 5))
                        )
                    )
                );
                $this->headers[$headerName] = $val;
            }
        }

        $this->context = $context;
    }

    public function getParam(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function getHeader(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    public function getBody()
    {
        if (null === $this->body) {
            $this->body = file_get_contents('php://input');
        }

        return $this->body;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}