<?php
/**
 * Página de Cadastro - CineKids
 * Senhas tratadas com hash BCRYPT (Regra CORE)
 */
$tituloPagina = "Cadastre-se no CineKids";
require_once __DIR__ . '/includes/header.php';

if (esta_logado()) {
    header('Location: index.php');
    exit;
}
?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl border-4 border-pastel-yellow-300 shadow-xl p-8 space-y-6 relative overflow-hidden">
        
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-pastel-pink-200/50 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-pastel-blue-200/50 rounded-full blur-xl pointer-events-none"></div>

        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-pastel-pink-400 to-pastel-pink-500 text-white flex items-center justify-center text-3xl mx-auto shadow-md">
                🎈
            </div>
            <h1 class="text-3xl font-black text-pastel-blue-900 tracking-tight">
                Crie Sua Conta Grátis
            </h1>
            <p class="text-xs font-bold text-pastel-blue-700">
                Junte-se à diversão e explore centenas de filmes incríveis!
            </p>
        </div>

        <form action="actions/cadastro_action.php" method="POST" class="space-y-4" onsubmit="return validarFormCadastro(event)">
            
            <div class="space-y-1">
                <label class="text-xs font-black uppercase text-pastel-blue-900">Seu Nome / Apelido *</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="nome" 
                        id="cad-nome"
                        required 
                        placeholder="Ex: Joãozinho Aventureiro"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50"
                    >
                    <i class="fa-solid fa-user absolute left-3.5 top-3.5 text-pastel-blue-400 text-xs"></i>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-black uppercase text-pastel-blue-900">Seu E-mail *</label>
                <div class="relative">
                    <input 
                        type="email" 
                        name="email" 
                        id="cad-email"
                        required 
                        placeholder="amiguinho@cinekids.com"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50"
                    >
                    <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-pastel-blue-400 text-xs"></i>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-black uppercase text-pastel-blue-900">Crie uma Senha (mínimo 6 caracteres) *</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="senha" 
                        id="cad-senha"
                        required 
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50"
                    >
                    <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-pastel-blue-400 text-xs"></i>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-black uppercase text-pastel-blue-900">Confirme sua Senha *</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="confirmar_senha" 
                        id="cad-confirma"
                        required 
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border-2 border-pastel-yellow-300 text-xs font-bold text-pastel-blue-900 focus:outline-none focus:ring-2 focus:ring-pastel-pink-300 bg-pastel-yellow-50/50"
                    >
                    <i class="fa-solid fa-shield-halved absolute left-3.5 top-3.5 text-pastel-blue-400 text-xs"></i>
                </div>
            </div>

            <div class="bg-pastel-yellow-50 p-3 rounded-xl border border-pastel-yellow-200 text-[11px] text-pastel-blue-700 font-semibold flex items-start gap-2">
                <i class="fa-solid fa-lock text-pastel-pink-500 mt-0.5"></i>
                <span>Sua senha será protegida com hash seguro <strong>BCrypt</strong> no banco de dados.</span>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-full font-black text-sm bg-gradient-to-r from-pastel-pink-500 to-rose-500 hover:from-pastel-pink-600 hover:to-rose-600 text-white shadow-pastel-pink hover:scale-102 transition-all">
                Criar Minha Conta 🍿
            </button>
        </form>

        <div class="text-center pt-2 border-t border-pastel-yellow-200">
            <p class="text-xs font-bold text-pastel-blue-800">
                Já possui uma conta? <a href="login.php" class="text-pastel-blue-600 font-black hover:underline">Entre agora</a>
            </p>
        </div>

    </div>
</div>

<script>
    function validarFormCadastro(e) {
        const s1 = document.getElementById('cad-senha').value;
        const s2 = document.getElementById('cad-confirma').value;
        if (s1.length < 6) {
            e.preventDefault();
            CineModal.alert('Senha muito curta', 'A sua senha precisa ter pelo menos 6 caracteres para garantir sua segurança!');
            return false;
        }
        if (s1 !== s2) {
            e.preventDefault();
            CineModal.alert('Senhas diferentes', 'As duas senhas digitadas não coincidem. Verifique e digite novamente!');
            return false;
        }
        return true;
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
