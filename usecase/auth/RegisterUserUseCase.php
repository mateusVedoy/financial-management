<?php

namespace UseCase\auth;

use Domain\entities\User;
use Domain\repositories\UserRepositoryInterface;
use Domain\value_objects\Email;

/**
 * Use Case para registro de novo usuário
 */
class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Executa o registro de um novo usuário
     * 
     * @param string $email Email do usuário
     * @param string $password Senha do usuário
     * @param string $passwordConfirm Confirmação da senha
     * @return array ['success' => bool, 'user' => User|null, 'error' => string|null]
     */
    public function execute(string $email, string $password, string $passwordConfirm): array
    {
        // Validações básicas
        if (empty($email) || empty($password) || empty($passwordConfirm)) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'Por favor, preencha todos os campos.'
            ];
        }

        if (strlen($password) < 6) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'A senha deve ter pelo menos 6 caracteres.'
            ];
        }

        if ($password !== $passwordConfirm) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'As senhas não coincidem.'
            ];
        }

        try {
            $emailVO = new Email($email);
        } catch (\InvalidArgumentException $e) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'Formato de e-mail inválido.'
            ];
        }

        // Verifica se o email já existe
        if ($this->userRepository->emailExists($emailVO)) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'Este e-mail já está cadastrado.'
            ];
        }

        // Cria o hash da senha
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Cria o usuário
        $user = new User($emailVO, $passwordHash);

        // Salva no repositório
        try {
            $user = $this->userRepository->save($user);
            
            return [
                'success' => true,
                'user' => $user,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'user' => null,
                'error' => 'Erro ao registrar usuário: ' . $e->getMessage()
            ];
        }
    }
}

// Esse arquivo possui código gerado em colaboração com IA

