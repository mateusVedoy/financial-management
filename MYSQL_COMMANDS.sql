-- Cria o banco de dados se ele não existir
CREATE DATABASE IF NOT EXISTS financial_management;

-- Seleciona o banco de dados para usar
USE financial_management;

-- 1. CRIA TABELA DE USUARIO (Fornecida por você)
CREATE TABLE `users` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. CRIA TABELA DE TIPO FINANCEIRO (Receita / Despesa)
-- Esta tabela é necessária para a tabela de categorias.
CREATE TABLE `financial_types` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Ex: Receita, Despesa',
    description VARCHAR(255)
);

-- 3. CRIA TABELA DE CATEGORIAS FINANCEIRAS
-- Esta tabela usa a FK da tabela 'financial_types'
CREATE TABLE `financial_categories` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    financial_type_id INT NOT NULL,
    
    FOREIGN KEY (`financial_type_id`) REFERENCES `financial_types` (`id`),
    UNIQUE KEY `UQ_name_type` (`name`, `financial_type_id`)
);

-- 4. CRIA TABELA DE OPERAÇÕES FINANCEIRAS (Lançamentos)
-- Esta é a versão que você solicitou, com FK para Usuário, Categoria e Tipo.
CREATE TABLE `financial_operations` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value DECIMAL(10, 2) NOT NULL,
    moment TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Chaves Estrangeiras
    user_id INT NOT NULL,
    financial_category_id INT NOT NULL,
    
    -- FK redundante solicitada por você (para 'financial_types')
    financial_type_id INT NOT NULL, 
    
    FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) 
        ON DELETE CASCADE,
    
    FOREIGN KEY (`financial_category_id`) 
        REFERENCES `financial_categories` (`id`),
        
    FOREIGN KEY (`financial_type_id`) -- Referência direta para a tabela de tipos
        REFERENCES `financial_types` (`id`)
);

-- 5. INSERTS DE DADOS FIXOS (Tipos e Categorias)

-- Inserindo os Tipos Financeiros (Fixos)
INSERT INTO `financial_types` (name, description) VALUES
('Receita', 'Entrada de valores'),
('Despesa', 'Saída de valores');

-- Inserindo as Categorias Financeiras
-- (Assumindo que 'Receita' teve o ID 1 e 'Despesa' teve o ID 2)

-- Categorias de Receita (Tipo ID: 1)
INSERT INTO `financial_categories` (name, financial_type_id, description) VALUES
('Salário', 1, 'Recebimento de salário principal'),
('Vale Alimentação/Refeição', 1, 'Benefício de alimentação/refeição'),
('Investimentos', 1, 'Rendimentos de investimentos'),
('Extra', 1, 'Renda extra, freelancer, bônus, 13º');

-- Categorias de Despesa (Tipo ID: 2)
INSERT INTO `financial_categories` (name, financial_type_id, description) VALUES
('Alimentação', 2, 'Compras de supermercado, restaurantes, delivery'),
('Saúde', 2, 'Plano de saúde, farmácia, consultas'),
('Moradia', 2, 'Aluguel, condomínio, financiamento'),
('Água', 2, 'Conta de água'),
('Luz', 2, 'Conta de energia elétrica'),
('Telefone', 2, 'Conta de telefone/celular'),
('Internet', 2, 'Conta de internet'),
('Transporte', 2, 'Combustível, transporte público, Uber'),
('Lazer', 2, 'Cinema, streaming, passeios');