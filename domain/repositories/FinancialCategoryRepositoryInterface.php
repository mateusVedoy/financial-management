<?php

namespace Domain\repositories;

use Domain\entities\FinancialCategory;

/**
 * Interface para repositório de categorias financeiras
 */
interface FinancialCategoryRepositoryInterface
{
    /**
     * Busca todas as categorias financeiras
     * 
     * @return FinancialCategory[]
     */
    public function findAll(): array;

    /**
     * Busca categorias por tipo financeiro
     * 
     * @param int $financialTypeId ID do tipo financeiro
     * @return FinancialCategory[]
     */
    public function findByType(int $financialTypeId): array;

    /**
     * Busca uma categoria por ID
     */
    public function findById(int $id): ?FinancialCategory;
}

// Esse arquivo possui código gerado em colaboração com IA

