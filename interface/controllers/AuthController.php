<?php

namespace Interface\controllers;

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

/**
 * Controller para autenticação
 */
class AuthController
{
    /**
     * Processa login
     */
    public function login(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            ViewHelper::redirect('index.php');
            return;
        }

        $email = ViewHelper::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $useCase = Bootstrap::getLoginUseCase();
        $result = $useCase->execute($email, $password);

        if ($result['success']) {
            ViewHelper::redirect('operation_list.php');
        } else {
            $errorParam = $result['error'] === 'Email inválido.' ? 'invalid_email' : 'invalid';
            ViewHelper::redirect('index.php?error=' . urlencode($errorParam));
        }
    }

    /**
     * Processa registro
     */
    public function register(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            ViewHelper::redirect('register.php');
            return;
        }

        $email = ViewHelper::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $useCase = Bootstrap::getRegisterUseCase();
        $result = $useCase->execute($email, $password, $passwordConfirm);

        if ($result['success']) {
            ViewHelper::redirect('index.php?message=registered');
        } else {
            // Determina o tipo de erro
            $error = 'unknown';
            if (strpos($result['error'], 'preencha todos') !== false) {
                $error = 'empty_fields';
            } elseif (strpos($result['error'], 'e-mail') !== false || strpos($result['error'], 'Email') !== false) {
                $error = strpos($result['error'], 'já está') !== false ? 'email_exists' : 'invalid_email';
            } elseif (strpos($result['error'], 'senha') !== false) {
                $error = strpos($result['error'], 'coincidem') !== false ? 'password_mismatch' : 'empty_fields';
            }
            
            ViewHelper::redirect('register.php?error=' . urlencode($error));
        }
    }

    /**
     * Faz logout
     */
    public function logout(): void
    {
        $authService = Bootstrap::getAuthService();
        $authService->logout();
        ViewHelper::redirect('index.php?message=logged_out');
    }
}

// Esse arquivo possui código gerado em colaboração com IA

