<?php
/**
 * Processamento de Login
 * 
 * Processa o formulário de login e autentica o usuário.
 */

require 'config.php';
require 'includes/functions.php';

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('index.php');
}

// Coleta e sanitiza dados do formulário
$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validações básicas
if (empty($email) || empty($password)) {
    redirect('index.php?error=empty_fields');
}

if (!validateEmail($email)) {
    redirect('index.php?error=invalid_email');
}

// Busca o usuário pelo email
try {
    $stmt = $pdo->prepare("SELECT id, email, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifica se o usuário existe e se a senha está correta
    if ($user && password_verify($password, $user['password_hash'])) {
        // Sucesso! A senha está correta.
        
        // Regenera o ID da sessão para prevenir "fixação de sessão"
        session_regenerate_id(true);

        // Armazena os dados do usuário na sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        
        // Redireciona para a página protegida
        redirect('dashboard.php');
    } else {
        // Falha no login (usuário não encontrado ou senha incorreta)
        redirect('index.php?error=invalid');
    }
} catch (PDOException $e) {
    // Erro ao consultar banco de dados
    error_log("Erro no login: " . $e->getMessage());
    redirect('index.php?error=invalid');
}
?>