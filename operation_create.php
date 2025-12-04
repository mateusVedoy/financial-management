<?php
/**
 * Cadastro de Operação Financeira
 * 
 * Permite cadastrar receitas e despesas.
 */

require __DIR__ . '/autoload.php';
require __DIR__ . '/display_errors.php';
require __DIR__ . '/check_auth.php';

use Interface\Bootstrap;
use Shared\helpers\ViewHelper;

// Busca tipos financeiros e categorias
$typeRepository = Bootstrap::getFinancialTypeRepository();
$categoryRepository = Bootstrap::getFinancialCategoryRepository();

$tipos = [];
foreach ($typeRepository->findAll() as $tipo) {
    $tipos[] = [
        'id' => $tipo->getId(),
        'name' => $tipo->getName(),
        'description' => $tipo->getDescription()
    ];
}

$categorias = [];
foreach ($categoryRepository->findAll() as $categoria) {
    $categorias[] = [
        'id' => $categoria->getId(),
        'name' => $categoria->getName(),
        'description' => $categoria->getDescription(),
        'financial_type_id' => $categoria->getFinancialTypeId()
    ];
}

// Organiza categorias por tipo
$categoriasPorTipo = [];
foreach ($categorias as $categoria) {
    $categoriasPorTipo[$categoria['financial_type_id']][] = $categoria;
}

// Processa formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller = new \Interface\controllers\FinancialOperationController();
    $controller->create();
    return; // O controller redireciona
}

// Se chegou aqui, mostra o formulário
$message = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $message = ViewHelper::showAlert($error, 'danger');
}

// Inclui a view
require __DIR__ . '/interface/views/financial/operation_create.php';
