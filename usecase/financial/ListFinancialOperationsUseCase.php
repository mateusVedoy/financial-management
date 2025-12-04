<?php

namespace UseCase\financial;

use Domain\repositories\FinancialOperationRepositoryInterface;

/**
 * Use Case para listar operações financeiras de um usuário
 */
class ListFinancialOperationsUseCase
{
    private FinancialOperationRepositoryInterface $operationRepository;

    public function __construct(FinancialOperationRepositoryInterface $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    /**
     * Executa a listagem de operações financeiras
     * 
     * @param int $userId ID do usuário
     * @param string|null $startDate Data inicial (Y-m-d) ou null
     * @param string|null $endDate Data final (Y-m-d) ou null
     * @return array Array de operações financeiras
     */
    public function execute(int $userId, ?string $startDate = null, ?string $endDate = null): array
    {
        return $this->operationRepository->findByUserAndPeriod($userId, $startDate, $endDate);
    }
}

// Esse arquivo possui código gerado em colaboração com IA

