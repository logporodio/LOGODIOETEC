<?php
/**
 * Página de Exibição / Player do Filme (Estilo Netflix Kids)
 * CineKids
 */
require_once __DIR__ . '/includes/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    definir_toast('Filme inválido para reprodução!', 'warning');
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$filme = $stmt->fetch();

if (!$filme) {
    definir_toast('Filme não encontrado!', 'error');
    header('Location: index.php');
    exit;
}

$tituloPagina = "Assistindo: " . $filme['titulo'];

// Registrar log de auditoria da reprodução
$usuario = usuario_logado();
registrar_log(
    $pdo, 
    'ASSISTIR_FILME', 
    "Filme '{$filme['titulo']}' (ID #{$filme['id']}) iniciado no player por " . ($usuario ? "{$usuario['nome']}" : "Visitante"), 
    $usuario ? $usuario['id'] : null, 
    $usuario ? $usuario['nome'] : 'Visitante'
);

// Filmes sugeridos
$stmtSug = $pdo->prepare("SELECT * FROM filmes WHERE id != :id ORDER BY RAND() LIMIT 4");
$stmtSug->execute([':id' => $id]);
$sugestoes = $stmtSug->fetchAll();
?>

<!-- TOPO DO PLAYER NETFLIX KIDS -->
<div class="bg-pastel-blue-900 text-white py-4 px-4 sm:px-8 border-b-4 border-pastel-pink-400">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <a href="index.php" class="flex items-center gap-2 text-yellow-200 hover:text-white font-black text-sm bg-pastel-blue-800/80 px-4 py-2 rounded-full border border-pastel-blue-600 transition-all">
            <i class="fa-solid fa-arrow-left"></i> Voltar ao Catálogo
        </a>

        <div class="text-center hidden sm:block">
            <span class="text-xs font-bold text-pastel-pink-300 uppercase tracking-widest">Sessão CineKids</span>
            <h1 class="text-lg font-black text-white"><?= htmlspecialchars($filme['titulo']) ?></h1>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded text-xs font-black badge-classificacao-<?= $filme['classificacao'] ?>">
                <?= $filme['classificacao'] === 'L' ? 'Livre' : $filme['classificacao'] . ' Anos' ?>
            </span>
            <a href="filme.php?id=<?= $filme['id'] ?>" class="text-xs font-bold bg-white text-pastel-blue-900 hover:bg-pastel-yellow-200 px-3 py-1.5 rounded-full transition-colors">
                <i class="fa-solid fa-star text-amber-500 mr-1"></i> <?= number_format($filme['nota_imdb'], 1) ?> IMDb
            </a>
        </div>
    </div>
</div>

<!-- CONTAINER DO PLAYER DE VÍDEO -->
<div class="bg-pastel-blue-950 py-6 sm:py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Player Central 16:9 Estilo Cinema -->
        <div class="relative w-full aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl border-4 border-pastel-yellow-300">
            <iframe 
                class="w-full h-full" 
                src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($filme['trailer_url']) ?>?autoplay=1&rel=0&modestbranding=1" 
                title="<?= htmlspecialchars($filme['titulo']) ?>" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                allowfullscreen>
            </iframe>
        </div>

        <!-- Barra de Ações do Player -->
        <div class="mt-4 flex flex-wrap items-center justify-between gap-4 bg-pastel-blue-900/60 backdrop-blur-md p-4 rounded-2xl border border-pastel-blue-700 text-white">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-extrabold text-yellow-200">Reproduzindo em Alta Definição • Áudio Dublado</span>
            </div>

            <div class="flex items-center gap-2">
                <a href="actions/favoritar_action.php?filme_id=<?= $filme['id'] ?>" class="px-4 py-2 rounded-full font-bold text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white transition-all flex items-center gap-1.5 shadow">
                    <i class="fa-solid fa-heart"></i> Favoritar
                </a>
                <a href="filme.php?id=<?= $filme['id'] ?>" class="px-4 py-2 rounded-full font-bold text-xs bg-white text-pastel-blue-950 hover:bg-yellow-200 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info"></i> Ficha IMDb
                </a>
            </div>
        </div>

    </div>
</div>

<!-- DETALHES E SINOPSE ABAIXO DO PLAYER -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-8">
    
    <div class="bg-white/80 p-6 sm:p-8 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-pastel-yellow-200 pb-3">
            <div>
                <h2 class="text-2xl font-black text-pastel-blue-900"><?= htmlspecialchars($filme['titulo']) ?></h2>
                <p class="text-xs font-bold text-pastel-pink-500 uppercase tracking-wider"><?= htmlspecialchars($filme['categoria']) ?> • <?= $filme['ano'] ?> • <?= htmlspecialchars($filme['duracao']) ?></p>
            </div>
            <div class="flex items-center gap-1.5 bg-pastel-yellow-200 px-3 py-1.5 rounded-full text-xs font-black text-pastel-blue-900 self-start sm:self-auto">
                <span class="text-amber-600">⭐</span> <?= number_format($filme['nota_imdb'], 1) ?> no IMDb Kids
            </div>
        </div>

        <p class="text-sm sm:text-base font-medium text-pastel-blue-950 leading-relaxed">
            <?= nl2br(htmlspecialchars($filme['sinopse'])) ?>
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-pastel-blue-800 pt-2 border-t border-pastel-yellow-100">
            <div><strong class="text-pastel-blue-950">Gêneros:</strong> <?= htmlspecialchars($filme['generos']) ?></div>
            <div><strong class="text-pastel-blue-950">Direção:</strong> <?= htmlspecialchars($filme['diretor'] ?? 'Não especificado') ?></div>
            <div class="sm:col-span-2"><strong class="text-pastel-blue-950">Elenco / Dublagem:</strong> <?= htmlspecialchars($filme['elenco'] ?? 'Vozes consagradas') ?></div>
        </div>
    </div>

    <!-- SUGESTÕES ESTILO NETFLIX -->
    <div class="space-y-4">
        <h3 class="text-2xl font-black text-pastel-blue-900">
            Mais Filmes para Continuar Assistindo 🍿
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <?php foreach ($sugestoes as $sug): ?>
                <div class="movie-card bg-white rounded-3xl overflow-hidden border-2 border-pastel-yellow-300 shadow-sm flex flex-col justify-between">
                    <div class="h-44 overflow-hidden relative cursor-pointer" onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($sug), ENT_QUOTES, 'UTF-8') ?>)">
                        <img src="<?= htmlspecialchars($sug['poster_url']) ?>" alt="<?= htmlspecialchars($sug['titulo']) ?>" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black badge-classificacao-<?= $sug['classificacao'] ?>">
                            <?= $sug['classificacao'] ?>
                        </span>
                    </div>
                    <div class="p-3 bg-pastel-yellow-50/50">
                        <h4 class="text-xs font-black text-pastel-blue-900 truncate"><?= htmlspecialchars($sug['titulo']) ?></h4>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-amber-500">⭐ <?= number_format($sug['nota_imdb'], 1) ?></span>
                            <a href="assistir.php?id=<?= $sug['id'] ?>" class="px-3 py-1 rounded-full text-[11px] font-black bg-pastel-pink-500 text-white hover:bg-pastel-pink-600 transition-colors">
                                Assistir
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
