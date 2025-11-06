<?php
/**
 * Página de Registro
 * 
 * Permite que novos usuários se registrem no sistema.
 */

require 'config.php';
require 'includes/functions.php';

// Se o usuário já estiver logado, redireciona para o dashboard
if (isAuthenticated()) {
    redirect('dashboard.php');
}

// Inicializa a mensagem de feedback
$message = '';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Coleta e sanitiza os dados do formulário
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validações
    if (empty($email) || empty($password) || empty($password_confirm)) {
        $message = showAlert('Por favor, preencha todos os campos.', 'danger');
    } elseif (!validateEmail($email)) {
        $message = showAlert('Formato de e-mail inválido.', 'danger');
    } elseif (strlen($password) < 6) {
        $message = showAlert('A senha deve ter pelo menos 6 caracteres.', 'danger');
    } elseif ($password !== $password_confirm) {
        $message = showAlert('As senhas não coincidem.', 'danger');
    } else {
        // Verifica se o e-mail já existe
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $message = showAlert('Este e-mail já está cadastrado.', 'warning');
            } else {
                // Cria o hash da senha (ESSENCIAL PARA SEGURANÇA)
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insere o novo usuário no banco
                $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
                $stmt->execute([$email, $password_hash]);
                
                // Redireciona para login com mensagem de sucesso
                redirect('index.php?message=registered');
            }
        } catch (PDOException $e) {
            $message = showAlert('Erro ao registrar: ' . htmlspecialchars($e->getMessage()), 'danger');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Sistema de Gestão Financeira</title>
    <!-- Tailwind CSS para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="font-sans antialiased">
    <div class="auth-page min-h-screen flex items-center justify-center">
        <div class="form-container">
            <h2 class="form-title">Criar Conta</h2>
            <p class="form-subtitle">Comece a gerenciar suas finanças hoje</p>

            <!-- Exibe mensagens de feedback (erros ou sucesso) -->
            <?php echo $message; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" required
                           class="form-input" placeholder="seu@email.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" id="password" name="password" required
                           class="form-input" placeholder="Mínimo 6 caracteres" minlength="6">
                </div>
                <div class="form-group">
                    <label for="password_confirm" class="form-label">Confirmar Senha</label>
                    <input type="password" id="password_confirm" name="password_confirm" required
                           class="form-input" placeholder="Confirme sua senha" minlength="6">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-full">
                        Registrar
                    </button>
                </div>
            </form>
            <p class="text-center text-sm text-gray-600 mt-6">
                Já tem uma conta?
                <a href="index.php" class="auth-link">
                    Faça login aqui
                </a>
            </p>
        </div>
    </div>
</body>
</html>