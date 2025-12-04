<?php
/**
 * Processamento de Login
 * 
 * Processa o formulário de login e autentica o usuário.
 */

require __DIR__ . '/autoload.php';
require __DIR__ . '/display_errors.php';

use Interface\Bootstrap;

// Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new \Interface\controllers\AuthController();
$controller->login();
