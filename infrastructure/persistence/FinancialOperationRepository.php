<?php

namespace Infrastructure\persistence;

use Domain\entities\FinancialOperation;
use Domain\repositories\FinancialOperationRepositoryInterface;
use Domain\value_objects\Money;
use Infrastructure\database\DatabaseConnection;
use PDO;

/**
 * Implementação do repositório de operações financeiras usando PDO
 */
class FinancialOperationRepository implements FinancialOperationRepositoryInterface
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->pdo = $dbConnection->getPdo();
    }

    public function findByUserAndPeriod(int $userId, ?string $startDate = null, ?string $endDate = null): array
    {
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
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        // Retorna arrays formatados para views (com dados agregados)
        // Use cases podem usar as entidades individuais se necessário
        return $rows;
    }
    
    /**
     * Busca operações como entidades (sem dados agregados)
     */
    public function findEntitiesByUserAndPeriod(int $userId, ?string $startDate = null, ?string $endDate = null): array
    {
        $sql = "SELECT o.* FROM financial_operations o WHERE o.user_id = ?";
        
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
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function save(FinancialOperation $operation): FinancialOperation
    {
        if ($operation->getId() === null) {
            // Insert
            $stmt = $this->pdo->prepare("
                INSERT INTO financial_operations 
                (value, financial_type_id, financial_category_id, moment, user_id) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $operation->getValue()->getAmount(),
                $operation->getFinancialTypeId(),
                $operation->getFinancialCategoryId(),
                $operation->getMoment()->format('Y-m-d H:i:s'),
                $operation->getUserId()
            ]);
            
            $id = (int)$this->pdo->lastInsertId();
            
            return new FinancialOperation(
                $operation->getValue(),
                $operation->getUserId(),
                $operation->getFinancialCategoryId(),
                $operation->getFinancialTypeId(),
                $operation->getMoment(),
                $id
            );
        } else {
            // Update (se necessário no futuro)
            $stmt = $this->pdo->prepare("
                UPDATE financial_operations 
                SET value = ?, financial_type_id = ?, financial_category_id = ?, moment = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $operation->getValue()->getAmount(),
                $operation->getFinancialTypeId(),
                $operation->getFinancialCategoryId(),
                $operation->getMoment()->format('Y-m-d H:i:s'),
                $operation->getId()
            ]);
            
            return $operation;
        }
    }

    public function calculateIncomeByUserAndPeriod(int $userId, string $startDate, string $endDate): float
    {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(o.value), 0) as total
            FROM financial_operations o
            INNER JOIN financial_types t ON o.financial_type_id = t.id
            WHERE o.user_id = ? 
            AND LOWER(t.name) = 'receita'
            AND DATE(o.moment) >= ?
            AND DATE(o.moment) <= ?
        ");
        $stmt->execute([$userId, $startDate, $endDate]);
        $result = $stmt->fetch();
        
        return floatval($result['total'] ?? 0);
    }

    public function calculateExpensesByUserAndPeriod(int $userId, string $startDate, string $endDate): float
    {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(o.value), 0) as total
            FROM financial_operations o
            INNER JOIN financial_types t ON o.financial_type_id = t.id
            WHERE o.user_id = ? 
            AND LOWER(t.name) = 'despesa'
            AND DATE(o.moment) >= ?
            AND DATE(o.moment) <= ?
        ");
        $stmt->execute([$userId, $startDate, $endDate]);
        $result = $stmt->fetch();
        
        return floatval($result['total'] ?? 0);
    }

    private function mapToEntity(array $row): FinancialOperation
    {
        $moment = \DateTime::createFromFormat('Y-m-d H:i:s', $row['moment']);
        if (!$moment) {
            $moment = new \DateTime($row['moment']);
        }

        return new FinancialOperation(
            new Money(floatval($row['value'])),
            (int)$row['user_id'],
            (int)$row['financial_category_id'],
            (int)$row['financial_type_id'],
            $moment,
            (int)$row['id']
        );
    }
}

// Esse arquivo possui código gerado em colaboração com IA

