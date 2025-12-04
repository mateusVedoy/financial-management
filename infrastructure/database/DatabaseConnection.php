<?php

namespace Infrastructure\database;

use PDO;
use PDOException;

/**
 * Encapsula a conexão com o banco de dados
 */
class DatabaseConnection
{
    private PDO $pdo;

    public function __construct(
        string $host = '127.0.0.1',
        string $database = 'financial_management',
        string $username = 'root',
        string $password = 'root',
        string $charset = 'utf8mb4'
    ) {
        $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}

// Esse arquivo possui código gerado em colaboração com IA

