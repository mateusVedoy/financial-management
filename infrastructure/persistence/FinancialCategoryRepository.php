<?php

namespace Infrastructure\persistence;

use Domain\entities\FinancialCategory;
use Domain\repositories\FinancialCategoryRepositoryInterface;
use Infrastructure\database\DatabaseConnection;
use PDO;

/**
 * Implementação do repositório de categorias financeiras usando PDO
 */
class FinancialCategoryRepository implements FinancialCategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->pdo = $dbConnection->getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, description, financial_type_id FROM financial_categories ORDER BY financial_type_id, name");
        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function findByType(int $financialTypeId): array
    {
        $stmt = $this->pdo->prepare("SELECT id, name, description, financial_type_id FROM financial_categories WHERE financial_type_id = ? ORDER BY name");
        $stmt->execute([$financialTypeId]);
        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function findById(int $id): ?FinancialCategory
    {
        $stmt = $this->pdo->prepare("SELECT id, name, description, financial_type_id FROM financial_categories WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapToEntity($row);
    }

    private function mapToEntity(array $row): FinancialCategory
    {
        return new FinancialCategory(
            $row['name'],
            (int)$row['financial_type_id'],
            $row['description'],
            (int)$row['id']
        );
    }
}

// Esse arquivo possui código gerado em colaboração com IA

