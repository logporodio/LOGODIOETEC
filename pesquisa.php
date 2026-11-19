<?php
/**
 * Catálogo e Pesquisa de Filmes - CineKids
 * Filtros por Categoria, Classificação e Notas do IMDb
 */
$tituloPagina = "Catálogo de Filmes Infantis";
require_once __DIR__ . '/includes/header.php';

$termo = trim($_GET['q'] ?? '');
$categoria = trim($_GET['categoria'] ?? '');
$classificacao = trim($_GET['classificacao'] ?? '');
$ordem = trim($_GET['ordem'] ?? '');

$sql = "SELECT * FROM filmes WHERE 1=1";
$params = [];

if (!empty($termo)) {
    $sql .= " AND (titulo LIKE :termo OR titulo_original LIKE :termo OR sinopse LIKE :termo OR generos LIKE :termo)";
    $params[':termo'] = "%{$termo}%";
}

if (!empty($categoria)) {
    $sql .= " AND categoria = :categoria";
    $params[':categoria'] = $categoria;
}

if (!empty($classificacao)) {
    $sql .= " AND classificacao = :classificacao";
    $params[':classificacao'] = $classificacao;
}

// Ordenação
if ($ordem === 'nota') {
    $sql .= " ORDER BY nota_imdb DESC, ano DESC";
} elseif ($ordem === 'ano') {
    $sql .= " ORDER BY ano DESC, nota_imdb DESC";
} elseif ($ordem === 'nome') {
    $sql .= " ORDER BY titulo ASC";
} else {
    $sql .= " ORDER BY ano DESC, nota_imdb DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$filmes = $stmt->fetchAll();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- CABEÇALHO E FILTROS -->
    <div class="bg-white/80 p-6 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm space-y-4">
        <div>
            <h1 class="text-3xl font-black text-pastel-blue-900">
                Catálogo CineKids 🎬
            </h1>
            <p class="text-xs sm:text-sm font-semibold text-pastel-blue-700">
                Explore os melhores filmes de animação e aventura feitos para a garotada.
            </p>
        </div>

        <!-- FORMULÁRIO DE FILTRO RESPONSIVO -->
        <form action="pesquisa.php" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-2">
            
            <!-- Campo Busca -->
            <div class="lg:col-span-2">
                <input 
                    type="text" 
                    name="q" 
                    value="<?= htmlspecialchars($termo) ?>" 
                    placeholder="Nome do filme, personagem, estúdio..."
                    class="w-full px-4 py-2.5 rounded-full border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-white"
                >
            </div>

            <!-- Categoria -->
            <div>
                <select name="categoria" class="w-full px-4 py-2.5 rounded-full border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-white">
                    <option value="">Todas as Categorias</option>
                    <option value="Disney & Pixar" <?= $categoria === 'Disney & Pixar' ? 'selected' : '' ?>>Disney & Pixar</option>
                    <option value="Aventura & Animação" <?= $categoria === 'Aventura & Animação' ? 'selected' : '' ?>>Aventura & Animação</option>
                    <option value="Comédia & Diversão" <?= $categoria === 'Comédia & Diversão' ? 'selected' : '' ?>>Comédia & Diversão</option>
                    <option value="Clássicos Infantis" <?= $categoria === 'Clássicos Infantis' ? 'selected' : '' ?>>Clássicos Infantis</option>
                </select>
            </div>

            <!-- Classificação Indicativa -->
            <div>
                <select name="classificacao" class="w-full px-4 py-2.5 rounded-full border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-white">
                    <option value="">Todas as Idades</option>
                    <option value="L" <?= $classificacao === 'L' ? 'selected' : '' ?>>Livre para Todos</option>
                    <option value="10" <?= $classificacao === '10' ? 'selected' : '' ?>>10 Anos</option>
                    <option value="12" <?= $classificacao === '12' ? 'selected' : '' ?>>12 Anos</option>
                </select>
            </div>

            <!-- Ordenação -->
            <div>
                <select name="ordem" class="w-full px-4 py-2.5 rounded-full border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-white">
                    <option value="">Ordenar por</option>
                    <option value="nota" <?= $ordem === 'nota' ? 'selected' : '' ?>>Melhor Nota IMDb ⭐</option>
                    <option value="ano" <?= $ordem === 'ano' ? 'selected' : '' ?>>Mais Recentes</option>
                    <option value="nome" <?= $ordem === 'nome' ? 'selected' : '' ?>>Ordem Alfabética (A-Z)</option>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-5 flex items-center justify-end gap-2 pt-2">
                <?php if (!empty($termo) || !empty($categoria) || !empty($classificacao) || !empty($ordem)): ?>
                    <a href="pesquisa.php" class="px-4 py-2 rounded-full font-bold text-xs bg-gray-100 hover:bg-gray-200 text-pastel-blue-900 transition-colors">
                        Limpar Filtros
                    </a>
                <?php endif; ?>
                <button type="submit" class="px-6 py-2 rounded-full font-black text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow transition-all">
                    Filtrar Catálogo
                </button>
            </div>

        </form>
    </div>

    <!-- RESULTADO DOS FILMES -->
    <div class="space-y-4">
        <div class="flex items-center justify-between text-xs font-bold text-pastel-blue-700">
            <span>Encontrados <strong><?= count($filmes) ?></strong> filmes infantis</span>
        </div>

        <?php if (empty($filmes)): ?>
            <div class="bg-white/80 p-12 rounded-3xl border-2 border-pastel-yellow-300 text-center space-y-3">
                <span class="text-4xl">🍿</span>
                <h3 class="text-xl font-black text-pastel-blue-900">Nenhum filme encontrado com esses termos!</h3>
                <p class="text-xs font-semibold text-pastel-blue-600">Tente buscar por outro título, categoria ou limpar os filtros aplicados.</p>
                <a href="pesquisa.php" class="inline-block mt-2 px-6 py-2.5 rounded-full font-black text-xs bg-pastel-blue-600 text-white shadow">Ver Todo o Catálogo</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                <?php foreach ($filmes as $filme): ?>
                    <div class="movie-card bg-white rounded-3xl overflow-hidden border-2 border-pastel-yellow-200 shadow-md hover:shadow-xl hover:border-pastel-pink-300 flex flex-col group">
                        
                        <!-- Pôster do Filme com clique para Modal -->
                        <div class="relative h-60 sm:h-72 w-full overflow-hidden cursor-pointer" onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filme), ENT_QUOTES, 'UTF-8') ?>)">
                            <img src="<?= htmlspecialchars($filme['poster_url']) ?>" alt="<?= htmlspecialchars($filme['titulo']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            
                            <!-- Badges -->
                            <div class="absolute top-2.5 left-2.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black shadow badge-classificacao-<?= $filme['classificacao'] ?>">
                                    <?= $filme['classificacao'] === 'L' ? 'Livre' : $filme['classificacao'] ?>
                                </span>
                            </div>

                            <div class="absolute top-2.5 right-2.5 bg-pastel-yellow-300 text-pastel-blue-950 font-black text-xs px-2 py-0.5 rounded-full shadow flex items-center gap-1">
                                <span class="text-amber-600 text-[10px]">⭐</span> <?= number_format($filme['nota_imdb'], 1, ',', '.') ?>
                            </div>

                            <!-- Botão Flutuante Play -->
                            <div class="absolute inset-0 bg-pastel-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-3 text-center">
                                <a href="assistir.php?id=<?= $filme['id'] ?>" class="w-12 h-12 rounded-full bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white flex items-center justify-center text-xl shadow-lg transform hover:scale-110 transition-all">
                                    <i class="fa-solid fa-play ml-1"></i>
                                </a>
                                <span class="text-white text-xs font-black drop-shadow bg-black/40 px-3 py-1 rounded-full">
                                    Sinopse & Assistir
                                </span>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="p-3.5 flex flex-col flex-1 justify-between bg-pastel-yellow-50/50">
                            <div>
                                <h3 class="font-black text-xs sm:text-sm text-pastel-blue-900 group-hover:text-pastel-pink-600 transition-colors line-clamp-1 cursor-pointer" onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filme), ENT_QUOTES, 'UTF-8') ?>)">
                                    <?= htmlspecialchars($filme['titulo']) ?>
                                </h3>
                                <div class="flex items-center justify-between text-[11px] font-bold text-pastel-blue-700 mt-1">
                                    <span><?= $filme['ano'] ?></span>
                                    <span><?= htmlspecialchars($filme['duracao']) ?></span>
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="mt-3 pt-2.5 border-t border-pastel-yellow-200 flex items-center justify-between">
                                <button onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filme), ENT_QUOTES, 'UTF-8') ?>)" class="text-xs font-black text-pastel-blue-600 hover:text-pastel-pink-500">
                                    Ver Ficha
                                </button>
                                <a href="assistir.php?id=<?= $filme['id'] ?>" class="px-3 py-1 rounded-full text-xs font-black bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow-sm flex items-center gap-1">
                                    <i class="fa-solid fa-play text-[10px]"></i> Assistir
                                </a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
