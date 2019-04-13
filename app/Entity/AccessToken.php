<?php

namespace Entity;

class AccessToken
{
    private $id;
    private $token_hash;
    private $token;
    private $user_id;
    private $created_at;
    private $expired_at;

    public function getUserId(): int
    {
        return $this->user_id;
    }
}