<?php
/**
 * Página de Login
 * 
 * Permite que o usuário faça login no sistema.
 */

echo "entrou";

require __DIR__ . '/autoload.php';
require __DIR__ . '/display_errors.php';

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

// Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se o usuário já estiver logado, redireciona para o dashboard
$authService = Bootstrap::getAuthService();
if ($authService->isAuthenticated()) {
    ViewHelper::redirect('operation_list.php');
}

// Obtém mensagens de erro e sucesso
$error_message = ViewHelper::getErrorMessage();
$success_message = ViewHelper::getSuccessMessage();

// Inclui a view
require __DIR__ . '/interface/views/auth/login.php';
