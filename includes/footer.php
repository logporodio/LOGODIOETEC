<?php
/**
 * Footer Global - CineKids
 */
?>
    </main>

    <!-- Rodapé Amigável -->
    <footer class="mt-16 bg-pastel-yellow-100 border-t-4 border-pastel-yellow-300 text-pastel-blue-900 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                <!-- Coluna 1: Sobre -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-3xl">🍿</span>
                        <span class="text-2xl font-black text-pastel-blue-900">CINE<span class="text-pastel-pink-500">KIDS</span></span>
                    </div>
                    <p class="text-xs text-pastel-blue-700 leading-relaxed font-semibold">
                        A plataforma de streaming e catálogo de cinema feita especialmente para crianças, jovens e famílias. Conteúdo 100% seguro, livre de violência e cheio de magia!
                    </p>
                    <div class="flex items-center gap-2 pt-2">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black bg-emerald-500 text-white shadow-sm">Livre</span>
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black bg-sky-500 text-white shadow-sm">10 Anos</span>
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black bg-amber-500 text-white shadow-sm">12 Anos</span>
                        <span class="text-[11px] font-bold text-pastel-pink-600">Sem conteúdo +14</span>
                    </div>
                </div>

                <!-- Coluna 2: Navegação Rápida -->
                <div>
                    <h4 class="text-sm font-black text-pastel-blue-900 uppercase tracking-wider mb-4 border-b-2 border-pastel-pink-300 pb-1 inline-block">Navegue</h4>
                    <ul class="space-y-2 text-xs font-bold text-pastel-blue-800">
                        <li><a href="index.php" class="hover:text-pastel-pink-500 transition-colors">✨ Início & Carrosséis</a></li>
                        <li><a href="pesquisa.php" class="hover:text-pastel-pink-500 transition-colors">🎬 Catálogo Completo</a></li>
                        <li><a href="pesquisa.php?ordem=nota" class="hover:text-pastel-pink-500 transition-colors">⭐ Melhores Notas (IMDb)</a></li>
                        <li><a href="favoritos.php" class="hover:text-pastel-pink-500 transition-colors">💖 Filmes Favoritos</a></li>
                        <li><a href="historico.php" class="hover:text-pastel-pink-500 transition-colors">📋 Registro de Auditoria</a></li>
                    </ul>
                </div>

                <!-- Coluna 3: Categorias Infantis -->
                <div>
                    <h4 class="text-sm font-black text-pastel-blue-900 uppercase tracking-wider mb-4 border-b-2 border-pastel-pink-300 pb-1 inline-block">Estúdios & Gêneros</h4>
                    <ul class="space-y-2 text-xs font-bold text-pastel-blue-800">
                        <li><a href="pesquisa.php?categoria=Disney+%26+Pixar" class="hover:text-pastel-pink-500 transition-colors">🏰 Disney & Pixar</a></li>
                        <li><a href="pesquisa.php?categoria=Aventura+%26+Animação" class="hover:text-pastel-pink-500 transition-colors">🚀 Aventura & Ação</a></li>
                        <li><a href="pesquisa.php?categoria=Comédia+%26+Diversão" class="hover:text-pastel-pink-500 transition-colors">😂 Comédia & Diversão</a></li>
                        <li><a href="pesquisa.php?categoria=Clássicos+Infantis" class="hover:text-pastel-pink-500 transition-colors">👑 Clássicos Inesquecíveis</a></li>
                    </ul>
                </div>

                <!-- Coluna 4: Informações do Trabalho ETEC -->
                <div class="bg-white/80 p-5 rounded-3xl border-2 border-pastel-yellow-300 shadow-sm">
                    <h4 class="text-xs font-black text-pastel-pink-600 uppercase tracking-wider mb-2">Projeto Acadêmico</h4>
                    <p class="text-xs font-bold text-pastel-blue-900">
                        Desenvolvido pela turma de Logística da <strong>ETEC</strong>.
                    </p>
                    <p class="text-[11px] text-pastel-blue-700 mt-2 leading-relaxed">
                        Sistema estruturado com autenticação segura em Bcrypt, banco de dados MySQL com PDO, design Mobile-First e trilha de auditoria completa.
                    </p>
                </div>

            </div>

            <!-- Faixa Inferior -->
            <div class="pt-6 border-t border-pastel-yellow-300 flex flex-col sm:flex-row items-center justify-between text-xs text-pastel-blue-700 font-bold gap-4">
                <p>© <?= date('Y') ?> CineKids. Todos os direitos reservados para a diversão infantil!</p>
                <div class="flex items-center gap-4 text-sm">
                    <span title="Segurança infantil comprovada" class="text-emerald-600 flex items-center gap-1">
                        <i class="fa-solid fa-shield-halved"></i> 100% Kid-Safe
                    </span>
                    <span class="text-pastel-pink-500 flex items-center gap-1">
                        <i class="fa-solid fa-heart"></i> Feito com carinho
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Inclusão de Modais e Toasts Universais -->
    <?php require_once __DIR__ . '/modal.php'; ?>
    <?php require_once __DIR__ . '/toast.php'; ?>

    <!-- Scripts da Aplicação -->
    <script src="assets/js/app.js"></script>
</body>
</html>
