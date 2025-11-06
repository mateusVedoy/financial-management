-- criar usuário para banco e dar permissões



CREATE DATABASE IF NOT EXISTS financial_management;

USE financial_management;

-- 3. CRIA TABELA DE USUARIO (usadas para autenticar usuário)
CREATE TABLE `users` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


