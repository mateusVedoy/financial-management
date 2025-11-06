<?php
/**
 * Verificação de Autenticação
 * 
 * Este arquivo verifica se o usuário está autenticado.
 * Se não estiver, redireciona para a página de login.
 */

// Garante que a sessão está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Usuário não está logado, redireciona para login
    header("Location: index.php?error=not_logged_in");
    exit;
}

