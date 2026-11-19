<?php
/**
 * Painel Administrativo de Filmes - CineKids
 * CRUD com registro obrigatório de auditoria e exclusão via MODAL customizado
 */
$tituloPagina = "Gerenciamento de Filmes";
require_once __DIR__ . '/includes/header.php';

exigir_admin('login.php');

// Verificar se é edição
$filmeEditar = null;
$editarId = filter_input(INPUT_GET, 'editar', FILTER_VALIDATE_INT);
if ($editarId) {
    $stmtEdit = $pdo->prepare("SELECT * FROM filmes WHERE id = :id LIMIT 1");
    $stmtEdit->execute([':id' => $editarId]);
    $filmeEditar = $stmtEdit->fetch();
}

// Buscar todos os filmes para a lista
$stmtTodos = $pdo->query("SELECT * FROM filmes ORDER BY id DESC");
$todosFilmes = $stmtTodos->fetchAll();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- CABEÇALHO DO PAINEL ADMIN -->
    <div class="bg-white/90 p-6 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-10 h-10 rounded-2xl bg-pastel-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-film"></i>
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-pastel-blue-900">
                    Gerenciamento do Catálogo CineKids
                </h1>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-pastel-blue-700 mt-1">
                Cadastre, edite ou remova filmes. Todas as alterações geram registros de auditoria automáticos.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="historico.php" class="px-4 py-2 rounded-full font-black text-xs bg-pastel-yellow-200 hover:bg-pastel-yellow-300 text-pastel-blue-900 border border-pastel-yellow-400 shadow-sm flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-clock-rotate-left"></i> Ver Auditoria
            </a>
            <a href="#form-filme" class="px-4 py-2 rounded-full font-black text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-plus"></i> Novo Filme
            </a>
        </div>
    </div>

    <!-- FORMULÁRIO DE CADASTRO / EDIÇÃO -->
    <div id="form-filme" class="bg-white rounded-3xl border-2 border-pastel-yellow-300 shadow-md p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-pastel-yellow-200 pb-4">
            <div>
                <h2 class="text-xl font-black text-pastel-blue-900">
                    <?= $filmeEditar ? '✏️ Editar Filme: ' . htmlspecialchars($filmeEditar['titulo']) : '✨ Cadastrar Novo Filme Infantil' ?>
                </h2>
                <p class="text-xs font-semibold text-pastel-blue-600">
                    Preencha as informações do filme. Somente classificações indicativas infantis/juvenis são aceitas (Livre, 10 ou 12 anos).
                </p>
            </div>

            <?php if ($filmeEditar): ?>
                <a href="admin_filmes.php" class="px-3.5 py-1.5 rounded-full text-xs font-black bg-gray-100 hover:bg-gray-200 text-gray-700">
                    Cancelar Edição
                </a>
            <?php endif; ?>
        </div>

        <form action="actions/salvar_filme.php" method="POST" class="space-y-4">
            <?php if ($filmeEditar): ?>
                <input type="hidden" name="id" value="<?= $filmeEditar['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                
                <!-- Título -->
                <div class="space-y-1 sm:col-span-2">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Título do Filme *</label>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($filmeEditar['titulo'] ?? '') ?>" required placeholder="Ex: Divertida Mente 2" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Título Original -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Título Original</label>
                    <input type="text" name="titulo_original" value="<?= htmlspecialchars($filmeEditar['titulo_original'] ?? '') ?>" placeholder="Ex: Inside Out 2" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Ano -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Ano de Lançamento *</label>
                    <input type="number" name="ano" value="<?= htmlspecialchars($filmeEditar['ano'] ?? date('Y')) ?>" min="1930" max="2030" required class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Duração -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Duração (Ex: 1h 35m) *</label>
                    <input type="text" name="duracao" value="<?= htmlspecialchars($filmeEditar['duracao'] ?? '1h 30m') ?>" required placeholder="1h 35m" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Classificação Indicativa (Regra: sem filmes +14) -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900 flex items-center justify-between">
                        <span>Classificação Indicativa *</span>
                        <span class="text-[10px] text-pastel-pink-600 font-bold">Proibido +14</span>
                    </label>
                    <select name="classificacao" required class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-white">
                        <option value="L" <?= ($filmeEditar['classificacao'] ?? '') === 'L' ? 'selected' : '' ?>>Livre para Todos os Públicos (L)</option>
                        <option value="10" <?= ($filmeEditar['classificacao'] ?? '') === '10' ? 'selected' : '' ?>>Não recomendado para menores de 10 anos (10)</option>
                        <option value="12" <?= ($filmeEditar['classificacao'] ?? '') === '12' ? 'selected' : '' ?>>Não recomendado para menores de 12 anos (12)</option>
                    </select>
                </div>

                <!-- Categoria -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Categoria / Estúdio *</label>
                    <select name="categoria" required class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-white">
                        <option value="Disney & Pixar" <?= ($filmeEditar['categoria'] ?? '') === 'Disney & Pixar' ? 'selected' : '' ?>>Disney & Pixar</option>
                        <option value="Aventura & Animação" <?= ($filmeEditar['categoria'] ?? '') === 'Aventura & Animação' ? 'selected' : '' ?>>Aventura & Animação</option>
                        <option value="Comédia & Diversão" <?= ($filmeEditar['categoria'] ?? '') === 'Comédia & Diversão' ? 'selected' : '' ?>>Comédia & Diversão</option>
                        <option value="Clássicos Infantis" <?= ($filmeEditar['categoria'] ?? '') === 'Clássicos Infantis' ? 'selected' : '' ?>>Clássicos Infantis</option>
                    </select>
                </div>

                <!-- Nota IMDb -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Nota IMDb (0.0 a 10.0)</label>
                    <input type="number" step="0.1" min="1.0" max="10.0" name="nota_imdb" value="<?= htmlspecialchars($filmeEditar['nota_imdb'] ?? '8.0') ?>" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Gêneros -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Gêneros (separados por vírgula)</label>
                    <input type="text" name="generos" value="<?= htmlspecialchars($filmeEditar['generos'] ?? 'Animação, Aventura, Família') ?>" placeholder="Animação, Comédia, Família" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- URL do Pôster -->
                <div class="space-y-1 sm:col-span-2">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">URL do Pôster (Imagem Vertical) *</label>
                    <input type="url" name="poster_url" value="<?= htmlspecialchars($filmeEditar['poster_url'] ?? '') ?>" required placeholder="https://..." class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- URL do Banner -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">URL do Banner (Horizontal)</label>
                    <input type="url" name="banner_url" value="<?= htmlspecialchars($filmeEditar['banner_url'] ?? '') ?>" placeholder="https://..." class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Trailer YouTube ID ou URL -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Trailer do YouTube (ID ou Link) *</label>
                    <input type="text" name="trailer_url" value="<?= htmlspecialchars($filmeEditar['trailer_url'] ?? '') ?>" required placeholder="Ex: L4DrolmDxmw ou link completo" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Diretor -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Direção</label>
                    <input type="text" name="diretor" value="<?= htmlspecialchars($filmeEditar['diretor'] ?? '') ?>" placeholder="Ex: Kelsey Mann" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

                <!-- Elenco -->
                <div class="space-y-1">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Elenco / Dublagem</label>
                    <input type="text" name="elenco" value="<?= htmlspecialchars($filmeEditar['elenco'] ?? '') ?>" placeholder="Ex: Amy Poehler, Maya Hawke" class="w-full px-4 py-2.5 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50">
                </div>

            </div>

            <!-- Sinopse -->
            <div class="space-y-1">
                <label class="text-xs font-black uppercase text-pastel-blue-900">Sinopse do Filme *</label>
                <textarea name="sinopse" rows="3" required placeholder="Digite a história e descrição do filme..." class="w-full p-4 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50"><?= htmlspecialchars($filmeEditar['sinopse'] ?? '') ?></textarea>
            </div>

            <!-- Destaque no Banner Principal -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="destaque" name="destaque" value="1" <?= (!empty($filmeEditar['destaque'])) ? 'checked' : '' ?> class="w-4 h-4 text-pastel-pink-500 rounded border-gray-300 focus:ring-pastel-pink-400">
                <label for="destaque" class="text-xs font-bold text-pastel-blue-900 cursor-pointer">
                    Colocar em destaque no Banner Principal da Página Inicial (Hero Banner)
                </label>
            </div>

            <!-- Botão Salvar -->
            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="submit" class="px-8 py-3 rounded-full font-black text-sm bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow-md hover:shadow-lg transition-all">
                    <?= $filmeEditar ? 'Salvar Alterações' : 'Cadastrar Filme' ?>
                </button>
            </div>
        </form>
    </div>

    <!-- LISTA DE FILMES CADASTRADOS -->
    <div class="bg-white rounded-3xl border-2 border-pastel-yellow-300 shadow-md p-6 sm:p-8 space-y-4">
        <div class="flex items-center justify-between border-b border-pastel-yellow-200 pb-3">
            <h3 class="text-lg font-black text-pastel-blue-900">
                Filmes Atuais no Catálogo (<?= count($todosFilmes) ?>)
            </h3>
            <span class="text-xs font-bold text-pastel-blue-600">
                Ações de exclusão exigem confirmação em Modal
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-pastel-yellow-100 text-[11px] font-black uppercase text-pastel-blue-900 border-b border-pastel-yellow-300">
                        <th class="py-3 px-3">Filme</th>
                        <th class="py-3 px-3">Classificação</th>
                        <th class="py-3 px-3">Categoria</th>
                        <th class="py-3 px-3">Ano / Duração</th>
                        <th class="py-3 px-3">Nota IMDb</th>
                        <th class="py-3 px-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pastel-yellow-100 text-xs">
                    <?php foreach ($todosFilmes as $f): ?>
                    <tr class="hover:bg-pastel-yellow-50/60 transition-colors">
                        <td class="py-2.5 px-3">
                            <div class="flex items-center gap-3">
                                <img src="<?= htmlspecialchars($f['poster_url']) ?>" alt="poster" class="w-10 h-14 object-cover rounded-lg shadow-sm border border-pastel-yellow-200">
                                <div>
                                    <p class="font-black text-pastel-blue-900"><?= htmlspecialchars($f['titulo']) ?></p>
                                    <p class="text-[10px] text-pastel-blue-500 italic"><?= htmlspecialchars($f['titulo_original'] ?? '') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-2.5 px-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black badge-classificacao-<?= $f['classificacao'] ?>">
                                <?= $f['classificacao'] === 'L' ? 'Livre' : $f['classificacao'] . ' Anos' ?>
                            </span>
                        </td>
                        <td class="py-2.5 px-3 font-semibold text-pastel-blue-800">
                            <?= htmlspecialchars($f['categoria']) ?>
                        </td>
                        <td class="py-2.5 px-3 font-semibold text-pastel-blue-800">
                            <?= $f['ano'] ?> • <?= htmlspecialchars($f['duracao']) ?>
                        </td>
                        <td class="py-2.5 px-3 font-black text-amber-600">
                            ⭐ <?= number_format($f['nota_imdb'], 1) ?>
                        </td>
                        <td class="py-2.5 px-3 text-right space-x-1 whitespace-nowrap">
                            <a href="admin_filmes.php?editar=<?= $f['id'] ?>#form-filme" class="px-3 py-1.5 rounded-full font-bold text-xs bg-sky-100 text-sky-800 hover:bg-sky-200 transition-colors">
                                Editar
                            </a>
                            <button onclick="confirmarExclusaoFilme(<?= $f['id'] ?>, '<?= htmlspecialchars(addslashes($f['titulo']), ENT_QUOTES, 'UTF-8') ?>')" class="px-3 py-1.5 rounded-full font-bold text-xs bg-pink-100 text-pink-700 hover:bg-pink-200 transition-colors">
                                Excluir
                            </button>
                            <form id="form-del-<?= $f['id'] ?>" action="actions/excluir_filme.php" method="POST" class="hidden">
                                <input type="hidden" name="id" value="<?= $f['id'] ?>">
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    /**
     * Confirmação via MODAL (cumprindo requisito de não usar confirm() do navegador)
     */
    function confirmarExclusaoFilme(id, titulo) {
        CineModal.confirm(
            'Remover Filme do Catálogo?',
            `Você tem certeza que deseja excluir o filme "${titulo}"? Esta ação removerá o título e ficará registrada permanentemente na trilha de auditoria do sistema.`,
            () => {
                document.getElementById('form-del-' + id).submit();
            }
        );
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
