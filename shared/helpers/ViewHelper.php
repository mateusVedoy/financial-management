<?php

namespace Shared\helpers;

/**
 * Helper para views - funções auxiliares para renderização
 */
class ViewHelper
{
    /**
     * Exibe uma mensagem de alerta formatada
     */
    public static function showAlert(string $message, string $type = 'info'): string
    {
        $validTypes = ['danger', 'success', 'warning', 'info'];
        $type = in_array($type, $validTypes) ? $type : 'info';
        
        return '<div class="alert alert-' . htmlspecialchars($type) . '">' . 
               htmlspecialchars($message) . 
               '</div>';
    }

    /**
     * Sanitiza entrada de dados
     */
    public static function sanitizeInput(string $data): string
    {
        return trim(strip_tags($data));
    }

    /**
     * Redireciona para uma URL
     */
    public static function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: " . $url, true, $statusCode);
        exit;
    }

    /**
     * Formata valor monetário
     */
    public static function formatCurrency(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    /**
     * Obtém mensagem de erro da URL
     */
    public static function getErrorMessage(): string
    {
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
        return self::showAlert($message, 'danger');
    }

    /**
     * Obtém mensagem de sucesso da URL
     */
    public static function getSuccessMessage(): string
    {
        if (!isset($_GET['message'])) {
            return '';
        }
        
        $message = $_GET['message'];
        $messages = [
            'logged_out' => 'Você saiu com sucesso.',
            'registered' => 'Usuário registrado com sucesso! Você já pode fazer login.',
            'logged_in' => 'Login realizado com sucesso!',
            'created' => 'Operação financeira cadastrada com sucesso!'
        ];
        
        $msg = isset($messages[$message]) ? $messages[$message] : '';
        return $msg ? self::showAlert($msg, 'success') : '';
    }
}

// Esse arquivo possui código gerado em colaboração com IA

