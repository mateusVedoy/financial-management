<?php
/**
 * Página de Registro
 * 
 * Permite que novos usuários se registrem no sistema.
 */

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

// Inicializa a mensagem de feedback
$message = '';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller = new \Interface\controllers\AuthController();
    $controller->register();
    return; // O controller redireciona, mas caso não redirecione:
}

// Se chegou aqui, mostra o formulário
// Verifica se há erro na URL
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $messages = [
        'empty_fields' => 'Por favor, preencha todos os campos.',
        'email_exists' => 'Este e-mail já está cadastrado.',
        'password_mismatch' => 'As senhas não coincidem.',
        'invalid_email' => 'Formato de e-mail inválido.'
    ];
    
    $message = isset($messages[$error]) 
        ? ViewHelper::showAlert($messages[$error], 'danger') 
        : ViewHelper::showAlert('Ocorreu um erro.', 'danger');
}

// Inclui a view
require __DIR__ . '/interface/views/auth/register.php';
