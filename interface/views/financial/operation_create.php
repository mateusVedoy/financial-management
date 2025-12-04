<?php
/**
 * View de Criação de Operação Financeira
 * 
 * Variáveis esperadas:
 * @var array $tipos Array de tipos financeiros
 * @var array $categoriasPorTipo Array de categorias organizadas por tipo
 * @var string $message Mensagem de feedback
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Operação - Sistema de Gestão Financeira</title>
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
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Nova Operação Financeira</h2>
            <p class="text-gray-600 mb-6">Cadastre uma nova receita ou despesa</p>

            <?php echo $message ?? ''; ?>

            <form action="operation_create.php" method="POST" id="operationForm">
                <div class="form-group">
                    <label for="tipo_financeiro_id" class="form-label">Tipo *</label>
                    <select id="tipo_financeiro_id" name="tipo_financeiro_id" required class="form-input">
                        <option value="">Selecione o tipo</option>
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?php echo $tipo['id']; ?>">
                                <?php echo htmlspecialchars(ucfirst($tipo['name'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categoria_financeira_id" class="form-label">Categoria *</label>
                    <select id="categoria_financeira_id" name="categoria_financeira_id" required class="form-input">
                        <option value="">Primeiro selecione o tipo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="valor" class="form-label">Valor *</label>
                    <input type="text" id="valor" name="valor" required 
                           class="form-input" placeholder="0,00"
                           pattern="[0-9]+([,\.][0-9]{1,2})?">
                    <small class="text-gray-500">Use ponto ou vírgula como separador decimal</small>
                </div>

                <div class="form-group">
                    <label for="momento" class="form-label">Data e Hora</label>
                    <input type="datetime-local" id="momento" name="momento" 
                           class="form-input" 
                           value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-full">
                        Cadastrar Operação
                    </button>
                </div>
            </form>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <a href="operation_list.php" class="auth-link" style="display: inline-block;">
                    ← Voltar para lista de operações
                </a>
            </div>
        </div>
    </div>

    <script>
        // Atualiza categorias quando o tipo é selecionado
        document.getElementById('tipo_financeiro_id').addEventListener('change', function() {
            const tipoId = this.value;
            const categoriaSelect = document.getElementById('categoria_financeira_id');
            
            categoriaSelect.innerHTML = '<option value="">Selecione a categoria</option>';
            
            if (tipoId) {
                const categorias = <?php echo json_encode($categoriasPorTipo ?? []); ?>;
                if (categorias[tipoId]) {
                    categorias[tipoId].forEach(function(cat) {
                        const option = document.createElement('option');
                        option.value = cat.id;
                        option.textContent = cat.name;
                        categoriaSelect.appendChild(option);
                    });
                }
            }
        });

        // Formata valor monetário
        document.getElementById('valor').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                value = (parseInt(value) / 100).toFixed(2);
                value = value.replace('.', ',');
                e.target.value = value;
            }
        });
    </script>

</body>
</html>

