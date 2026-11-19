<?php
/**
 * Página de Detalhes do Filme (Estilo IMDb Kids)
 * CineKids
 */
require_once __DIR__ . '/includes/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    definir_toast('Filme não encontrado!', 'warning');
    header('Location: index.php');
    exit;
}

// Buscar dados do filme
$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$filme = $stmt->fetch();

if (!$filme) {
    definir_toast('O filme solicitado não existe no catálogo!', 'error');
    header('Location: index.php');
    exit;
}

$tituloPagina = $filme['titulo'] . " - Detalhes do Filme";

// Verificar se o usuário já favoritou este filme
$jaFavorito = false;
if (esta_logado()) {
    $stmtFav = $pdo->prepare("SELECT id FROM favoritos WHERE filme_id = :filme_id AND usuario_id = :usuario_id LIMIT 1");
    $stmtFav->execute([':filme_id' => $id, ':usuario_id' => $usuarioAtual['id']]);
    $jaFavorito = (bool)$stmtFav->fetch();
}

// Buscar avaliações dos usuários para este filme
$stmtAval = $pdo->prepare("
    SELECT a.*, u.nome AS usuario_nome, u.avatar AS usuario_avatar 
    FROM avaliacoes a 
    JOIN usuarios u ON u.id = a.usuario_id 
    WHERE a.filme_id = :filme_id 
    ORDER BY a.criado_em DESC
");
$stmtAval->execute([':filme_id' => $id]);
$avaliacoes = $stmtAval->fetchAll();

// Filmes recomendados da mesma categoria
$stmtRecom = $pdo->prepare("SELECT * FROM filmes WHERE categoria = :categoria AND id != :id ORDER BY RAND() LIMIT 4");
$stmtRecom->execute([':categoria' => $filme['categoria'], ':id' => $id]);
$recomendados = $stmtRecom->fetchAll();
?>

<!-- TOPO ESTILO IMDB: CABEÇALHO DA FICHA TÉCNICA -->
<div class="bg-pastel-yellow-100 border-b-2 border-pastel-yellow-300 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <!-- Migalha de Pão -->
                <nav class="flex items-center gap-2 text-xs font-bold text-pastel-blue-600 mb-2">
                    <a href="index.php" class="hover:underline">Início</a>
                    <span>/</span>
                    <a href="pesquisa.php?categoria=<?= urlencode($filme['categoria']) ?>" class="hover:underline"><?= htmlspecialchars($filme['categoria']) ?></a>
                    <span>/</span>
                    <span class="text-pastel-blue-900"><?= htmlspecialchars($filme['titulo']) ?></span>
                </nav>

                <h1 class="text-3xl sm:text-4xl font-black text-pastel-blue-900 tracking-tight">
                    <?= htmlspecialchars($filme['titulo']) ?>
                </h1>

                <?php if (!empty($filme['titulo_original'])): ?>
                <p class="text-sm font-semibold text-pastel-blue-700 italic">
                    Título Original: <?= htmlspecialchars($filme['titulo_original']) ?>
                </p>
                <?php endif; ?>

                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs font-bold text-pastel-blue-800">
                    <span><?= $filme['ano'] ?></span>
                    <span>•</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-black badge-classificacao-<?= $filme['classificacao'] ?>">
                        <?= $filme['classificacao'] === 'L' ? 'Classificação Livre' : $filme['classificacao'] . ' Anos' ?>
                    </span>
                    <span>•</span>
                    <span><i class="fa-regular fa-clock mr-1"></i> <?= htmlspecialchars($filme['duracao']) ?></span>
                </div>
            </div>

            <!-- Placar de Avaliação IMDb & Kids -->
            <div class="flex items-center gap-4 bg-white/80 p-3 sm:p-4 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm flex-shrink-0">
                <!-- Nota IMDb -->
                <div class="text-center px-3 border-r border-pastel-yellow-200">
                    <p class="text-[10px] font-black text-pastel-blue-600 uppercase tracking-wider">Avaliação IMDb</p>
                    <div class="flex items-center justify-center gap-1.5 mt-0.5">
                        <span class="text-amber-500 text-xl">⭐</span>
                        <span class="text-2xl font-black text-pastel-blue-950"><?= number_format($filme['nota_imdb'], 1, ',', '.') ?></span>
                        <span class="text-xs text-pastel-blue-500 font-bold">/10</span>
                    </div>
                </div>

                <!-- Avaliação da Garotada CineKids -->
                <div class="text-center px-3">
                    <p class="text-[10px] font-black text-pastel-pink-600 uppercase tracking-wider">Votos da Criançada</p>
                    <div class="flex items-center justify-center gap-1 mt-0.5">
                        <span class="text-pastel-pink-500 text-xl">💖</span>
                        <span class="text-2xl font-black text-pastel-blue-950"><?= count($avaliacoes) ?></span>
                        <span class="text-xs text-pastel-blue-500 font-bold">opiniões</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CORPO PRINCIPAL ESTILO IMDB KIDS -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    <!-- ÁREA VISUAL PRINCIPAL: PÔSTER + TRAILER PLAYER IMDB -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Pôster do Filme e Ações -->
        <div class="lg:col-span-4 flex flex-col items-center sm:items-start space-y-4">
            <div class="w-full max-w-sm rounded-3xl overflow-hidden border-4 border-pastel-yellow-300 shadow-xl bg-white">
                <img src="<?= htmlspecialchars($filme['poster_url']) ?>" alt="<?= htmlspecialchars($filme['titulo']) ?>" class="w-full h-auto object-cover">
            </div>

            <!-- Botões de Ação Imediata -->
            <div class="w-full max-w-sm space-y-2.5">
                <!-- Botão Assistir (Requisito: botão para assistir) -->
                <a href="assistir.php?id=<?= $filme['id'] ?>" class="w-full flex items-center justify-center gap-3 py-4 rounded-full font-black text-lg text-white bg-gradient-to-r from-pastel-pink-500 via-rose-500 to-pastel-pink-600 hover:from-pastel-pink-600 hover:to-rose-700 shadow-pastel-pink hover:scale-102 transition-all">
                    <i class="fa-solid fa-play text-xl"></i> ASSISTIR FILME
                </a>

                <!-- Botão Favoritar -->
                <a href="actions/favoritar_action.php?filme_id=<?= $filme['id'] ?>" class="w-full flex items-center justify-center gap-2 py-3 rounded-full font-extrabold text-sm border-2 <?= $jaFavorito ? 'bg-pastel-pink-100 border-pastel-pink-400 text-pastel-pink-600' : 'bg-white border-pastel-yellow-300 text-pastel-blue-800 hover:bg-pastel-yellow-100' ?> transition-colors shadow-sm">
                    <i class="fa-solid fa-heart <?= $jaFavorito ? 'text-pastel-pink-500' : 'text-pastel-pink-400' ?>"></i>
                    <?= $jaFavorito ? 'Remover dos Meus Favoritos' : 'Salvar nos Meus Favoritos' ?>
                </a>
            </div>
        </div>

        <!-- Coluna de Informações e Trailer Embed -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Trailer Oficial Embed (Estilo IMDb Trailer Viewer) -->
            <div class="bg-black rounded-3xl overflow-hidden shadow-2xl border-4 border-pastel-yellow-300 aspect-video">
                <iframe 
                    class="w-full h-full" 
                    src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($filme['trailer_url']) ?>?rel=0&modestbranding=1" 
                    title="Trailer oficial de <?= htmlspecialchars($filme['titulo']) ?>" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>

            <!-- Gêneros em Tags -->
            <div class="flex flex-wrap gap-2">
                <?php foreach (explode(',', $filme['generos']) as $gen): ?>
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-white border border-pastel-pink-300 text-pastel-pink-600 shadow-sm">
                        <?= trim(htmlspecialchars($gen)) ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <!-- Breve Descrição / Sinopse (Requisito Central) -->
            <div class="bg-white/80 p-6 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm space-y-2">
                <h3 class="text-xs font-black uppercase tracking-wider text-pastel-pink-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-book-open"></i> Sinopse do Filme
                </h3>
                <p class="text-base font-semibold text-pastel-blue-950 leading-relaxed">
                    <?= nl2br(htmlspecialchars($filme['sinopse'])) ?>
                </p>
            </div>

            <!-- Ficha Técnica IMDb (Direção e Dublagem) -->
            <div class="bg-white/80 p-6 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm space-y-4">
                <div class="flex items-start gap-4 pb-3 border-b border-pastel-yellow-200">
                    <div class="w-8 h-8 rounded-full bg-pastel-yellow-200 text-pastel-blue-900 flex items-center justify-center font-black text-sm flex-shrink-0">
                        🎬
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase text-pastel-blue-600">Direção</h4>
                        <p class="text-sm font-bold text-pastel-blue-950"><?= htmlspecialchars($filme['diretor'] ?? 'Não informado') ?></p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-pastel-pink-100 text-pastel-pink-600 flex items-center justify-center font-black text-sm flex-shrink-0">
                        🎙️
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase text-pastel-blue-600">Elenco Principal & Dubladores</h4>
                        <p class="text-sm font-bold text-pastel-blue-950"><?= htmlspecialchars($filme['elenco'] ?? 'Vozes originais consagradas') ?></p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- SEÇÃO DE AVALIAÇÕES E COMENTÁRIOS DOS USUÁRIOS -->
    <section class="bg-white/80 p-6 sm:p-8 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-pastel-yellow-200 pb-4">
            <div>
                <h3 class="text-2xl font-black text-pastel-blue-900">
                    Avaliações da Comunidade Kids 🌟
                </h3>
                <p class="text-xs font-bold text-pastel-blue-700">
                    Veja o que a garotada e as famílias acharam deste filme!
                </p>
            </div>

            <?php if (esta_logado()): ?>
                <a href="#form-avaliar" class="px-5 py-2.5 rounded-full font-black text-xs bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow-sm flex items-center gap-1.5 self-start sm:self-auto">
                    <i class="fa-solid fa-star"></i> Avaliar este Filme
                </a>
            <?php endif; ?>
        </div>

        <!-- Formulário para Avaliar (Se logado) -->
        <?php if (esta_logado()): ?>
        <form id="form-avaliar" action="actions/avaliar_filme.php" method="POST" class="bg-pastel-yellow-50 p-5 rounded-2xl border-2 border-pastel-yellow-300 space-y-4">
            <input type="hidden" name="filme_id" value="<?= $filme['id'] ?>">
            
            <h4 class="text-sm font-black text-pastel-blue-900">O que você achou de <?= htmlspecialchars($filme['titulo']) ?>?</h4>
            
            <!-- Seleção de Estrelas -->
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-pastel-blue-800">Sua Nota:</span>
                <div class="flex gap-2 text-2xl cursor-pointer" id="star-rating">
                    <label class="hover:scale-125 transition-transform"><input type="radio" name="nota" value="1" class="hidden" required>⭐</label>
                    <label class="hover:scale-125 transition-transform"><input type="radio" name="nota" value="2" class="hidden">⭐</label>
                    <label class="hover:scale-125 transition-transform"><input type="radio" name="nota" value="3" class="hidden">⭐</label>
                    <label class="hover:scale-125 transition-transform"><input type="radio" name="nota" value="4" class="hidden">⭐</label>
                    <label class="hover:scale-125 transition-transform"><input type="radio" name="nota" value="5" class="hidden" checked>⭐</label>
                </div>
            </div>

            <!-- Comentário -->
            <div>
                <textarea name="comentario" rows="2" placeholder="Escreva um comentário amigável sobre o filme..." class="w-full p-3 rounded-xl border border-pastel-yellow-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-white"></textarea>
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-full font-black text-xs bg-pastel-blue-600 hover:bg-pastel-blue-700 text-white shadow-sm">
                Publicar Avaliação
            </button>
        </form>
        <?php else: ?>
        <div class="bg-pastel-yellow-100 p-4 rounded-2xl text-center border border-pastel-yellow-300">
            <p class="text-xs font-bold text-pastel-blue-800">
                Gostou deste filme? <a href="login.php" class="text-pastel-pink-600 underline font-black">Entre na sua conta</a> para deixar suas estrelinhas e avaliação!
            </p>
        </div>
        <?php endif; ?>

        <!-- Lista de Comentários -->
        <div class="space-y-3">
            <?php if (empty($avaliacoes)): ?>
                <p class="text-xs font-bold text-pastel-blue-600 italic py-4 text-center">
                    Seja o primeiro a avaliar este filme incrível! 🍿
                </p>
            <?php else: ?>
                <?php foreach ($avaliacoes as $av): ?>
                    <div class="p-4 rounded-2xl bg-white border border-pastel-yellow-200 shadow-sm flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-pastel-yellow-200 border-2 border-pastel-pink-300 flex items-center justify-center font-black text-sm text-pastel-blue-900 flex-shrink-0">
                            <?= strtoupper(substr($av['usuario_nome'], 0, 1)) ?>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-pastel-blue-900"><?= htmlspecialchars($av['usuario_nome']) ?></span>
                                <span class="text-[11px] font-bold text-amber-500">
                                    <?= str_repeat('⭐', $av['nota']) ?> (<?= $av['nota'] ?>/5)
                                </span>
                            </div>
                            <?php if (!empty($av['comentario'])): ?>
                                <p class="text-xs font-semibold text-pastel-blue-800 mt-1"><?= nl2br(htmlspecialchars($av['comentario'])) ?></p>
                            <?php endif; ?>
                            <span class="text-[10px] text-pastel-blue-400 font-bold block mt-1"><?= date('d/m/Y H:i', strtotime($av['criado_em'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </section>

    <!-- FILMES RECOMENDADOS -->
    <?php if (!empty($recomendados)): ?>
    <section class="space-y-4">
        <h3 class="text-2xl font-black text-pastel-blue-900">
            Você Também Vai Amar Assistir 🍿
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <?php foreach ($recomendados as $rec): ?>
                <a href="filme.php?id=<?= $rec['id'] ?>" class="group bg-white rounded-3xl overflow-hidden border-2 border-pastel-yellow-300 shadow-sm hover:shadow-md transition-all">
                    <div class="h-48 overflow-hidden">
                        <img src="<?= htmlspecialchars($rec['poster_url']) ?>" alt="<?= htmlspecialchars($rec['titulo']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-black text-pastel-blue-900 group-hover:text-pastel-pink-500 transition-colors truncate"><?= htmlspecialchars($rec['titulo']) ?></h4>
                        <div class="flex items-center justify-between text-[10px] font-bold text-pastel-blue-600 mt-1">
                            <span><?= $rec['ano'] ?></span>
                            <span class="text-amber-500">⭐ <?= number_format($rec['nota_imdb'], 1) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
