<?php
/**
 * Logout
 * 
 * Encerra a sessão do usuário e redireciona para a página de login.
 */

require 'config.php';
require 'includes/functions.php';

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Destrói a sessão
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

// Redireciona para a página de login com uma mensagem de sucesso
redirect('index.php?message=logged_out');
?>