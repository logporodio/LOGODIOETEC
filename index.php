<?php
/**
 * Página Inicial - CineKids
 * Catálogo no estilo Netflix + IMDb Kids
 * Cores: Amarelo Pastel (#FEF9C3) com contrastes Azul (#0284C7) e Rosa (#EC4899)
 */
$tituloPagina = "Início - O Cinema Mais Divertido para a Garotada";
require_once __DIR__ . '/includes/header.php';

// Buscar filme em destaque para o Hero Banner
$stmtHero = $pdo->query("SELECT * FROM filmes WHERE destaque = 1 ORDER BY RAND() LIMIT 1");
$filmeHero = $stmtHero->fetch();
if (!$filmeHero) {
    $stmtHero = $pdo->query("SELECT * FROM filmes ORDER BY nota_imdb DESC LIMIT 1");
    $filmeHero = $stmtHero->fetch();
}

// Categorias para as fileiras estilo Netflix
$categorias = [
    [
        'id'        => 'disney-pixar',
        'titulo'    => '🏰 Magia Disney & Pixar',
        'subtitulo' => 'As histórias mais encantadoras que conquistaram o mundo',
        'query'     => "SELECT * FROM filmes WHERE categoria = 'Disney & Pixar' ORDER BY ano DESC"
    ],
    [
        'id'        => 'top-imdb',
        'titulo'    => '⭐ Campeões de Votos (Top IMDb Kids)',
        'subtitulo' => 'Os filmes mais bem avaliados pelas crianças e pela crítica',
        'query'     => "SELECT * FROM filmes ORDER BY nota_imdb DESC LIMIT 8"
    ],
    [
        'id'        => 'aventura-animacao',
        'titulo'    => '🚀 Aventuras Eletrizantes',
        'subtitulo' => 'Dragões, heróis e universos fantásticos cheios de ação',
        'query'     => "SELECT * FROM filmes WHERE categoria = 'Aventura & Animação' ORDER BY ano DESC"
    ],
    [
        'id'        => 'classicos-infantis',
        'titulo'    => '👑 Clássicos Inesquecíveis',
        'subtitulo' => 'Obras-primas da animação que marcaram gerações',
        'query'     => "SELECT * FROM filmes WHERE categoria = 'Clássicos Infantis' ORDER BY nota_imdb DESC"
    ],
    [
        'id'        => 'comedia-diversao',
        'titulo'    => '😂 Risadas Garantidas',
        'subtitulo' => 'Filmes para morrer de rir com os personagens mais engraçados',
        'query'     => "SELECT * FROM filmes WHERE categoria = 'Comédia & Diversão' OR generos LIKE '%Comédia%' ORDER BY RAND() LIMIT 8"
    ]
];
?>

