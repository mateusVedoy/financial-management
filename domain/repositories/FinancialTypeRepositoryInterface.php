<?php

namespace Domain\repositories;

use Domain\entities\FinancialType;

/**
 * Interface para repositório de tipos financeiros
 */
interface FinancialTypeRepositoryInterface
{
    /**
     * Busca todos os tipos financeiros
     * 
     * @return FinancialType[]
     */
    public function findAll(): array;

    /**
     * Busca um tipo financeiro por ID
     */
    public function findById(int $id): ?FinancialType;
}

// Esse arquivo possui código gerado em colaboração com IA

