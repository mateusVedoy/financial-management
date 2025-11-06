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
<body class="dashboard-page font-sans antialiased">

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
            <span class="welcome-icon">📊</span>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                Bem-vindo ao seu Dashboard!
            </h2>
            <p class="text-lg text-gray-700 mb-2">
                Olá, <span class="font-medium" style="color: #667eea;"><?php echo $user_email; ?></span>!
            </p>
            <p class="text-gray-600 mb-6">
                Você está logado no sistema. Em breve você poderá gerenciar suas receitas e despesas aqui.
            </p>
            
            <!-- Cards de estatísticas financeiras (placeholder) -->
            <div class="financial-stats">
                <div class="stat-card income">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💰</div>
                    <h3 style="font-weight: 600; color: #10b981; margin-bottom: 0.25rem;">Receitas</h3>
                    <p style="color: #6b7280; font-size: 0.875rem;">Em breve</p>
                </div>
                <div class="stat-card expense">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💸</div>
                    <h3 style="font-weight: 600; color: #ef4444; margin-bottom: 0.25rem;">Despesas</h3>
                    <p style="color: #6b7280; font-size: 0.875rem;">Em breve</p>
                </div>
                <div class="stat-card">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📈</div>
                    <h3 style="font-weight: 600; color: #3b82f6; margin-bottom: 0.25rem;">Saldo</h3>
                    <p style="color: #6b7280; font-size: 0.875rem;">Em breve</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>