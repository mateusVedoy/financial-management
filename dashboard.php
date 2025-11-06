<?php
/**
 * Dashboard
 * 
 * Página principal do sistema após o login.
 */

require 'config.php';
require 'check_auth.php';
require 'includes/functions.php';

// Obtém dados do usuário logado
$user_id = $_SESSION['user_id'];
$user_email = htmlspecialchars($_SESSION['user_email']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestão Financeira</title>
    <!-- Tailwind CSS para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <h1 class="navbar-brand">Sistema de Gestão Financeira</h1>
            <a href="logout.php" class="btn btn-danger">
                Sair
            </a>
        </div>
    </nav>

    <!-- Conteúdo da página -->
    <div class="main-container">
        <div class="card card-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                Bem-vindo!
            </h2>
            <p class="text-lg text-gray-700 mb-2">
                Olá, <span class="font-medium text-blue-600"><?php echo $user_email; ?></span>!
            </p>
            <p class="text-gray-600">
                Você está logado no sistema. Em breve você poderá gerenciar suas receitas e despesas aqui.
            </p>
        </div>
    </div>

</body>
</html>