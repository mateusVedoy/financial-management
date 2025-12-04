<?php
/**
 * View de Dashboard
 * 
 * Variáveis esperadas:
 * @var string $user_email Email do usuário
 * @var array $stats Array com receitas, despesas, saldo, estado
 */
use Shared\helpers\ViewHelper;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestão Financeira</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                Gerencie suas receitas e despesas de forma simples e eficiente.
            </p>
            
            <!-- Cards de estatísticas financeiras (últimos 30 dias) -->
            <div class="financial-stats mb-6">
                <div class="stat-card income">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💰</div>
                    <h3 style="font-weight: 600; color: #10b981; margin-bottom: 0.25rem;">Receitas (30 dias)</h3>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #10b981;">
                        <?php echo ViewHelper::formatCurrency($stats['receitas']); ?>
                    </p>
                </div>
                <div class="stat-card expense">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💸</div>
                    <h3 style="font-weight: 600; color: #ef4444; margin-bottom: 0.25rem;">Despesas (30 dias)</h3>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #ef4444;">
                        <?php echo ViewHelper::formatCurrency($stats['despesas']); ?>
                    </p>
                </div>
                <div class="stat-card" style="border-left-color: <?php echo $stats['saldo'] >= 0 ? '#10b981' : '#ef4444'; ?>;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📈</div>
                    <h3 style="font-weight: 600; color: #3b82f6; margin-bottom: 0.25rem;">Saldo (30 dias)</h3>
                    <p style="font-size: 1.5rem; font-weight: 700; color: <?php echo $stats['saldo'] >= 0 ? '#10b981' : '#ef4444'; ?>;">
                        <?php echo ViewHelper::formatCurrency($stats['saldo']); ?>
                    </p>
                </div>
            </div>

            <!-- Ações rápidas -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
                <a href="operation_create.php" class="card" style="text-align: center; text-decoration: none; transition: transform 0.2s; cursor: pointer; border: 2px solid #667eea;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">➕</div>
                    <h3 style="font-weight: 600; color: #667eea; margin-bottom: 0.25rem;">Nova Operação</h3>
                    <p style="color: #6b7280; font-size: 0.875rem;">Cadastrar receita ou despesa</p>
                </a>
                <a href="operation_list.php" class="card" style="text-align: center; text-decoration: none; transition: transform 0.2s; cursor: pointer; border: 2px solid #3b82f6;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📋</div>
                    <h3 style="font-weight: 600; color: #3b82f6; margin-bottom: 0.25rem;">Listar Operações</h3>
                    <p style="color: #6b7280; font-size: 0.875rem;">Ver todas as operações</p>
                </a>
                <a href="financial_statement.php" class="card" style="text-align: center; text-decoration: none; transition: transform 0.2s; cursor: pointer; border: 2px solid #10b981;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📊</div>
                    <h3 style="font-weight: 600; color: #10b981; margin-bottom: 0.25rem;">Demonstrativo</h3>
                    <p style="color: #6b7280; font-size: 0.875rem;">Balanço financeiro</p>
                </a>
            </div>
        </div>
    </div>

</body>
</html>

