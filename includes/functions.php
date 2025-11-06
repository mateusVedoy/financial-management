<?php
/**
 * Funções Auxiliares
 * 
 * Este arquivo contém funções reutilizáveis para o sistema.
 */

/**
 * Exibe uma mensagem de alerta formatada
 * 
 * @param string $message Mensagem a ser exibida
 * @param string $type Tipo do alerta (danger, success, warning, info)
 * @return string HTML formatado do alerta
 */
function showAlert($message, $type = 'info') {
    $validTypes = ['danger', 'success', 'warning', 'info'];
    $type = in_array($type, $validTypes) ? $type : 'info';
    
    return '<div class="alert alert-' . htmlspecialchars($type) . '">' . 
           htmlspecialchars($message) . 
           '</div>';
}

/**
 * Valida formato de email
 * 
 * @param string $email Email a ser validado
 * @return bool True se válido, False caso contrário
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitiza entrada de dados
 * 
 * @param string $data Dados a serem sanitizados
 * @return string Dados sanitizados
 */
function sanitizeInput($data) {
    return trim(strip_tags($data));
}

/**
 * Verifica se o usuário está autenticado
 * 
 * @return bool True se autenticado, False caso contrário
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redireciona para uma URL
 * 
 * @param string $url URL de destino
 * @param int $statusCode Código de status HTTP
 */
function redirect($url, $statusCode = 302) {
    header("Location: " . $url, true, $statusCode);
    exit;
}

/**
 * Obtém mensagem de erro da URL
 * 
 * @return string Mensagem formatada ou string vazia
 */
function getErrorMessage() {
    if (!isset($_GET['error'])) {
        return '';
    }
    
    $error = $_GET['error'];
    $messages = [
        'invalid' => 'Email ou senha inválidos.',
        'not_logged_in' => 'Você precisa estar logado para acessar esta página.',
        'empty_fields' => 'Por favor, preencha todos os campos.',
        'email_exists' => 'Este e-mail já está cadastrado.',
        'password_mismatch' => 'As senhas não coincidem.',
        'invalid_email' => 'Formato de e-mail inválido.'
    ];
    
    $message = isset($messages[$error]) ? $messages[$error] : 'Ocorreu um erro.';
    return showAlert($message, 'danger');
}

/**
 * Obtém mensagem de sucesso da URL
 * 
 * @return string Mensagem formatada ou string vazia
 */
function getSuccessMessage() {
    if (!isset($_GET['message'])) {
        return '';
    }
    
    $message = $_GET['message'];
    $messages = [
        'logged_out' => 'Você saiu com sucesso.',
        'registered' => 'Usuário registrado com sucesso! Você já pode fazer login.',
        'logged_in' => 'Login realizado com sucesso!'
    ];
    
    $msg = isset($messages[$message]) ? $messages[$message] : '';
    return $msg ? showAlert($msg, 'success') : '';
}

