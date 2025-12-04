<?php
/**
 * Dashboard
 * 
 * Página principal do sistema após o login.
 */

require __DIR__ . '/autoload.php';
require __DIR__ . '/display_errors.php';
require __DIR__ . '/check_auth.php';

use Interface\Bootstrap;
use Interface\controllers\DashboardController;
use Shared\helpers\ViewHelper;

$authService = Bootstrap::getAuthService();
$user_email = htmlspecialchars($authService->getUserEmail() ?? '');

// Obtém estatísticas rápidas (últimos 30 dias)
$dashboardController = new DashboardController();
$stats = $dashboardController->getQuickStats();

// Inclui a view
require __DIR__ . '/interface/views/financial/dashboard.php';
