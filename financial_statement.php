<?php
/**
 * Demonstrativo Financeiro
 * 
 * Exibe o balanço financeiro do usuário em um período.
 */

require __DIR__ . '/autoload.php';
require __DIR__ . '/display_errors.php';
require __DIR__ . '/check_auth.php';

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

$authService = Bootstrap::getAuthService();
$userId = $authService->getUserId();

// Filtros de data
$start_date = $_GET['start_date'] ?? date('Y-m-01'); // Primeiro dia do mês
$end_date = $_GET['end_date'] ?? date('Y-m-d'); // Hoje

// Calcula demonstrativo
$statementUseCase = Bootstrap::getCalculateFinancialStatementUseCase();
$demonstrativo = $statementUseCase->execute($userId, $start_date, $end_date);

// Busca operações para detalhamento
$operationRepository = Bootstrap::getFinancialOperationRepository();
$operacoes = $operationRepository->findByUserAndPeriod($userId, $start_date, $end_date);

// Agrupa por categoria
$porCategoria = [];
foreach ($operacoes as $op) {
    $key = $op['financial_category_id'];
    if (!isset($porCategoria[$key])) {
        $porCategoria[$key] = [
            'nome' => $op['categoria_nome'],
            'tipo' => $op['tipo_nome'],
            'total' => 0,
            'quantidade' => 0
        ];
    }
    $porCategoria[$key]['total'] += $op['value'];
    $porCategoria[$key]['quantidade']++;
}

// Inclui a view
require __DIR__ . '/interface/views/financial/financial_statement.php';
