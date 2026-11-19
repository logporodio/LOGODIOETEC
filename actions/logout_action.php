<?php
/**
 * Ação de Logout
 * CineKids
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

if (esta_logado()) {
    $user = usuario_logado();
    registrar_log($pdo, 'LOGOUT', "Usuário '{$user['nome']}' desconectou-se do sistema.", $user['id'], $user['nome']);
}

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

session_start();
definir_toast('Você saiu com sucesso. Volte logo para assistir mais filmes! 🍿', 'info');
header('Location: ../index.php');
exit;
