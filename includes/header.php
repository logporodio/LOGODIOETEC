<?php
/**
 * Header Global - CineKids
 * Paleta: Amarelo Pastel (#FEF9C3) com contrastes Azul (#0284C7) e Rosa (#EC4899)
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/logs.php';

$usuarioAtual = usuario_logado();
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloPagina) ? htmlspecialchars($tituloPagina) . ' | CineKids' : 'CineKids - O Cinema Mais Divertido para a Garotada' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pastel-yellow': {
                            50: '#FFFDE7',
                            100: '#FEF9C3',
                            200: '#FEF08A',
                            300: '#FDE047',
                            400: '#FACC15'
                        },
                        'pastel-blue': {
                            100: '#E0F2FE',
                            200: '#BAE6FD',
                            300: '#7DD3FC',
                            400: '#38BDF8',
                            500: '#0EA5E9',
                            600: '#0284C7',
                            700: '#0369A1',
                            900: '#0C4A6E'
                        },
                        'pastel-pink': {
                            100: '#FCE7F3',
                            200: '#FBCFE8',
                            300: '#F472B6',
                            400: '#FB7185',
                            500: '#EC4899',
                            600: '#DB2777'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    
    <!-- Ícones Lucide / SVG Helpers -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-pastel-yellow-50 min-h-screen flex flex-col text-pastel-blue-900 selection:bg-pastel-pink-200 selection:text-pastel-pink-600">

    <!-- Barra Superior de Destaque / Aviso Amigável para Crianças -->
    <div class="bg-gradient-to-r from-pastel-yellow-200 via-pastel-pink-100 to-pastel-blue-200 text-xs py-1.5 px-4 text-center font-bold text-pastel-blue-900 border-b border-pastel-yellow-300">
        🍿 Bem-vindo ao CineKids! Filmes seguros, livres e divertidos para toda a família 🎈
    </div>

    <!-- Navbar Principal -->
    <header class="sticky top-0 z-40 bg-pastel-yellow-100/90 backdrop-blur-md border-b-2 border-pastel-yellow-300 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo CineKids -->
                <div class="flex items-center gap-3">
                    <a href="index.php" class="flex items-center gap-2.5 group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pastel-pink-400 to-pastel-pink-500 flex items-center justify-center text-white text-2xl shadow-md group-hover:rotate-6 transition-transform">
                            🎬
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl sm:text-3xl font-black tracking-tight text-pastel-blue-900 leading-none">
                                CINE<span class="text-pastel-pink-500">KIDS</span>
                            </span>
                            <span class="text-[10px] font-extrabold tracking-widest text-pastel-blue-600 uppercase">
                                Estilo Netflix & IMDb Kids
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Links Desktop -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="index.php" class="px-3.5 py-2 rounded-full font-extrabold text-sm transition-all <?= $paginaAtual === 'index.php' ? 'bg-pastel-pink-500 text-white shadow-md' : 'text-pastel-blue-900 hover:bg-pastel-yellow-200 hover:text-pastel-blue-700' ?>">
                        <i class="fa-solid fa-house mr-1"></i> Início
                    </a>

                    <a href="pesquisa.php" class="px-3.5 py-2 rounded-full font-extrabold text-sm transition-all <?= $paginaAtual === 'pesquisa.php' && empty($_GET['ordem']) ? 'bg-pastel-pink-500 text-white shadow-md' : 'text-pastel-blue-900 hover:bg-pastel-yellow-200 hover:text-pastel-blue-700' ?>">
                        <i class="fa-solid fa-film mr-1"></i> Catálogo
                    </a>

                    <a href="pesquisa.php?ordem=nota" class="px-3.5 py-2 rounded-full font-extrabold text-sm transition-all <?= isset($_GET['ordem']) && $_GET['ordem'] === 'nota' ? 'bg-pastel-pink-500 text-white shadow-md' : 'text-pastel-blue-900 hover:bg-pastel-yellow-200 hover:text-pastel-blue-700' ?>">
                        <i class="fa-solid fa-star text-amber-500 mr-1"></i> Top IMDb
                    </a>

                    <a href="favoritos.php" class="px-3.5 py-2 rounded-full font-extrabold text-sm transition-all <?= $paginaAtual === 'favoritos.php' ? 'bg-pastel-pink-500 text-white shadow-md' : 'text-pastel-blue-900 hover:bg-pastel-yellow-200 hover:text-pastel-blue-700' ?>">
                        <i class="fa-solid fa-heart text-pastel-pink-500 mr-1"></i> Meus Favoritos
                    </a>

                    <a href="historico.php" class="px-3.5 py-2 rounded-full font-extrabold text-sm transition-all <?= $paginaAtual === 'historico.php' ? 'bg-pastel-pink-500 text-white shadow-md' : 'text-pastel-blue-900 hover:bg-pastel-yellow-200 hover:text-pastel-blue-700' ?>">
                        <i class="fa-solid fa-clock-rotate-left text-pastel-blue-600 mr-1"></i> Auditoria
                    </a>

                    <?php if (eh_admin()): ?>
                    <a href="admin_filmes.php" class="px-3.5 py-2 rounded-full font-extrabold text-sm bg-pastel-blue-600 hover:bg-pastel-blue-700 text-white shadow transition-all">
                        <i class="fa-solid fa-gear mr-1"></i> Gerenciar Filmes
                    </a>
                    <?php endif; ?>
                </nav>

                <!-- Barra de Busca Rápida e Perfil -->
                <div class="hidden lg:flex items-center gap-3">
                    <form action="pesquisa.php" method="GET" class="relative">
                        <input type="text" name="q" placeholder="Buscar filmes..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" class="w-44 focus:w-60 transition-all duration-300 pl-9 pr-4 py-2 bg-white/90 border-2 border-pastel-yellow-300 rounded-full text-xs font-bold text-pastel-blue-900 focus:outline-none focus:border-pastel-blue-400 focus:ring-2 focus:ring-pastel-blue-200">
                        <button type="submit" class="absolute left-3 top-2.5 text-pastel-blue-500 text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <?php if ($usuarioAtual): ?>
                        <!-- Usuário Logado -->
                        <div class="flex items-center gap-2 pl-2 border-l-2 border-pastel-yellow-300">
                            <a href="perfil.php" class="flex items-center gap-2 group">
                                <div class="w-10 h-10 rounded-full bg-pastel-yellow-300 border-2 border-pastel-pink-400 flex items-center justify-center font-black text-pastel-blue-900 shadow-sm group-hover:scale-105 transition-transform">
                                    <?= strtoupper(substr($usuarioAtual['nome'], 0, 1)) ?>
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-black text-pastel-blue-900 truncate max-w-[100px]"><?= htmlspecialchars($usuarioAtual['nome']) ?></span>
                                    <span class="text-[10px] font-bold text-pastel-pink-500 uppercase"><?= $usuarioAtual['tipo'] ?></span>
                                </div>
                            </a>
                            <a href="actions/logout_action.php" title="Sair da Conta" class="w-8 h-8 rounded-full bg-white hover:bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold transition-colors">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Visitante Não Logado -->
                        <div class="flex items-center gap-2">
                            <a href="login.php" class="px-4 py-2 rounded-full font-extrabold text-xs text-pastel-blue-900 hover:bg-pastel-yellow-200 border border-pastel-yellow-300 transition-colors">
                                Entrar
                            </a>
                            <a href="cadastro.php" class="px-4 py-2 rounded-full font-black text-xs text-white bg-pastel-pink-500 hover:bg-pastel-pink-600 shadow-md hover:shadow-lg transition-all">
                                Cadastrar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Botão Menu Mobile -->
                <div class="flex md:hidden items-center gap-2">
                    <a href="pesquisa.php" class="p-2 text-pastel-blue-700 text-lg">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    <button id="mobile-menu-btn" onclick="toggleMenuMobile()" class="p-2.5 rounded-2xl bg-white border-2 border-pastel-yellow-300 text-pastel-blue-900 text-lg shadow-sm">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Menu Dropdown Mobile -->
        <div id="mobile-menu" class="hidden md:hidden bg-pastel-yellow-100 border-t-2 border-pastel-yellow-200 px-4 pt-3 pb-6 space-y-2">
            <a href="index.php" class="block px-4 py-2.5 rounded-2xl font-black text-sm <?= $paginaAtual === 'index.php' ? 'bg-pastel-pink-500 text-white' : 'text-pastel-blue-900 bg-white/70' ?>">
                <i class="fa-solid fa-house mr-2"></i> Início
            </a>
            <a href="pesquisa.php" class="block px-4 py-2.5 rounded-2xl font-black text-sm <?= $paginaAtual === 'pesquisa.php' ? 'bg-pastel-pink-500 text-white' : 'text-pastel-blue-900 bg-white/70' ?>">
                <i class="fa-solid fa-film mr-2"></i> Catálogo Completo
            </a>
            <a href="pesquisa.php?ordem=nota" class="block px-4 py-2.5 rounded-2xl font-black text-sm text-pastel-blue-900 bg-white/70">
                <i class="fa-solid fa-star text-amber-500 mr-2"></i> Melhores Notas (IMDb)
            </a>
            <a href="favoritos.php" class="block px-4 py-2.5 rounded-2xl font-black text-sm <?= $paginaAtual === 'favoritos.php' ? 'bg-pastel-pink-500 text-white' : 'text-pastel-blue-900 bg-white/70' ?>">
                <i class="fa-solid fa-heart text-pastel-pink-500 mr-2"></i> Meus Favoritos
            </a>
            <a href="historico.php" class="block px-4 py-2.5 rounded-2xl font-black text-sm <?= $paginaAtual === 'historico.php' ? 'bg-pastel-pink-500 text-white' : 'text-pastel-blue-900 bg-white/70' ?>">
                <i class="fa-solid fa-clock-rotate-left text-pastel-blue-600 mr-2"></i> Auditoria de Logs
            </a>
            <?php if (eh_admin()): ?>
            <a href="admin_filmes.php" class="block px-4 py-2.5 rounded-2xl font-black text-sm bg-pastel-blue-600 text-white shadow">
                <i class="fa-solid fa-gear mr-2"></i> Gerenciador de Filmes
            </a>
            <?php endif; ?>

            <div class="pt-3 border-t border-pastel-yellow-300">
                <?php if ($usuarioAtual): ?>
                    <div class="flex items-center justify-between bg-white/80 p-3 rounded-2xl">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-pastel-yellow-300 border-2 border-pastel-pink-400 flex items-center justify-center font-black text-pastel-blue-900">
                                <?= strtoupper(substr($usuarioAtual['nome'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-sm font-black text-pastel-blue-900"><?= htmlspecialchars($usuarioAtual['nome']) ?></p>
                                <p class="text-xs font-bold text-pastel-pink-500 uppercase"><?= $usuarioAtual['tipo'] ?></p>
                            </div>
                        </div>
                        <a href="actions/logout_action.php" class="px-3 py-1.5 rounded-xl bg-pink-100 text-pink-700 font-bold text-xs">
                            Sair
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="login.php" class="text-center py-2.5 rounded-xl font-black text-xs bg-white text-pastel-blue-900 border border-pastel-yellow-300">Entrar</a>
                        <a href="cadastro.php" class="text-center py-2.5 rounded-xl font-black text-xs bg-pastel-pink-500 text-white">Cadastrar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        function toggleMenuMobile() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>

    <!-- Conteúdo Principal Dinâmico -->
    <main class="flex-grow">
