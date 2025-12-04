<?php

namespace Interface\controllers;

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

/**
 * Controller para operações financeiras
 */
class FinancialOperationController
{
    /**
     * Cria uma nova operação financeira
     */
    public function create(): void
    {
        $authService = Bootstrap::getAuthService();
        
        if (!$authService->isAuthenticated()) {
            ViewHelper::redirect('index.php?error=not_logged_in');
            return;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            ViewHelper::redirect('operation_create.php');
            return;
        }

        $userId = $authService->getUserId();
        $tipoId = intval($_POST['tipo_financeiro_id'] ?? 0);
        $categoriaId = intval($_POST['categoria_financeira_id'] ?? 0);
        $valor = $_POST['valor'] ?? '';
        $momento = $_POST['momento'] ?? date('Y-m-d H:i:s');

        $useCase = Bootstrap::getCreateFinancialOperationUseCase();
        $result = $useCase->execute($userId, $tipoId, $categoriaId, $valor, $momento);

        if ($result['success']) {
            ViewHelper::redirect('operation_list.php?message=created');
        } else {
            ViewHelper::redirect('operation_create.php?error=' . urlencode($result['error']));
        }
    }
}

// Esse arquivo possui código gerado em colaboração com IA

