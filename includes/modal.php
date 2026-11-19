<?php
/**
 * Componentes de Modais - CineKids
 * Tratamento de caixas de mensagens em modais em vez dos popups nativos
 */
?>
<!-- Modal de Diálogo Genérico (Substituto nativo de alert/confirm) -->
<div id="cinekids-dialog-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="relative w-full max-w-md bg-yellow-50 border-4 border-yellow-200 rounded-3xl shadow-2xl p-6 transform transition-transform duration-300 scale-95 overflow-hidden">
        <!-- Detalhe decorativo infantil -->
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-pink-200/50 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-sky-200/50 rounded-full blur-xl pointer-events-none"></div>

        <div class="flex items-start gap-4">
            <div id="dialog-icon-container" class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-2xl shadow-md">
                <!-- Ícone inserido via JS -->
            </div>
            <div class="flex-1">
                <h3 id="dialog-title" class="text-xl font-extrabold text-sky-900 tracking-tight"></h3>
                <p id="dialog-message" class="text-sky-800/90 mt-2 text-sm leading-relaxed"></p>
            </div>
        </div>

        <div id="dialog-buttons" class="mt-6 flex items-center justify-end gap-3">
            <button id="dialog-btn-cancel" class="px-5 py-2.5 rounded-full font-bold text-sm bg-white border border-sky-200 text-sky-800 hover:bg-yellow-100 hover:border-yellow-400 transition-all shadow-sm hidden">
                Cancelar
            </button>
            <button id="dialog-btn-confirm" class="px-6 py-2.5 rounded-full font-bold text-sm bg-pink-500 hover:bg-pink-600 text-white shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                Confirmar
            </button>
        </div>
    </div>
</div>

<!-- Modal de Detalhes Rápidos do Filme (Estilo Netflix Preview) -->
<div id="cinekids-filme-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/75 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="relative w-full max-w-3xl bg-yellow-50 border-4 border-yellow-300 rounded-3xl shadow-2xl overflow-hidden transform transition-transform duration-300 scale-95 max-h-[90vh] flex flex-col">
        <!-- Botão Fechar -->
        <button onclick="fecharModalFilme()" class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-pink-500 hover:text-white text-sky-900 flex items-center justify-center font-black text-xl shadow-lg transition-all">
            ✕
        </button>

        <!-- Banner com imagem -->
        <div class="relative h-56 sm:h-72 w-full bg-sky-900 overflow-hidden flex-shrink-0">
            <img id="modal-filme-banner" src="" alt="Banner do Filme" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-yellow-50 via-sky-950/40 to-transparent"></div>
            
            <div class="absolute bottom-4 left-6 right-6">
                <span id="modal-filme-categoria" class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-pink-500 text-white shadow"></span>
                <h2 id="modal-filme-titulo" class="text-2xl sm:text-3xl font-black text-white drop-shadow-md mt-1"></h2>
                <p id="modal-filme-orig" class="text-xs text-yellow-200 italic"></p>
            </div>
        </div>

        <!-- Conteúdo do Filme -->
        <div class="p-6 overflow-y-auto space-y-4">
            <!-- Badges e IMDb -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Classificação Indicativa -->
                <span id="modal-filme-classificacao" class="px-2.5 py-1 rounded-md text-xs font-black shadow-sm"></span>
                <!-- Ano e Duração -->
                <span id="modal-filme-ano-duracao" class="text-xs font-bold text-sky-800 bg-white/80 px-2.5 py-1 rounded-full border border-sky-100"></span>
                <!-- Nota IMDb -->
                <div class="flex items-center gap-1.5 bg-yellow-300 px-3 py-1 rounded-full text-xs font-extrabold text-sky-950 shadow-sm">
                    <span class="text-yellow-600 text-sm">⭐</span>
                    <span id="modal-filme-imdb"></span>
                    <span class="text-[10px] text-sky-800/70 font-semibold">IMDb</span>
                </div>
            </div>

            <!-- Gêneros -->
            <p id="modal-filme-generos" class="text-xs font-bold text-pink-600 uppercase tracking-wide"></p>

            <!-- Breve Descrição -->
            <div>
                <h4 class="text-xs font-extrabold text-sky-900 uppercase tracking-wider mb-1">Sinopse</h4>
                <p id="modal-filme-sinopse" class="text-sky-950 text-sm leading-relaxed bg-white/60 p-4 rounded-2xl border border-yellow-200 shadow-inner"></p>
            </div>

            <!-- Diretor e Elenco -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-sky-800 pt-1">
                <div><strong class="text-sky-950">Direção:</strong> <span id="modal-filme-diretor"></span></div>
                <div><strong class="text-sky-950">Elenco Principal:</strong> <span id="modal-filme-elenco"></span></div>
            </div>

            <!-- Botões de Ação -->
            <div class="pt-3 flex flex-wrap items-center justify-between gap-3 border-t border-yellow-200">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Botão para Assistir -->
                    <a id="modal-filme-btn-assistir" href="#" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-6 py-3 rounded-full font-black text-sm text-white bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 shadow-lg hover:shadow-pink-300/50 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        Assistir Agora
                    </a>
                    <!-- Botão Ficha Completa IMDb -->
                    <a id="modal-filme-btn-detalhes" href="#" class="flex items-center justify-center gap-2 px-5 py-3 rounded-full font-bold text-sm text-sky-900 bg-white hover:bg-sky-50 border-2 border-sky-200 transition-all shadow-sm">
                        Ver Detalhes (IMDb)
                    </a>
                </div>

                <div id="modal-filme-btn-favorito-container">
                    <!-- Botão de Favoritar dinâmico -->
                </div>
            </div>
        </div>
    </div>
</div>
