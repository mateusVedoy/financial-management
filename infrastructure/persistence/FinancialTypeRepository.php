<?php

namespace Infrastructure\persistence;

use Domain\entities\FinancialType;
use Domain\repositories\FinancialTypeRepositoryInterface;
use Infrastructure\database\DatabaseConnection;
use PDO;

/**
 * Implementação do repositório de tipos financeiros usando PDO
 */
class FinancialTypeRepository implements FinancialTypeRepositoryInterface
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->pdo = $dbConnection->getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, description FROM financial_types ORDER BY name");
        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function findById(int $id): ?FinancialType
    {
        $stmt = $this->pdo->prepare("SELECT id, name, description FROM financial_types WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapToEntity($row);
    }

    private function mapToEntity(array $row): FinancialType
    {
        return new FinancialType(
            $row['name'],
            $row['description'],
            (int)$row['id']
        );
    }
}

// Esse arquivo possui código gerado em colaboração com IA

