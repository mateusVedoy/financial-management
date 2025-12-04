<?php
/**
 * Listagem de Operações Financeiras
 * 
 * Exibe todas as operações financeiras do usuário.
 */

require __DIR__ . '/autoload.php';
require __DIR__ . '/display_errors.php';
require __DIR__ . '/check_auth.php';

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

$authService = Bootstrap::getAuthService();
$userId = $authService->getUserId();
$message = ViewHelper::getSuccessMessage();

// Filtros
$start_date = $_GET['start_date'] ?? date('Y-m-01'); // Primeiro dia do mês
$end_date = $_GET['end_date'] ?? date('Y-m-d'); // Hoje

// Busca operações
$operationRepository = Bootstrap::getFinancialOperationRepository();
$operacoes = $operationRepository->findByUserAndPeriod($userId, $start_date, $end_date);

// Calcula totais
$statementUseCase = Bootstrap::getCalculateFinancialStatementUseCase();
$totais = $statementUseCase->execute($userId, $start_date, $end_date);

// Inclui a view
require __DIR__ . '/interface/views/financial/operation_list.php';
