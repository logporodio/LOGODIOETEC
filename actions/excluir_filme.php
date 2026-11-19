<?php
/**
 * Ação de Excluir Filme
 * CineKids - Somente Administradores
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

exigir_admin('../login.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    definir_toast('ID do filme inválido!', 'warning');
    header('Location: ../admin_filmes.php');
    exit;
}

$admin = usuario_logado();

try {
    // Buscar nome do filme para registro no log
    $stmtFind = $pdo->prepare("SELECT titulo FROM filmes WHERE id = :id LIMIT 1");
    $stmtFind->execute([':id' => $id]);
    $filme = $stmtFind->fetch();

    if ($filme) {
        $stmt = $pdo->prepare("DELETE FROM filmes WHERE id = :id");
        $stmt->execute([':id' => $id]);

        registrar_log($pdo, 'EXCLUIR_FILME', "Administrador '{$admin['nome']}' excluiu o filme '{$filme['titulo']}' (ID #{$id}).", $admin['id'], $admin['nome']);
        definir_toast("Filme '{$filme['titulo']}' foi removido do catálogo com sucesso.", 'info');
    } else {
        definir_toast('Filme não encontrado.', 'warning');
    }

    header('Location: ../admin_filmes.php');
    exit;

} catch (PDOException $e) {
    error_log("Erro ao excluir filme: " . $e->getMessage());
    definir_toast('Erro ao tentar excluir o filme: ' . $e->getMessage(), 'error');
    header('Location: ../admin_filmes.php');
    exit;
}
