<?php
/**
 * View de Registro
 * 
 * Variáveis esperadas:
 * @var string $message Mensagem de feedback
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Sistema de Gestão Financeira</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="font-sans antialiased">
    <div class="auth-page min-h-screen flex items-center justify-center">
        <div class="form-container">
            <h2 class="form-title">Criar Conta</h2>
            <p class="form-subtitle">Comece a gerenciar suas finanças hoje</p>

            <?php echo $message ?? ''; ?>

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

