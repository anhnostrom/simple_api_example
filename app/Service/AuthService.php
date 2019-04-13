<?php

namespace Service;

use Entity\User;
use Repository\AccessTokenRepository;
use Repository\UserRepository;

/**
 * Имитация сервиса авторизации. Обменивает токен на пользователя
 *
 * Class AuthService
 * @package Service
 */
class AuthService
{
    private $tokenRepo;
    private $userRepo;

    public function __construct(AccessTokenRepository $tokenRepo, UserRepository $userRepo)
    {
        $this->tokenRepo = $tokenRepo;
        $this->userRepo = $userRepo;
    }

    public function findUser(string $accessToken): ?User
    {
        $token = $this->tokenRepo->find($accessToken);
        if (null === $token) {
            return null;
        }

        return $this->userRepo->find($token->getUserId());
    }
}