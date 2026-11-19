/**
 * JavaScript CineKids
 * Toasts, Modais interativos e Carrosséis estilo Netflix
 */

// --- 1. SISTEMA DE TOASTS ---
function mostrarToast(mensagem, tipo = 'success') {
    const container = document.getElementById('cinekids-toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'pointer-events-auto flex items-center gap-3 p-4 rounded-2xl shadow-xl border-2 transform transition-all duration-300 translate-y-3 opacity-0';

    let icon = '✨';
    let estiloClasses = '';

    if (tipo === 'success') {
        estiloClasses = 'bg-yellow-50 border-sky-400 text-sky-950';
        icon = `<div class="w-8 h-8 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold">✓</div>`;
    } else if (tipo === 'error') {
        estiloClasses = 'bg-pink-50 border-pink-400 text-pink-950';
        icon = `<div class="w-8 h-8 rounded-full bg-pink-500 text-white flex items-center justify-center font-bold">✕</div>`;
    } else if (tipo === 'warning') {
        estiloClasses = 'bg-amber-50 border-yellow-400 text-amber-950';
        icon = `<div class="w-8 h-8 rounded-full bg-yellow-400 text-amber-950 flex items-center justify-center font-bold">!</div>`;
    } else {
        estiloClasses = 'bg-sky-50 border-sky-300 text-sky-950';
        icon = `<div class="w-8 h-8 rounded-full bg-sky-400 text-white flex items-center justify-center font-bold">ℹ</div>`;
    }

    toast.className += ` ${estiloClasses}`;
    toast.innerHTML = `
        <div class="flex-shrink-0">${icon}</div>
        <div class="flex-1 text-sm font-bold leading-snug">${mensagem}</div>
        <button class="text-sky-800/60 hover:text-sky-900 font-bold px-1 text-lg" onclick="this.parentElement.remove()">✕</button>
    `;

    container.appendChild(toast);

    // Animação de entrada
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-3', 'opacity-0');
    });

    // Desaparece automaticamente após 4.5 segundos
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-6');
        setTimeout(() => toast.remove(), 350);
    }, 4500);
}

// --- 2. SISTEMA UNIVERSAL DE MODAL (Substituição de alert e confirm) ---
const CineModal = {
    alert: function(titulo, mensagem, callback = null) {
        const modal = document.getElementById('cinekids-dialog-modal');
        if (!modal) return;

        const iconContainer = document.getElementById('dialog-icon-container');
        const titleEl = document.getElementById('dialog-title');
        const msgEl = document.getElementById('dialog-message');
        const btnCancel = document.getElementById('dialog-btn-cancel');
        const btnConfirm = document.getElementById('dialog-btn-confirm');

        iconContainer.className = 'w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-2xl shadow-md bg-sky-100 text-sky-600';
        iconContainer.innerHTML = '🌟';

        titleEl.textContent = titulo || 'CineKids Informa';
        msgEl.textContent = mensagem;

        btnCancel.classList.add('hidden');
        btnConfirm.textContent = 'Entendido!';
        btnConfirm.className = 'px-6 py-2.5 rounded-full font-bold text-sm bg-sky-500 hover:bg-sky-600 text-white shadow-md hover:shadow-lg transition-all';

        btnConfirm.onclick = () => {
            CineModal.fechar();
            if (callback) callback();
        };

        CineModal.abrir();
    },

    confirm: function(titulo, mensagem, onConfirm, onCancel = null) {
        const modal = document.getElementById('cinekids-dialog-modal');
        if (!modal) return;

        const iconContainer = document.getElementById('dialog-icon-container');
        const titleEl = document.getElementById('dialog-title');
        const msgEl = document.getElementById('dialog-message');
        const btnCancel = document.getElementById('dialog-btn-cancel');
        const btnConfirm = document.getElementById('dialog-btn-confirm');

        iconContainer.className = 'w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-2xl shadow-md bg-pink-100 text-pink-600';
        iconContainer.innerHTML = '❓';

        titleEl.textContent = titulo || 'Tem certeza?';
        msgEl.textContent = mensagem;

        btnCancel.classList.remove('hidden');
        btnCancel.onclick = () => {
            CineModal.fechar();
            if (onCancel) onCancel();
        };

        btnConfirm.textContent = 'Sim, continuar';
        btnConfirm.className = 'px-6 py-2.5 rounded-full font-bold text-sm bg-pink-500 hover:bg-pink-600 text-white shadow-md hover:shadow-lg transition-all';
        btnConfirm.onclick = () => {
            CineModal.fechar();
            if (onConfirm) onConfirm();
        };

        CineModal.abrir();
    },

    abrir: function() {
        const modal = document.getElementById('cinekids-dialog-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        });
    },

    fechar: function() {
        const modal = document.getElementById('cinekids-dialog-modal');
        if (!modal) return;
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 250);
    }
};

// Substituição das funções nativas do navegador para assegurar conformidade total
window.alert = function(msg) {
    CineModal.alert('Mensagem CineKids', msg);
};

// --- 3. MODAL DE DETALHES RÁPIDOS DO FILME (Estilo Netflix & IMDb) ---
function abrirModalFilme(dados) {
    const modal = document.getElementById('cinekids-filme-modal');
    if (!modal) return;

    document.getElementById('modal-filme-banner').src = dados.banner_url || dados.poster_url;
    document.getElementById('modal-filme-titulo').textContent = dados.titulo;
    document.getElementById('modal-filme-orig').textContent = dados.titulo_original ? `(${dados.titulo_original})` : '';
    document.getElementById('modal-filme-categoria').textContent = dados.categoria || 'Infantil';
    
    // Classificação
    const classBadge = document.getElementById('modal-filme-classificacao');
    classBadge.textContent = dados.classificacao === 'L' ? 'Livre' : dados.classificacao + ' anos';
    classBadge.className = `px-2.5 py-1 rounded-md text-xs font-black shadow-sm badge-classificacao-${dados.classificacao}`;

    document.getElementById('modal-filme-ano-duracao').textContent = `${dados.ano} • ${dados.duracao}`;
    document.getElementById('modal-filme-imdb').textContent = parseFloat(dados.nota_imdb).toFixed(1);
    document.getElementById('modal-filme-generos').textContent = dados.generos;
    document.getElementById('modal-filme-sinopse').textContent = dados.sinopse;
    document.getElementById('modal-filme-diretor').textContent = dados.diretor || 'Informação não informada';
    document.getElementById('modal-filme-elenco').textContent = dados.elenco || 'Vozes originais e dubladores';

    // Links de ação
    document.getElementById('modal-filme-btn-assistir').href = `assistir.php?id=${dados.id}`;
    document.getElementById('modal-filme-btn-detalhes').href = `filme.php?id=${dados.id}`;

    // Exibir Modal
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    });
}

function fecharModalFilme() {
    const modal = document.getElementById('cinekids-filme-modal');
    if (!modal) return;
    modal.classList.add('opacity-0');
    modal.querySelector('div').classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 250);
}

// Fechar com tecla ESC ou clique fora
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        fecharModalFilme();
        CineModal.fechar();
    }
});

// --- 4. ROLAGEM HORIZONTAL DE CARROSSÉIS (Netflix Style) ---
function scrollCarousel(containerId, direcao) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const distancia = container.clientWidth * 0.75;
    container.scrollBy({
        left: direcao * distancia,
        behavior: 'smooth'
    });
}
