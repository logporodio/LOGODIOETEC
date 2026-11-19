<?php
/**
 * Ação de Favoritar / Desfavoritar Filme
 * CineKids
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

if (!esta_logado()) {
    definir_toast('Faça login para salvar filmes nos seus favoritos!', 'warning');
    header('Location: ../login.php');
    exit;
}

$filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'filme_id', FILTER_VALIDATE_INT);
$usuario = usuario_logado();

if (!$filme_id) {
    definir_toast('Filme inválido!', 'warning');
    header('Location: ../index.php');
    exit;
}

try {
    // Buscar filme
    $stmtFilme = $pdo->prepare("SELECT titulo FROM filmes WHERE id = :id LIMIT 1");
    $stmtFilme->execute([':id' => $filme_id]);
    $filme = $stmtFilme->fetch();

    if (!$filme) {
        definir_toast('Filme não encontrado!', 'warning');
        header('Location: ../index.php');
        exit;
    }

    // Verificar se já está favoritado
    $stmtCheck = $pdo->prepare("SELECT id FROM favoritos WHERE filme_id = :filme_id AND usuario_id = :usuario_id LIMIT 1");
    $stmtCheck->execute([
        ':filme_id'   => $filme_id,
        ':usuario_id' => $usuario['id']
    ]);
    $fav = $stmtCheck->fetch();

    if ($fav) {
        // Remover dos favoritos
        $stmtDel = $pdo->prepare("DELETE FROM favoritos WHERE id = :id");
        $stmtDel->execute([':id' => $fav['id']]);

        registrar_log($pdo, 'DESFAVORITAR', "Usuário '{$usuario['nome']}' removeu '{$filme['titulo']}' dos favoritos.", $usuario['id'], $usuario['nome']);
        definir_toast("Removido dos seus favoritos: {$filme['titulo']}", 'info');
    } else {
        // Adicionar aos favoritos
        $stmtAdd = $pdo->prepare("INSERT INTO favoritos (filme_id, usuario_id) VALUES (:filme_id, :usuario_id)");
        $stmtAdd->execute([
            ':filme_id'   => $filme_id,
            ':usuario_id' => $usuario['id']
        ]);

        registrar_log($pdo, 'FAVORITAR', "Usuário '{$usuario['nome']}' favoritou '{$filme['titulo']}'.", $usuario['id'], $usuario['nome']);
        definir_toast("Adicionado com carinho aos favoritos: {$filme['titulo']} 💖", 'success');
    }

    $voltar = $_SERVER['HTTP_REFERER'] ?? '../index.php';
    header("Location: {$voltar}");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao alternar favorito: " . $e->getMessage());
    definir_toast('Erro ao atualizar favoritos.', 'error');
    header('Location: ../index.php');
    exit;
}
