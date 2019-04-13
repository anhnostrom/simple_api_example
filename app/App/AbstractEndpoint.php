<?php

namespace App;

abstract class AbstractEndpoint
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';

    abstract public function getHTTPMethod(): string;

    abstract public function isAuthRequired(): bool;

}