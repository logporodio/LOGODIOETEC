<?php
/**
 * Página de Perfil do Usuário - CineKids
 */
$tituloPagina = "Meu Perfil";
require_once __DIR__ . '/includes/header.php';

exigir_login('login.php');

// Buscar dados atualizados
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $usuarioAtual['id']]);
$user = $stmt->fetch();

// Contagens
$totalFavs = $pdo->prepare("SELECT COUNT(*) FROM favoritos WHERE usuario_id = :id");
$totalFavs->execute([':id' => $user['id']]);
$qtdFavs = $totalFavs->fetchColumn();

$totalAvals = $pdo->prepare("SELECT COUNT(*) FROM avaliacoes WHERE usuario_id = :id");
$totalAvals->execute([':id' => $user['id']]);
$qtdAvals = $totalAvals->fetchColumn();

// Logs recentes deste usuário
$stmtLogs = $pdo->prepare("SELECT * FROM logs_auditoria WHERE usuario_id = :id ORDER BY data_hora DESC LIMIT 5");
$stmtLogs->execute([':id' => $user['id']]);
$meusLogs = $stmtLogs->fetchAll();
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- CARD DO PERFIL -->
    <div class="bg-white rounded-3xl border-2 border-pastel-yellow-300 shadow-sm p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6">
        <div class="w-24 h-24 rounded-full bg-pastel-yellow-200 border-4 border-pastel-pink-400 flex items-center justify-center font-black text-4xl text-pastel-blue-900 shadow-md">
            <?= strtoupper(substr($user['nome'], 0, 1)) ?>
        </div>

        <div class="text-center sm:text-left flex-1 space-y-1">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <h1 class="text-2xl font-black text-pastel-blue-900"><?= htmlspecialchars($user['nome']) ?></h1>
                <span class="px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider <?= $user['tipo'] === 'admin' ? 'bg-pastel-blue-600 text-white' : 'bg-pastel-pink-500 text-white' ?> self-center sm:self-auto">
                    <?= $user['tipo'] === 'admin' ? 'Administrador' : 'Criança / Usuário' ?>
                </span>
            </div>
            <p class="text-xs font-bold text-pastel-blue-600"><?= htmlspecialchars($user['email']) ?></p>
            <p class="text-[11px] font-semibold text-pastel-blue-400">Membro desde: <?= date('d/m/Y', strtotime($user['criado_em'])) ?></p>
        </div>

        <!-- Estatísticas do Usuário -->
        <div class="flex items-center gap-4 bg-pastel-yellow-50 p-4 rounded-2xl border border-pastel-yellow-200">
            <div class="text-center px-3 border-r border-pastel-yellow-300">
                <span class="text-xl font-black text-pastel-pink-600"><?= $qtdFavs ?></span>
                <p class="text-[10px] font-bold text-pastel-blue-800 uppercase">Favoritos</p>
            </div>
            <div class="text-center px-3">
                <span class="text-xl font-black text-amber-500"><?= $qtdAvals ?></span>
                <p class="text-[10px] font-bold text-pastel-blue-800 uppercase">Avaliações</p>
            </div>
        </div>
    </div>

    <!-- FORMULÁRIO DE EDIÇÃO -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-3xl border-2 border-pastel-yellow-300 shadow-sm p-6 space-y-4">
            <h3 class="text-lg font-black text-pastel-blue-900 border-b border-pastel-yellow-200 pb-2">
                Editar Meus Dados
            </h3>

            <form action="actions/perfil_action.php" method="POST" class="space-y-3">
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Nome / Apelido</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <div class="pt-2 border-t border-pastel-yellow-100 space-y-3">
                    <p class="text-xs font-bold text-pastel-pink-600">Deseja alterar sua senha? (Opcional)</p>
                    
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-pastel-blue-800">Senha Atual</label>
                        <input type="password" name="senha_atual" placeholder="Digite sua senha atual" class="w-full px-4 py-2 rounded-2xl border border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 bg-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-pastel-blue-800">Nova Senha (min. 6 caracteres)</label>
                        <input type="password" name="nova_senha" placeholder="Digite a nova senha" class="w-full px-4 py-2 rounded-2xl border border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 bg-white">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-full font-black text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow transition-all">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        <!-- HISTÓRICO PESSOAL DE AUDITORIA -->
        <div class="bg-white rounded-3xl border-2 border-pastel-yellow-300 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-pastel-yellow-200 pb-2">
                <h3 class="text-lg font-black text-pastel-blue-900">
                    Minhas Atividades Recentes
                </h3>
                <a href="historico.php?busca=<?= urlencode($user['nome']) ?>" class="text-[11px] font-black text-pastel-blue-600 hover:text-pastel-pink-500">
                    Ver tudo
                </a>
            </div>

            <div class="space-y-2.5">
                <?php if (empty($meusLogs)): ?>
                    <p class="text-xs font-semibold text-pastel-blue-500 italic py-4 text-center">Nenhuma atividade registrada ainda.</p>
                <?php else: ?>
                    <?php foreach ($meusLogs as $l): ?>
                        <div class="p-3 rounded-2xl bg-pastel-yellow-50/80 border border-pastel-yellow-200 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-pastel-blue-900 text-[11px]"><?= htmlspecialchars($l['acao']) ?></span>
                                <span class="text-[10px] text-pastel-blue-500 font-bold"><?= date('d/m H:i', strtotime($l['data_hora'])) ?></span>
                            </div>
                            <p class="text-[11px] text-pastel-blue-800"><?= htmlspecialchars($l['detalhes']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
