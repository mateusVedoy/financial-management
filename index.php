<?php
/**
 * Página de Login
 * 
 * Permite que o usuário faça login no sistema.
 */

require 'config.php';
require 'includes/functions.php';

// Se o usuário já estiver logado, redireciona para o dashboard
if (isAuthenticated()) {
    redirect('dashboard.php');
}

// Obtém mensagens de erro e sucesso
$error_message = getErrorMessage();
$success_message = getSuccessMessage();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestão Financeira</title>
    <!-- Tailwind CSS para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="font-sans antialiased">
    <div class="auth-page min-h-screen flex items-center justify-center">
        <div class="form-container">
            <h2 class="form-title">Login</h2>
            <p class="form-subtitle">Acesse sua conta para gerenciar suas finanças</p>

            <!-- Exibe mensagens de erro ou sucesso -->
            <?php echo $error_message; ?>
            <?php echo $success_message; ?>

            <form action="login_process.php" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" required
                           class="form-input" placeholder="seu@email.com">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" id="password" name="password" required
                           class="form-input" placeholder="Sua senha">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-full">
                        Entrar
                    </button>
                </div>
            </form>
            <p class="text-center text-sm text-gray-600 mt-6">
                Não tem uma conta?
                <a href="register.php" class="auth-link">
                    Registre-se aqui
                </a>
            </p>
        </div>
    </div>
</body>
</html>