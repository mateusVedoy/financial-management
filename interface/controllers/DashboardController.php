<?php

namespace Interface\controllers;

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

/**
 * Controller para dashboard
 */
class DashboardController
{
    /**
     * Obtém estatísticas rápidas (últimos 30 dias)
     */
    public function getQuickStats(): array
    {
        $authService = Bootstrap::getAuthService();
        
        if (!$authService->isAuthenticated()) {
            return [
                'receitas' => 0,
                'despesas' => 0,
                'saldo' => 0,
                'estado' => 'prejuizo'
            ];
        }

        $userId = $authService->getUserId();
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-30 days'));

        $useCase = Bootstrap::getCalculateFinancialStatementUseCase();
        return $useCase->execute($userId, $startDate, $endDate);
    }
}

// Esse arquivo possui código gerado em colaboração com IA

