<?php
/**
 * Página de Login - CineKids
 * Cores: Amarelo Pastel (#FEF9C3) com contrastes Azul (#0284C7) e Rosa (#EC4899)
 */
$tituloPagina = "Entrar no CineKids";
require_once __DIR__ . '/includes/header.php';

if (esta_logado()) {
    header('Location: index.php');
    exit;
}
?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl border-4 border-pastel-yellow-300 shadow-xl p-8 space-y-6 relative overflow-hidden">
        
        <!-- Elementos Decorativos Infantis -->
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-pastel-pink-200/50 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-pastel-blue-200/50 rounded-full blur-xl pointer-events-none"></div>

        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-pastel-pink-400 to-pastel-pink-500 text-white flex items-center justify-center text-3xl mx-auto shadow-md">
                🍿
            </div>
            <h1 class="text-3xl font-black text-pastel-blue-900 tracking-tight">
                Olá, Amiguinho!
            </h1>
            <p class="text-xs font-bold text-pastel-blue-700">
                Entre com sua conta para assistir filmes e salvar favoritos.
            </p>
        </div>

        <form action="actions/login_action.php" method="POST" class="space-y-4">
            <div class="space-y-1">
                <label class="text-xs font-black uppercase text-pastel-blue-900">E-mail</label>
                <div class="relative">
                    <input 
                        type="email" 
                        name="email" 
                        id="login-email"
                        required 
                        placeholder="seuemail@exemplo.com"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50"
                    >
                    <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-pastel-blue-400 text-xs"></i>
                </div>
            </div>

            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-black uppercase text-pastel-blue-900">Sua Senha</label>
                </div>
                <div class="relative">
                    <input 
                        type="password" 
                        name="senha" 
                        id="login-senha"
                        required 
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50"
                    >
                    <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-pastel-blue-400 text-xs"></i>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-full font-black text-sm bg-gradient-to-r from-pastel-pink-500 to-rose-500 hover:from-pastel-pink-600 hover:to-rose-600 text-white shadow-pastel-pink hover:scale-102 transition-all">
                Entrar no CineKids 🎈
            </button>
        </form>

        <!-- Contas Prontas de Demonstração (Para avaliação do trabalho) -->
        <div class="bg-pastel-yellow-50 p-4 rounded-2xl border-2 border-pastel-yellow-300 space-y-2">
            <p class="text-[11px] font-black uppercase text-pastel-blue-900 text-center tracking-wider">
                ⚡ Contas de Teste Rápidas (Clique para preencher)
            </p>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <button type="button" onclick="preencherLogin('admin@cinekids.com', 'admin123')" class="p-2 rounded-xl bg-white hover:bg-pastel-yellow-100 border border-pastel-yellow-300 font-bold text-pastel-blue-900 text-left transition-colors">
                    <strong class="block text-pastel-blue-600">👑 Administrador</strong>
                    admin@cinekids.com
                </button>
                <button type="button" onclick="preencherLogin('aluno@etec.sp.gov.br', 'etec123')" class="p-2 rounded-xl bg-white hover:bg-pastel-yellow-100 border border-pastel-yellow-300 font-bold text-pastel-blue-900 text-left transition-colors">
                    <strong class="block text-pastel-pink-600">🎓 Turma ETEC</strong>
                    aluno@etec.sp.gov.br
                </button>
            </div>
            <button type="button" onclick="preencherLogin('kid@cinekids.com', 'kid123')" class="w-full p-2 rounded-xl bg-white hover:bg-pastel-yellow-100 border border-pastel-yellow-300 font-bold text-pastel-blue-900 text-center text-xs transition-colors">
                <span class="text-amber-600">🧸 Usuário Infantil:</span> kid@cinekids.com
            </button>
        </div>

        <div class="text-center pt-2 border-t border-pastel-yellow-200">
            <p class="text-xs font-bold text-pastel-blue-800">
                Ainda não tem conta? <a href="cadastro.php" class="text-pastel-pink-500 font-black hover:underline">Cadastre-se aqui</a>
            </p>
        </div>

    </div>
</div>

<script>
    function preencherLogin(email, senha) {
        document.getElementById('login-email').value = email;
        document.getElementById('login-senha').value = senha;
        mostrarToast('Dados de acesso preenchidos! Clique em Entrar.', 'info');
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
