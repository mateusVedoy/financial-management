<?php
/**
 * Demonstrativo Financeiro
 * 
 * Exibe o balanço financeiro do usuário em um período.
 */

require 'config.php';
require 'check_auth.php';
require 'includes/functions.php';
require 'includes/financial_functions.php';

$user_id = $_SESSION['user_id'];

// Filtros de data
$start_date = $_GET['start_date'] ?? date('Y-m-01'); // Primeiro dia do mês
$end_date = $_GET['end_date'] ?? date('Y-m-d'); // Hoje

// Calcula demonstrativo
$demonstrativo = calculateFinancialStatement($pdo, $user_id, $start_date, $end_date);

// Busca operações para detalhamento
$operacoes = getUserOperations($pdo, $user_id, $start_date, $end_date);

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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demonstrativo Financeiro - Sistema de Gestão Financeira</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="dashboard-page font-sans antialiased">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <h1 class="navbar-brand">Sistema de Gestão Financeira</h1>
            <div style="display: flex; gap: 0.5rem;">
                <a href="logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo da página -->
    <div class="main-container">
        <div class="card">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Demonstrativo Financeiro</h2>

            <!-- Filtros de data -->
            <form method="GET" action="financial_statement.php" style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label for="start_date" class="form-label">Data Inicial</label>
                    <input type="date" id="start_date" name="start_date" 
                           class="form-input" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label for="end_date" class="form-label">Data Final</label>
                    <input type="date" id="end_date" name="end_date" 
                           class="form-input" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>

            <!-- Resumo geral -->
            <div class="financial-stats mb-6">
                <div class="stat-card income">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💰</div>
                    <h3 style="font-weight: 600; color: #10b981; margin-bottom: 0.25rem;">Total de Receitas</h3>
                    <p style="font-size: 1.75rem; font-weight: 700; color: #10b981;">
                        <?php echo formatCurrency($demonstrativo['receitas']); ?>
                    </p>
                </div>
                <div class="stat-card expense">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💸</div>
                    <h3 style="font-weight: 600; color: #ef4444; margin-bottom: 0.25rem;">Total de Despesas</h3>
                    <p style="font-size: 1.75rem; font-weight: 700; color: #ef4444;">
                        <?php echo formatCurrency($demonstrativo['despesas']); ?>
                    </p>
                </div>
                <div class="stat-card" style="border-left-color: <?php echo $demonstrativo['saldo'] >= 0 ? '#10b981' : '#ef4444'; ?>;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $demonstrativo['saldo'] >= 0 ? '📈' : '📉'; ?></div>
                    <h3 style="font-weight: 600; color: #3b82f6; margin-bottom: 0.25rem;">Saldo Final</h3>
                    <p style="font-size: 1.75rem; font-weight: 700; color: <?php echo $demonstrativo['saldo'] >= 0 ? '#10b981' : '#ef4444'; ?>;">
                        <?php echo formatCurrency($demonstrativo['saldo']); ?>
                    </p>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                        Estado: <strong style="color: <?php echo $demonstrativo['saldo'] >= 0 ? '#10b981' : '#ef4444'; ?>;">
                            <?php echo $demonstrativo['estado'] == 'lucro' ? 'Lucro' : 'Prejuízo'; ?>
                        </strong>
                    </p>
                </div>
            </div>

            <!-- Detalhamento por categoria -->
            <div style="margin-top: 2rem;">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Detalhamento por Categoria</h3>
                
                <?php if (empty($porCategoria)): ?>
                    <p class="text-gray-600 text-center py-8">
                        Nenhuma operação encontrada no período selecionado.
                    </p>
                <?php else: ?>
                    <div style="display: grid; gap: 1rem;">
                        <?php 
                        // Separa receitas e despesas
                        $receitas_cat = [];
                        $despesas_cat = [];
                        foreach ($porCategoria as $cat) {
                            if (strtolower($cat['tipo']) == 'receita') {
                                $receitas_cat[] = $cat;
                            } else {
                                $despesas_cat[] = $cat;
                            }
                        }
                        ?>
                        
                        <?php if (!empty($receitas_cat)): ?>
                            <div class="card" style="border-left: 4px solid #10b981;">
                                <h4 style="font-weight: 600; color: #10b981; margin-bottom: 1rem;">💰 Receitas</h4>
                                <div style="display: grid; gap: 0.75rem;">
                                    <?php foreach ($receitas_cat as $cat): ?>
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background-color: #f0fdf4; border-radius: 0.5rem;">
                                            <div>
                                                <strong><?php echo htmlspecialchars($cat['nome']); ?></strong>
                                                <span style="color: #6b7280; font-size: 0.875rem; margin-left: 0.5rem;">
                                                    (<?php echo $cat['quantidade']; ?> operação<?php echo $cat['quantidade'] > 1 ? 'ões' : ''; ?>)
                                                </span>
                                            </div>
                                            <strong style="color: #10b981; font-size: 1.125rem;">
                                                <?php echo formatCurrency($cat['total']); ?>
                                            </strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($despesas_cat)): ?>
                            <div class="card" style="border-left: 4px solid #ef4444;">
                                <h4 style="font-weight: 600; color: #ef4444; margin-bottom: 1rem;">💸 Despesas</h4>
                                <div style="display: grid; gap: 0.75rem;">
                                    <?php foreach ($despesas_cat as $cat): ?>
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background-color: #fef2f2; border-radius: 0.5rem;">
                                            <div>
                                                <strong><?php echo htmlspecialchars($cat['nome']); ?></strong>
                                                <span style="color: #6b7280; font-size: 0.875rem; margin-left: 0.5rem;">
                                                    (<?php echo $cat['quantidade']; ?> operação<?php echo $cat['quantidade'] > 1 ? 'ões' : ''; ?>)
                                                </span>
                                            </div>
                                            <strong style="color: #ef4444; font-size: 1.125rem;">
                                                <?php echo formatCurrency($cat['total']); ?>
                                            </strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <a href="operation_list.php?start_date=<?php echo urlencode($start_date); ?>&end_date=<?php echo urlencode($end_date); ?>" 
                   class="auth-link">
                    ← Voltar para lista de operações
                </a>
            </div>
        </div>
    </div>

</body>
</html>

