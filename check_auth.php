<?php
/**
 * Verificação de Autenticação
 * 
 * Este arquivo verifica se o usuário está autenticado.
 * Se não estiver, redireciona para a página de login.
 */

require __DIR__ . '/autoload.php';

// Garante que a sessão está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

// Verifica se o usuário está logado
$authService = Bootstrap::getAuthService();
if (!$authService->isAuthenticated()) {
    // Usuário não está logado, redireciona para login
    ViewHelper::redirect('index.php?error=not_logged_in');
}
