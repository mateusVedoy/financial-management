<?php
/**
 * Configuração do Sistema
 * 
 * Este arquivo contém as configurações principais do sistema,
 * incluindo conexão com banco de dados e inicialização de sessão.
 */

require 'display_errors.php';

// Inicia a sessão em todas as páginas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Configurações do Banco de Dados ---
$db_host = '127.0.0.1';     // Geralmente 'localhost'
$db_name = 'financial_management';     // O nome do seu banco de dados
$db_user = 'root';   // Seu usuário do MySQL
$db_pass = 'root';     // Sua senha do MySQL
$charset = 'utf8mb4';

// --- DSN (Data Source Name) ---
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

// --- Opções do PDO ---
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// --- Conexão PDO ---
try {
    // Cria a instância do PDO
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (\PDOException $e) {
    // Em caso de erro, exibe a mensagem e para a execução
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>