<?php
/**
 * Funções Auxiliares para Operações Financeiras
 * 
 * Este arquivo contém funções relacionadas a operações financeiras,
 * categorias e tipos financeiros.
 */

/**
 * Obtém todos os tipos financeiros (Receita/Despesa)
 * 
 * @param PDO $pdo Conexão com banco de dados
 * @return array Array com tipos financeiros
 */
function getFinancialTypes($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM financial_types ORDER BY name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erro ao buscar tipos financeiros: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtém categorias financeiras por tipo
 * 
 * @param PDO $pdo Conexão com banco de dados
 * @param int $tipoId ID do tipo financeiro (receita/despesa)
 * @return array Array com categorias
 */
function getCategoriesByType($pdo, $tipoId) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM financial_categories WHERE financial_type_id = ? ORDER BY name");
        $stmt->execute([$tipoId]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erro ao buscar categorias: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtém todas as categorias financeiras
 * 
 * @param PDO $pdo Conexão com banco de dados
 * @return array Array com todas as categorias
 */
function getAllCategories($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM financial_categories ORDER BY financial_type_id, name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erro ao buscar categorias: " . $e->getMessage());
        return [];
    }
}

/**
 * Formata valor monetário
 * 
 * @param float $value Valor a ser formatado
 * @return string Valor formatado
 */
function formatCurrency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

/**
 * Valida valor monetário
 * 
 * @param mixed $value Valor a ser validado
 * @return bool True se válido, False caso contrário
 */
function validateAmount($value) {
    $value = str_replace(['R$', ' ', '.'], '', $value);
    $value = str_replace(',', '.', $value);
    $amount = floatval($value);
    return $amount > 0;
}

/**
 * Converte string para float (valor monetário)
 * 
 * @param string $value Valor em string
 * @return float Valor convertido
 */
function parseAmount($value) {
    $value = str_replace(['R$', ' ', '.'], '', $value);
    $value = str_replace(',', '.', $value);
    return floatval($value);
}

/**
 * Obtém operações financeiras do usuário
 * 
 * @param PDO $pdo Conexão com banco de dados
 * @param int $userId ID do usuário
 * @param string $startDate Data inicial (opcional)
 * @param string $endDate Data final (opcional)
 * @return array Array com operações
 */
function getUserOperations($pdo, $userId, $startDate = null, $endDate = null) {
    try {
        $sql = "SELECT o.*, 
                       t.name as tipo_nome, 
                       t.description as tipo_descricao,
                       c.name as categoria_nome,
                       c.description as categoria_descricao
                FROM financial_operations o
                INNER JOIN financial_types t ON o.financial_type_id = t.id
                INNER JOIN financial_categories c ON o.financial_category_id = c.id
                WHERE o.user_id = ?";
        
        $params = [$userId];
        
        if ($startDate) {
            $sql .= " AND DATE(o.moment) >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND DATE(o.moment) <= ?";
            $params[] = $endDate;
        }
        
        $sql .= " ORDER BY o.moment DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erro ao buscar operações: " . $e->getMessage());
        return [];
    }
}

/**
 * Calcula demonstrativo financeiro
 * 
 * @param PDO $pdo Conexão com banco de dados
 * @param int $userId ID do usuário
 * @param string $startDate Data inicial
 * @param string $endDate Data final
 * @return array Array com totais e saldo
 */
function calculateFinancialStatement($pdo, $userId, $startDate, $endDate) {
    try {
        // Busca receitas
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(o.value), 0) as total
            FROM financial_operations o
            INNER JOIN financial_types t ON o.financial_type_id = t.id
            WHERE o.user_id = ? 
            AND LOWER(t.name) = 'receita'
            AND DATE(o.moment) >= ?
            AND DATE(o.moment) <= ?
        ");
        $stmt->execute([$userId, $startDate, $endDate]);
        $receitas = $stmt->fetch()['total'] ?? 0;
        
        // Busca despesas
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(o.value), 0) as total
            FROM financial_operations o
            INNER JOIN financial_types t ON o.financial_type_id = t.id
            WHERE o.user_id = ? 
            AND LOWER(t.name) = 'despesa'
            AND DATE(o.moment) >= ?
            AND DATE(o.moment) <= ?
        ");
        $stmt->execute([$userId, $startDate, $endDate]);
        $despesas = $stmt->fetch()['total'] ?? 0;
        
        $saldo = $receitas - $despesas;
        
        return [
            'receitas' => floatval($receitas),
            'despesas' => floatval($despesas),
            'saldo' => floatval($saldo),
            'estado' => $saldo >= 0 ? 'lucro' : 'prejuizo'
        ];
    } catch (PDOException $e) {
        error_log("Erro ao calcular demonstrativo: " . $e->getMessage());
        return [
            'receitas' => 0,
            'despesas' => 0,
            'saldo' => 0,
            'estado' => 'prejuizo'
        ];
    }
}

/**
 * Obtém estatísticas rápidas do usuário (últimos 30 dias)
 * 
 * @param PDO $pdo Conexão com banco de dados
 * @param int $userId ID do usuário
 * @return array Array com estatísticas
 */
function getQuickStats($pdo, $userId) {
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 days'));
    return calculateFinancialStatement($pdo, $userId, $startDate, $endDate);
}

