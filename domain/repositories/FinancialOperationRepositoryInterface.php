<?php

namespace Domain\repositories;

use Domain\entities\FinancialOperation;

/**
 * Interface para repositório de operações financeiras
 */
interface FinancialOperationRepositoryInterface
{
    /**
     * Busca operações financeiras de um usuário em um período
     * 
     * @param int $userId ID do usuário
     * @param string|null $startDate Data inicial (Y-m-d) ou null
     * @param string|null $endDate Data final (Y-m-d) ou null
     * @return FinancialOperation[] Array de operações
     */
    public function findByUserAndPeriod(int $userId, ?string $startDate = null, ?string $endDate = null): array;

    /**
     * Salva uma operação financeira
     */
    public function save(FinancialOperation $operation): FinancialOperation;

    /**
     * Calcula total de receitas de um usuário em um período
     */
    public function calculateIncomeByUserAndPeriod(int $userId, string $startDate, string $endDate): float;

    /**
     * Calcula total de despesas de um usuário em um período
     */
    public function calculateExpensesByUserAndPeriod(int $userId, string $startDate, string $endDate): float;
}

// Esse arquivo possui código gerado em colaboração com IA

