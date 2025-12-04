<?php
/**
 * Logout
 * 
 * Encerra a sessão do usuário e redireciona para a página de login.
 */

require __DIR__ . '/autoload.php';
require __DIR__ . '/display_errors.php';

// Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new \Interface\controllers\AuthController();
$controller->logout();
