<?php

namespace Interface\Helpers;

use Domain\entities\FinancialOperation;

/**
 * Helper para converter entidades em arrays para views
 */
class EntityToArrayConverter
{
    /**
     * Converte operação financeira em array para view
     */
    public static function operationToArray(FinancialOperation $operation, array $typeData = [], array $categoryData = []): array
    {
        return [
            'id' => $operation->getId(),
            'value' => $operation->getValue()->getAmount(),
            'moment' => $operation->getMoment()->format('Y-m-d H:i:s'),
            'user_id' => $operation->getUserId(),
            'financial_category_id' => $operation->getFinancialCategoryId(),
            'financial_type_id' => $operation->getFinancialTypeId(),
            'tipo_nome' => $typeData['name'] ?? '',
            'tipo_descricao' => $typeData['description'] ?? '',
            'categoria_nome' => $categoryData['name'] ?? '',
            'categoria_descricao' => $categoryData['description'] ?? ''
        ];
    }

    /**
     * Converte array de operações com dados adicionais em formato para view
     */
    public static function operationsToViewArray(array $operations): array
    {
        $result = [];
        foreach ($operations as $op) {
            if ($op instanceof FinancialOperation) {
                // Se for entidade, precisa buscar dados adicionais
                // Por enquanto retorna estrutura básica
                $result[] = [
                    'id' => $op->getId(),
                    'value' => $op->getValue()->getAmount(),
                    'moment' => $op->getMoment()->format('Y-m-d H:i:s'),
                    'tipo_nome' => '', // Será preenchido depois
                    'categoria_nome' => '' // Será preenchido depois
                ];
            } else {
                // Já é array (vindo do repositório que faz JOIN)
                $result[] = $op;
            }
        }
        return $result;
    }
}

// Esse arquivo possui código gerado em colaboração com IA

