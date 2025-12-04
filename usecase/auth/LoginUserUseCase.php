<?php

namespace UseCase\auth;

use Domain\entities\User;
use Domain\repositories\UserRepositoryInterface;
use Domain\value_objects\Email;
use Infrastructure\Auth\SessionAuthService;

/**
 * Use Case para autenticação de usuário
 */
class LoginUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private SessionAuthService $authService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        SessionAuthService $authService
    ) {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    /**
     * Executa o login do usuário
     * 
     * @param string $email Email do usuário
     * @param string $password Senha do usuário
     * @return array ['success' => bool, 'user' => User|null, 'error' => string|null]
     */
    public function execute(string $email, string $password): array
    {
        try {
            $emailVO = new Email($email);
        } catch (\InvalidArgumentException $e) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'Email inválido.'
            ];
        }

        $user = $this->userRepository->findByEmail($emailVO);

        if (!$user || !$user->verifyPassword($password)) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'Email ou senha inválidos.'
            ];
        }

        // Autentica o usuário na sessão
        $this->authService->login($user->getId(), $user->getEmail()->getValue());

        return [
            'success' => true,
            'user' => $user,
            'error' => null
        ];
    }
}

// Esse arquivo possui código gerado em colaboração com IA