<!-- BANNER HERO ESTILO NETFLIX COM DETALHES IMDB -->
<?php if ($filmeHero): ?>
<section class="relative w-full min-h-[480px] sm:min-h-[560px] flex items-center bg-pastel-blue-900 overflow-hidden">
    <!-- Imagem de Fundo Hero com Overlay Pastel Suave -->
    <img src="<?= htmlspecialchars($filmeHero['banner_url']) ?>" alt="<?= htmlspecialchars($filmeHero['titulo']) ?>" class="absolute inset-0 w-full h-full object-cover object-center scale-105 filter brightness-90">
    <div class="absolute inset-0 bg-gradient-to-t from-pastel-yellow-50 via-pastel-blue-900/60 to-pastel-blue-900/40"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-pastel-blue-950/90 via-pastel-blue-900/50 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 z-10 w-full">
        <div class="max-w-2xl space-y-4">
            
            <!-- Badges de Categoria e Estrelas -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-pastel-pink-500 text-white shadow-md">
                    <?= htmlspecialchars($filmeHero['categoria']) ?>
                </span>
                
                <span class="px-2.5 py-0.5 rounded text-xs font-black badge-classificacao-<?= $filmeHero['classificacao'] ?>">
                    <?= $filmeHero['classificacao'] === 'L' ? 'Livre' : $filmeHero['classificacao'] . ' Anos' ?>
                </span>

                <div class="flex items-center gap-1.5 bg-pastel-yellow-300 px-3 py-0.5 rounded-full text-xs font-black text-pastel-blue-950 shadow-sm">
                    <span class="text-amber-600">⭐</span>
                    <span><?= number_format($filmeHero['nota_imdb'], 1, ',', '.') ?></span>
                    <span class="text-[10px] text-pastel-blue-800 font-bold">IMDb</span>
                </div>

                <span class="text-xs font-bold text-white/90">
                    <?= $filmeHero['ano'] ?> • <?= htmlspecialchars($filmeHero['duracao']) ?>
                </span>
            </div>

            <!-- Título do Filme -->
            <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight drop-shadow-lg leading-tight">
                <?= htmlspecialchars($filmeHero['titulo']) ?>
            </h1>

            <!-- Breve Sinopse -->
            <p class="text-sm sm:text-base text-yellow-50/90 font-medium leading-relaxed drop-shadow line-clamp-3 sm:line-clamp-4 max-w-xl">
                <?= htmlspecialchars($filmeHero['sinopse']) ?>
            </p>

            <!-- Botões de Ação Hero -->
            <div class="pt-4 flex flex-wrap items-center gap-3">
                <a href="assistir.php?id=<?= $filmeHero['id'] ?>" class="flex items-center gap-2.5 px-8 py-3.5 rounded-full font-black text-base text-white bg-gradient-to-r from-pastel-pink-500 to-rose-500 hover:from-pastel-pink-600 hover:to-rose-600 shadow-pastel-pink hover:scale-105 transition-all">
                    <i class="fa-solid fa-play text-lg"></i> Assistir Agora
                </a>

                <button onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filmeHero), ENT_QUOTES, 'UTF-8') ?>)" class="flex items-center gap-2 px-6 py-3.5 rounded-full font-extrabold text-sm text-pastel-blue-900 bg-pastel-yellow-200/90 hover:bg-pastel-yellow-300 backdrop-blur-md border-2 border-pastel-yellow-300 shadow-md hover:scale-105 transition-all">
                    <i class="fa-solid fa-circle-info text-pastel-blue-700 text-base"></i> Mais Informações
                </button>

                <a href="actions/favoritar_action.php?filme_id=<?= $filmeHero['id'] ?>" class="w-12 h-12 rounded-full bg-white/80 hover:bg-white text-pastel-pink-500 flex items-center justify-center text-lg shadow-md hover:scale-110 transition-all" title="Favoritar">
                    <i class="fa-solid fa-heart"></i>
                </a>
            </div>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- CARROSSÉIS ESTILO NETFLIX KIDS -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <?php foreach ($categorias as $cat): 
        $stmtCat = $pdo->query($cat['query']);
        $filmesCat = $stmtCat->fetchAll();
        if (empty($filmesCat)) continue;
    ?>
    <section class="space-y-3">
        <!-- Título da Categoria com Navegação -->
        <div class="flex items-end justify-between">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-pastel-blue-900 flex items-center gap-2">
                    <?= $cat['titulo'] ?>
                </h2>
                <p class="text-xs sm:text-sm font-semibold text-pastel-blue-700">
                    <?= $cat['subtitulo'] ?>
                </p>
            </div>

            <!-- Controles de Rolagem do Carrossel -->
            <div class="flex items-center gap-2">
                <button onclick="scrollCarousel('carousel-<?= $cat['id'] ?>', -1)" class="w-9 h-9 rounded-full bg-white hover:bg-pastel-yellow-200 text-pastel-blue-800 border-2 border-pastel-yellow-300 flex items-center justify-center shadow-sm hover:scale-105 transition-all" title="Anterior">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button onclick="scrollCarousel('carousel-<?= $cat['id'] ?>', 1)" class="w-9 h-9 rounded-full bg-white hover:bg-pastel-yellow-200 text-pastel-blue-800 border-2 border-pastel-yellow-300 flex items-center justify-center shadow-sm hover:scale-105 transition-all" title="Próximo">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Trilho do Carrossel de Filmes -->
        <div id="carousel-<?= $cat['id'] ?>" class="flex gap-4 sm:gap-6 overflow-x-auto no-scrollbar py-4 px-1 scroll-smooth">
            <?php foreach ($filmesCat as $filme): ?>
                <div class="movie-card flex-shrink-0 w-48 sm:w-56 bg-white rounded-3xl overflow-hidden border-2 border-pastel-yellow-200 shadow-md hover:shadow-xl hover:border-pastel-pink-300 flex flex-col group">
                    
                    <!-- Pôster do Filme (Clique abre modal com sinopse e botão assistir) -->
                    <div class="relative h-64 sm:h-72 w-full overflow-hidden cursor-pointer" onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filme), ENT_QUOTES, 'UTF-8') ?>)">
                        <img src="<?= htmlspecialchars($filme['poster_url']) ?>" alt="<?= htmlspecialchars($filme['titulo']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        
                        <!-- Badges Sobrepostos -->
                        <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black shadow badge-classificacao-<?= $filme['classificacao'] ?>">
                                <?= $filme['classificacao'] === 'L' ? 'Livre' : $filme['classificacao'] ?>
                            </span>
                        </div>

                        <div class="absolute top-2.5 right-2.5 bg-pastel-yellow-300 text-pastel-blue-950 font-black text-xs px-2 py-0.5 rounded-full shadow flex items-center gap-1">
                            <span class="text-amber-600 text-[10px]">⭐</span> <?= number_format($filme['nota_imdb'], 1, ',', '.') ?>
                        </div>

                        <!-- Botão Play Flutuante no Hover (Estilo Netflix) -->
                        <div class="absolute inset-0 bg-pastel-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-3 text-center">
                            <a href="assistir.php?id=<?= $filme['id'] ?>" class="w-12 h-12 rounded-full bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white flex items-center justify-center text-xl shadow-lg transform hover:scale-110 transition-all">
                                <i class="fa-solid fa-play ml-1"></i>
                            </a>
                            <span class="text-white text-xs font-black drop-shadow bg-black/40 px-3 py-1 rounded-full">
                                Ver Sinopse & Assistir
                            </span>
                        </div>
                    </div>

                    <!-- Informações do Card -->
                    <div class="p-3.5 flex flex-col flex-1 justify-between bg-pastel-yellow-50/50">
                        <div>
                            <h3 class="font-black text-sm text-pastel-blue-900 group-hover:text-pastel-pink-600 transition-colors line-clamp-1 cursor-pointer" onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filme), ENT_QUOTES, 'UTF-8') ?>)">
                                <?= htmlspecialchars($filme['titulo']) ?>
                            </h3>
                            <div class="flex items-center justify-between text-[11px] font-bold text-pastel-blue-700 mt-1">
                                <span><?= $filme['ano'] ?></span>
                                <span><?= htmlspecialchars($filme['duracao']) ?></span>
                            </div>
                        </div>

                        <!-- Ações Rápidas -->
                        <div class="mt-3 pt-2.5 border-t border-pastel-yellow-200 flex items-center justify-between">
                            <button onclick="abrirModalFilme(<?= htmlspecialchars(json_encode($filme), ENT_QUOTES, 'UTF-8') ?>)" class="text-xs font-black text-pastel-blue-600 hover:text-pastel-pink-500 flex items-center gap-1">
                                <i class="fa-regular fa-eye"></i> Detalhes
                            </button>
                            <a href="assistir.php?id=<?= $filme['id'] ?>" class="px-3 py-1 rounded-full text-xs font-black bg-pastel-pink-500 hover:bg-pastel-pink-600 text-white shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-play text-[10px]"></i> Assistir
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>

