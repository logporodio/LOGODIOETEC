<?php
/**
 * Página de Históricos e Logs de Auditoria
 * CineKids - Conformidade total com o requisito de auditoria
 */
$tituloPagina = "Histórico & Auditoria do Sistema";
require_once __DIR__ . '/includes/header.php';

// Filtros
$filtroAcao = trim($_GET['acao'] ?? '');
$busca = trim($_GET['busca'] ?? '');

$sql = "SELECT * FROM logs_auditoria WHERE 1=1";
$params = [];

if (!empty($filtroAcao)) {
    $sql .= " AND acao = :acao";
    $params[':acao'] = $filtroAcao;
}

if (!empty($busca)) {
    $sql .= " AND (detalhes LIKE :busca OR usuario_nome LIKE :busca OR ip LIKE :busca)";
    $params[':busca'] = "%{$busca}%";
}

$sql .= " ORDER BY data_hora DESC LIMIT 100";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

// Estatísticas para os cards
$totalLogs = $pdo->query("SELECT COUNT(*) FROM logs_auditoria")->fetchColumn();
$totalUsuarios = $pdo->query("SELECT COUNT(DISTINCT usuario_id) FROM logs_auditoria WHERE usuario_id IS NOT NULL")->fetchColumn();
$totalAlteracoesCatalogo = $pdo->query("SELECT COUNT(*) FROM logs_auditoria WHERE acao IN ('INSERIR_FILME', 'EDITAR_FILME', 'EXCLUIR_FILME')")->fetchColumn();
$totalInteracoes = $pdo->query("SELECT COUNT(*) FROM logs_auditoria WHERE acao IN ('AVALIAR_FILME', 'FAVORITAR', 'DESFAVORITAR', 'ASSISTIR_FILME')")->fetchColumn();

