<?php

namespace Infrastructure\auth;

/**
 * Serviço de autenticação baseado em sessão
 */
class SessionAuthService
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(int $userId, string $email): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $email;
    }

    public function logout(): void
    {
        $_SESSION = array();
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUserEmail(): ?string
    {
        return $_SESSION['user_email'] ?? null;
    }
}

// Esse arquivo possui código gerado em colaboração com IA