</div>

<!-- SEÇÃO DE RECOMENDAÇÃO SEGURA PARA PAIS E EDUCADORES -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-gradient-to-r from-pastel-yellow-200 via-pastel-pink-100 to-pastel-blue-200 rounded-3xl p-6 sm:p-10 border-4 border-pastel-yellow-300 shadow-md">
        <div class="flex flex-col md:flex-row items-center gap-6 justify-between">
            <div class="space-y-2 text-center md:text-left">
                <span class="px-3 py-1 rounded-full text-xs font-black bg-pastel-blue-600 text-white uppercase tracking-wider">
                    Segurança em Primeiro Lugar
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-pastel-blue-900">
                    O cinema onde as crianças se divertem e os pais ficam tranquilos 🧸
                </h3>
                <p class="text-sm font-semibold text-pastel-blue-800 max-w-2xl">
                    No CineKids, todos os títulos possuem rigorosa curadoria: classificação indicativa Livre, 10 ou 12 anos. Nenhum filme com classificação +14 ou conteúdo impróprio tem espaço por aqui.
                </p>
            </div>
            <a href="pesquisa.php" class="px-8 py-3.5 rounded-full font-black text-sm bg-pastel-blue-900 hover:bg-pastel-blue-800 text-white shadow-lg hover:scale-105 transition-all flex-shrink-0">
                Explorar Todo o Catálogo 🎈
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