// Lista de ações distintas para o filtro
$stmtAcoes = $pdo->query("SELECT DISTINCT acao FROM logs_auditoria ORDER BY acao ASC");
$todasAcoes = $stmtAcoes->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- CABEÇALHO DA PÁGINA -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/80 p-6 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-10 h-10 rounded-2xl bg-pastel-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-pastel-blue-900">
                    Trilha de Auditoria & Histórico
                </h1>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-pastel-blue-700 mt-1">
                Registro imutável de todas as alterações e ações realizadas no CineKids para auditorias futuras.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sistema Ativo
            </span>
        </div>
    </div>

    <!-- CARDS DE MÉTRICAS / RESUMO DE AUDITORIA -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-pastel-yellow-100 p-5 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm">
            <p class="text-[11px] font-black uppercase text-pastel-blue-700 tracking-wider">Total de Registros</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-pastel-blue-950"><?= $totalLogs ?></span>
                <span class="text-xl">📋</span>
            </div>
            <span class="text-[10px] font-bold text-pastel-blue-600 mt-1 block">Eventos rastreados</span>
        </div>

        <div class="bg-pastel-pink-100 p-5 rounded-3xl border-2 border-pastel-pink-200 shadow-sm">
            <p class="text-[11px] font-black uppercase text-pastel-pink-700 tracking-wider">Usuários Ativos</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-pastel-pink-900"><?= $totalUsuarios ?></span>
                <span class="text-xl">👥</span>
            </div>
            <span class="text-[10px] font-bold text-pastel-pink-600 mt-1 block">Com ações registradas</span>
        </div>

        <div class="bg-pastel-blue-100 p-5 rounded-3xl border-2 border-pastel-blue-200 shadow-sm">
            <p class="text-[11px] font-black uppercase text-pastel-blue-700 tracking-wider">Alterações de Catálogo</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-pastel-blue-900"><?= $totalAlteracoesCatalogo ?></span>
                <span class="text-xl">🎬</span>
            </div>
            <span class="text-[10px] font-bold text-pastel-blue-600 mt-1 block">Filmes criados/editados</span>
        </div>

        <div class="bg-amber-100 p-5 rounded-3xl border-2 border-amber-200 shadow-sm">
            <p class="text-[11px] font-black uppercase text-amber-800 tracking-wider">Interações Infantis</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-amber-950"><?= $totalInteracoes ?></span>
                <span class="text-xl">🍿</span>
            </div>
            <span class="text-[10px] font-bold text-amber-700 mt-1 block">Visualizações & Avaliações</span>
        </div>

    </div>

    <!-- FORMULÁRIO DE FILTRO DE AUDITORIA -->
    <div class="bg-white/90 p-5 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm">
        <form action="historico.php" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            
            <div class="flex-1 w-full">
                <input 
                    type="text" 
                    name="busca" 
                    placeholder="Pesquisar por usuário, IP ou palavras nos detalhes..." 
                    value="<?= htmlspecialchars($busca) ?>"
                    class="w-full px-4 py-2.5 rounded-full border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-400 bg-pastel-yellow-50/50"
                >
            </div>

            <div class="w-full sm:w-64">
                <select name="acao" class="w-full px-4 py-2.5 rounded-full border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-400 bg-white">
                    <option value="">Todas as Ações</option>
                    <?php foreach ($todasAcoes as $ac): ?>
                        <option value="<?= htmlspecialchars($ac) ?>" <?= $filtroAcao === $ac ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ac) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-full font-black text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow transition-all">
                Filtrar Logs
            </button>

            <?php if (!empty($filtroAcao) || !empty($busca)): ?>
                <a href="historico.php" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-full font-bold text-xs bg-gray-100 hover:bg-gray-200 text-pastel-blue-900 transition-colors">
                    Limpar
                </a>
            <?php endif; ?>

        </form>
    </div>

    <!-- TABELA / LISTA DE LOGS RESPONSIVA -->
    <div class="bg-white rounded-3xl border-2 border-pastel-yellow-300 shadow-md overflow-hidden">
        
        <?php if (empty($logs)): ?>
            <div class="p-12 text-center space-y-3">
                <span class="text-4xl">🔍</span>
                <h3 class="text-lg font-black text-pastel-blue-900">Nenhum registro encontrado</h3>
                <p class="text-xs font-semibold text-pastel-blue-600">Não há registros com os filtros informados.</p>
            </div>
        <?php else: ?>

            <!-- Visualização Desktop (Tabela) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-pastel-yellow-100/80 border-b-2 border-pastel-yellow-300 text-[11px] font-black uppercase text-pastel-blue-800 tracking-wider">
                            <th class="py-3.5 px-4">Data / Hora</th>
                            <th class="py-3.5 px-4">Usuário</th>
                            <th class="py-3.5 px-4">Ação</th>
                            <th class="py-3.5 px-4">Detalhes da Auditoria</th>
                            <th class="py-3.5 px-4 text-right">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pastel-yellow-100 text-xs">
                        <?php foreach ($logs as $log): 
                            // Cores de badge por tipo de ação
                            $badgeColor = 'bg-sky-100 text-sky-800 border-sky-200';
                            if (in_array($log['acao'], ['LOGIN', 'CADASTRO'])) {
                                $badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            } elseif (in_array($log['acao'], ['EXCLUIR_FILME', 'LOGIN_FALHOU'])) {
                                $badgeColor = 'bg-rose-100 text-rose-800 border-rose-200';
                            } elseif (in_array($log['acao'], ['INSERIR_FILME', 'EDITAR_FILME'])) {
                                $badgeColor = 'bg-amber-100 text-amber-800 border-amber-200';
                            } elseif (in_array($log['acao'], ['AVALIAR_FILME', 'FAVORITAR'])) {
                                $badgeColor = 'bg-pastel-pink-100 text-pastel-pink-600 border-pastel-pink-200';
                            }
                        ?>
                        <tr class="hover:bg-pastel-yellow-50/70 transition-colors">
                            <td class="py-3 px-4 font-bold text-pastel-blue-900 whitespace-nowrap">
                                <?= date('d/m/Y H:i:s', strtotime($log['data_hora'])) ?>
                            </td>
                            <td class="py-3 px-4 font-black text-pastel-blue-950 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-6 h-6 rounded-full bg-pastel-yellow-200 text-pastel-blue-900 flex items-center justify-center text-[10px] font-black">
                                        <?= strtoupper(substr($log['usuario_nome'] ?? 'V', 0, 1)) ?>
                                    </span>
                                    <?= htmlspecialchars($log['usuario_nome'] ?? 'Visitante') ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider <?= $badgeColor ?>">
                                    <?= htmlspecialchars($log['acao']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-pastel-blue-900">
                                <?= htmlspecialchars($log['detalhes']) ?>
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-[11px] text-pastel-blue-500 whitespace-nowrap">
                                <?= htmlspecialchars($log['ip'] ?? '127.0.0.1') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Visualização Mobile (Cards) -->
            <div class="block md:hidden divide-y divide-pastel-yellow-200">
                <?php foreach ($logs as $log): ?>
                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black text-pastel-blue-500">
                            <?= date('d/m/Y H:i:s', strtotime($log['data_hora'])) ?>
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-pastel-yellow-200 text-pastel-blue-900">
                            <?= htmlspecialchars($log['acao']) ?>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-pastel-yellow-300 text-pastel-blue-900 flex items-center justify-center text-[10px] font-black">
                            <?= strtoupper(substr($log['usuario_nome'] ?? 'V', 0, 1)) ?>
                        </span>
                        <span class="text-xs font-black text-pastel-blue-900"><?= htmlspecialchars($log['usuario_nome'] ?? 'Visitante') ?></span>
                    </div>
                    <p class="text-xs font-semibold text-pastel-blue-800 bg-pastel-yellow-50/80 p-2.5 rounded-xl border border-pastel-yellow-200">
                        <?= htmlspecialchars($log['detalhes']) ?>
                    </p>
                    <div class="text-right text-[10px] font-mono text-pastel-blue-400">
                        IP: <?= htmlspecialchars($log['ip'] ?? '127.0.0.1') ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
