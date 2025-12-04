<?php

namespace UseCase\financial;

use Domain\entities\FinancialOperation;
use Domain\repositories\FinancialCategoryRepositoryInterface;
use Domain\repositories\FinancialOperationRepositoryInterface;
use Domain\repositories\FinancialTypeRepositoryInterface;
use Domain\value_objects\Money;

/**
 * Use Case para criar uma nova operação financeira
 */
class CreateFinancialOperationUseCase
{
    private FinancialOperationRepositoryInterface $operationRepository;
    private FinancialTypeRepositoryInterface $typeRepository;
    private FinancialCategoryRepositoryInterface $categoryRepository;

    public function __construct(
        FinancialOperationRepositoryInterface $operationRepository,
        FinancialTypeRepositoryInterface $typeRepository,
        FinancialCategoryRepositoryInterface $categoryRepository
    ) {
        $this->operationRepository = $operationRepository;
        $this->typeRepository = $typeRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Executa a criação de uma operação financeira
     * 
     * @param int $userId ID do usuário
     * @param int $financialTypeId ID do tipo financeiro
     * @param int $financialCategoryId ID da categoria financeira
     * @param string $value Valor monetário em string
     * @param string|null $moment Data/hora da operação (Y-m-d H:i:s) ou null para agora
     * @return array ['success' => bool, 'operation' => FinancialOperation|null, 'error' => string|null]
     */
    public function execute(
        int $userId,
        int $financialTypeId,
        int $financialCategoryId,
        string $value,
        ?string $moment = null
    ): array {
        // Validações básicas
        if (empty($financialTypeId) || empty($financialCategoryId) || empty($value)) {
            return [
                'success' => false,
                'operation' => null,
                'error' => 'Por favor, preencha todos os campos.'
            ];
        }

        // Valida e converte o valor
        try {
            $money = Money::fromString($value);
            
            if ($money->getAmount() <= 0) {
                return [
                    'success' => false,
                    'operation' => null,
                    'error' => 'Valor inválido. O valor deve ser maior que zero.'
                ];
            }
        } catch (\InvalidArgumentException $e) {
            return [
                'success' => false,
                'operation' => null,
                'error' => 'Valor monetário inválido.'
            ];
        }

        // Verifica se o tipo existe
        $type = $this->typeRepository->findById($financialTypeId);
        if (!$type) {
            return [
                'success' => false,
                'operation' => null,
                'error' => 'Tipo financeiro inválido.'
            ];
        }

        // Verifica se a categoria existe
        $category = $this->categoryRepository->findById($financialCategoryId);
        if (!$category) {
            return [
                'success' => false,
                'operation' => null,
                'error' => 'Categoria financeira inválida.'
            ];
        }

        // Verifica se a categoria pertence ao tipo selecionado
        if ($category->getFinancialTypeId() !== $financialTypeId) {
            return [
                'success' => false,
                'operation' => null,
                'error' => 'Categoria inválida para o tipo selecionado.'
            ];
        }

        // Cria o objeto DateTime para o momento
        if ($moment) {
            try {
                $momentDate = new \DateTime($moment);
            } catch (\Exception $e) {
                $momentDate = new \DateTime();
            }
        } else {
            $momentDate = new \DateTime();
        }

        // Cria a operação
        $operation = new FinancialOperation(
            $money,
            $userId,
            $financialCategoryId,
            $financialTypeId,
            $momentDate
        );

        // Salva no repositório
        try {
            $operation = $this->operationRepository->save($operation);
            
            return [
                'success' => true,
                'operation' => $operation,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'operation' => null,
                'error' => 'Erro ao cadastrar operação: ' . $e->getMessage()
            ];
        }
    }
}

// Esse arquivo possui código gerado em colaboração com IA

