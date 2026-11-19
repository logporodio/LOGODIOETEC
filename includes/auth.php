<?php
/**
 * Gerenciamento de Sessão e Autenticação
 * CineKids
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se há usuário logado
 */
function esta_logado() {
    return isset($_SESSION['usuario']) && !empty($_SESSION['usuario']['id']);
}

/**
 * Retorna os dados do usuário logado ou null
 */
function usuario_logado() {
    return esta_logado() ? $_SESSION['usuario'] : null;
}

/**
 * Verifica se o usuário atual é administrador
 */
function eh_admin() {
    return esta_logado() && isset($_SESSION['usuario']['tipo']) && $_SESSION['usuario']['tipo'] === 'admin';
}

/**
 * Redireciona caso o usuário não esteja logado
 */
function exigir_login($redirecionar_para = 'login.php') {
    if (!esta_logado()) {
        definir_toast('Você precisa fazer login para acessar esta página!', 'warning');
        header("Location: {$redirecionar_para}");
        exit;
    }
}

/**
 * Redireciona caso o usuário não seja admin
 */
function exigir_admin($redirecionar_para = 'index.php') {
    exigir_login('login.php');
    if (!eh_admin()) {
        definir_toast('Acesso restrito apenas para administradores do CineKids!', 'error');
        header("Location: {$redirecionar_para}");
        exit;
    }
}

/**
 * Define uma notificação toast para exibição na próxima tela
 * @param string $mensagem Texto da notificação
 * @param string $tipo 'success', 'error', 'info', 'warning'
 */
function definir_toast($mensagem, $tipo = 'success') {
    $_SESSION['toast'] = [
        'mensagem' => $mensagem,
        'tipo'     => $tipo
    ];
}

/**
 * Recupera e remove o toast da sessão
 */
function obter_toast() {
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        unset($_SESSION['toast']);
        return $toast;
    }
    return null;
}
