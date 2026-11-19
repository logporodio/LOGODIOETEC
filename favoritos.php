<?php
/**
 * Meus Favoritos - CineKids
 */
$tituloPagina = "Meus Favoritos";
require_once __DIR__ . '/includes/header.php';

if (!esta_logado()) {
?>
<div class="max-w-4xl mx-auto px-4 py-16 text-center space-y-6">
    <div class="w-20 h-20 rounded-full bg-pastel-pink-200 text-pastel-pink-500 mx-auto flex items-center justify-center text-4xl shadow-md">
        💖
    </div>
    <h1 class="text-3xl font-black text-pastel-blue-900">
        Guarde Seus Filmes Favoritos Aqui!
    </h1>
    <p class="text-sm font-semibold text-pastel-blue-700 max-w-md mx-auto">
        Crie sua conta ou faça login para salvar os filmes mais divertidos e assistir quando quiser.
    </p>
    <div class="flex items-center justify-center gap-3">
        <a href="login.php" class="px-6 py-3 rounded-full font-black text-xs bg-pastel-blue-600 hover:bg-pastel-blue-700 text-white shadow">
            Entrar na Minha Conta
        </a>
        <a href="cadastro.php" class="px-6 py-3 rounded-full font-black text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow">
            Criar Conta Grátis
        </a>
    </div>
</div>
<?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Buscar favoritos do usuário
$stmt = $pdo->prepare("
    SELECT f.*, fav.criado_em as favoritado_em 
    FROM favoritos fav
    JOIN filmes f ON f.id = fav.filme_id
    WHERE fav.usuario_id = :usuario_id
    ORDER BY fav.criado_em DESC
");
$stmt->execute([':usuario_id' => $usuarioAtual['id']]);
$favoritos = $stmt->fetchAll();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <div class="bg-white/80 p-6 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-3xl">💖</span>
                <h1 class="text-3xl font-black text-pastel-blue-900">
                    Minha Lista de Favoritos
                </h1>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-pastel-blue-700 mt-1">
                Filmes especiais salvos com carinho por <?= htmlspecialchars($usuarioAtual['nome']) ?>!
            </p>
        </div>

        <span class="px-4 py-2 rounded-full font-black text-xs bg-pastel-pink-100 text-pastel-pink-600 border border-pastel-pink-300 self-start sm:self-auto">
            <?= count($favoritos) ?> <?= count($favoritos) === 1 ? 'filme guardado' : 'filmes guardados' ?>
        </span>
    </div>

    <?php if (empty($favoritos)): ?>
        <div class="bg-white/80 p-12 rounded-3xl border-2 border-pastel-yellow-300 text-center space-y-4">
            <span class="text-5xl">🍿</span>
            <h3 class="text-xl font-black text-pastel-blue-900">Sua lista de favoritos ainda está vazia!</h3>
            <p class="text-xs font-semibold text-pastel-blue-600 max-w-sm mx-auto">
                Navegue pelo catálogo e clique no coração dos filmes que você mais gostar para guardar aqui.
            </p>
            <a href="pesquisa.php" class="inline-block px-6 py-2.5 rounded-full font-black text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow">
                Explorar Filmes Divertidos
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
            <?php foreach ($favoritos as $filme): ?>
                <div class="movie-card bg-white rounded-3xl overflow-hidden border-2 border-pastel-yellow-200 shadow-md hover:shadow-xl flex flex-col justify-between group">
                    <div class="relative h-60 sm:h-72 w-full overflow-hidden cursor-pointer" onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filme), ENT_QUOTES, 'UTF-8') ?>)">
                        <img src="<?= htmlspecialchars($filme['poster_url']) ?>" alt="<?= htmlspecialchars($filme['titulo']) ?>" class="w-full h-full object-cover">
                        
                        <div class="absolute top-2.5 left-2.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black badge-classificacao-<?= $filme['classificacao'] ?>">
                                <?= $filme['classificacao'] ?>
                            </span>
                        </div>

                        <!-- Botão Remover dos Favoritos -->
                        <a href="actions/favoritar_action.php?filme_id=<?= $filme['id'] ?>" class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 hover:bg-pink-100 text-pink-600 flex items-center justify-center text-xs shadow-md" title="Remover dos Favoritos" onclick="event.stopPropagation()">
                            <i class="fa-solid fa-heart-crack"></i>
                        </a>
                    </div>

                    <div class="p-3.5 bg-pastel-yellow-50/50">
                        <h3 class="font-black text-xs sm:text-sm text-pastel-blue-900 truncate"><?= htmlspecialchars($filme['titulo']) ?></h3>
                        <div class="flex items-center justify-between text-[11px] font-bold text-pastel-blue-700 mt-1">
                            <span><?= $filme['ano'] ?></span>
                            <span class="text-amber-500">⭐ <?= number_format($filme['nota_imdb'], 1) ?></span>
                        </div>
                        
                        <div class="mt-3 pt-2 border-t border-pastel-yellow-200 flex items-center justify-between">
                            <a href="assistir.php?id=<?= $filme['id'] ?>" class="w-full text-center py-1.5 rounded-full text-xs font-black bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow-sm flex items-center justify-center gap-1">
                                <i class="fa-solid fa-play text-[10px]"></i> Assistir
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
