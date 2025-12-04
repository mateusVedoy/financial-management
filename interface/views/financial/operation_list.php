<?php
/**
 * View de Listagem de Operações Financeiras
 * 
 * Variáveis esperadas:
 * @var array $operacoes Array de operações
 * @var array $totais Array com receitas, despesas, saldo
 * @var string $start_date Data inicial
 * @var string $end_date Data final
 * @var string $message Mensagem de feedback
 */
use Shared\helpers\ViewHelper;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operações Financeiras - Sistema de Gestão Financeira</title>
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
        <!-- Filtros e ações -->
        <div class="card mb-6">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <h2 class="text-2xl font-bold text-gray-800">Operações Financeiras</h2>
                <a href="operation_create.php" class="btn btn-primary">
                    + Nova Operação
                </a>
            </div>

            <?php echo $message ?? ''; ?>

            <!-- Filtros de data -->
            <form method="GET" action="operation_list.php" style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
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
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
                <div class="form-group">
                    <a href="financial_statement.php?start_date=<?php echo urlencode($start_date); ?>&end_date=<?php echo urlencode($end_date); ?>" 
                       class="btn" style="background-color: #10b981; color: white;">
                        Ver Demonstrativo
                    </a>
                </div>
            </form>
        </div>

        <!-- Cards de resumo -->
        <div class="financial-stats mb-6">
            <div class="stat-card income">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">💰</div>
                <h3 style="font-weight: 600; color: #10b981; margin-bottom: 0.25rem;">Receitas</h3>
                <p style="font-size: 1.5rem; font-weight: 700; color: #10b981;">
                    <?php echo ViewHelper::formatCurrency($totais['receitas']); ?>
                </p>
            </div>
            <div class="stat-card expense">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">💸</div>
                <h3 style="font-weight: 600; color: #ef4444; margin-bottom: 0.25rem;">Despesas</h3>
                <p style="font-size: 1.5rem; font-weight: 700; color: #ef4444;">
                    <?php echo ViewHelper::formatCurrency($totais['despesas']); ?>
                </p>
            </div>
            <div class="stat-card" style="border-left-color: <?php echo $totais['saldo'] >= 0 ? '#10b981' : '#ef4444'; ?>;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📈</div>
                <h3 style="font-weight: 600; color: #3b82f6; margin-bottom: 0.25rem;">Saldo</h3>
                <p style="font-size: 1.5rem; font-weight: 700; color: <?php echo $totais['saldo'] >= 0 ? '#10b981' : '#ef4444'; ?>;">
                    <?php echo ViewHelper::formatCurrency($totais['saldo']); ?>
                </p>
            </div>
        </div>

        <!-- Lista de operações -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Lista de Operações</h3>
            
            <?php if (empty($operacoes)): ?>
                <p class="text-gray-600 text-center py-8">
                    Nenhuma operação encontrada no período selecionado.
                </p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
                                <th style="padding: 0.75rem; font-weight: 600; color: #374151;">Data/Hora</th>
                                <th style="padding: 0.75rem; font-weight: 600; color: #374151;">Tipo</th>
                                <th style="padding: 0.75rem; font-weight: 600; color: #374151;">Categoria</th>
                                <th style="padding: 0.75rem; font-weight: 600; color: #374151; text-align: right;">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($operacoes as $op): ?>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 0.75rem; color: #6b7280;">
                                        <?php echo date('d/m/Y H:i', strtotime($op['moment'])); ?>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <span style="padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 500;
                                            background-color: <?php echo strtolower($op['tipo_nome']) == 'receita' ? '#dcfce7' : '#fee2e2'; ?>;
                                            color: <?php echo strtolower($op['tipo_nome']) == 'receita' ? '#10b981' : '#ef4444'; ?>;">
                                            <?php echo htmlspecialchars(ucfirst($op['tipo_nome'])); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem; color: #374151;">
                                        <?php echo htmlspecialchars($op['categoria_nome']); ?>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: right; font-weight: 600;
                                        color: <?php echo strtolower($op['tipo_nome']) == 'receita' ? '#10b981' : '#ef4444'; ?>;">
                                        <?php echo strtolower($op['tipo_nome']) == 'receita' ? '+' : '-'; ?>
                                        <?php echo ViewHelper::formatCurrency($op['value']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

