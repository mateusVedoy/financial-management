<?php

namespace UseCase\financial;

use Domain\repositories\FinancialOperationRepositoryInterface;
use Domain\value_objects\Money;

/**
 * Use Case para calcular demonstrativo financeiro
 */
class CalculateFinancialStatementUseCase
{
    private FinancialOperationRepositoryInterface $operationRepository;

    public function __construct(FinancialOperationRepositoryInterface $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    /**
     * Executa o cálculo do demonstrativo financeiro
     * 
     * @param int $userId ID do usuário
     * @param string $startDate Data inicial (Y-m-d)
     * @param string $endDate Data final (Y-m-d)
     * @return array ['receitas' => float, 'despesas' => float, 'saldo' => float, 'estado' => string]
     */
    public function execute(int $userId, string $startDate, string $endDate): array
    {
        $receitas = $this->operationRepository->calculateIncomeByUserAndPeriod($userId, $startDate, $endDate);
        $despesas = $this->operationRepository->calculateExpensesByUserAndPeriod($userId, $startDate, $endDate);
        
        $saldo = $receitas - $despesas;
        $estado = $saldo >= 0 ? 'lucro' : 'prejuizo';

        return [
            'receitas' => floatval($receitas),
            'despesas' => floatval($despesas),
            'saldo' => floatval($saldo),
            'estado' => $estado
        ];
    }
}

// Esse arquivo possui código gerado em colaboração com IA

