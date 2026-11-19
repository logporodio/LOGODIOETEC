<?php
/**
 * Ação de Avaliar Filme (Estilo IMDb Kids)
 * CineKids
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

if (!esta_logado()) {
    definir_toast('Você precisa estar logado para avaliar este filme!', 'warning');
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_VALIDATE_INT);
$nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);
$comentario = trim($_POST['comentario'] ?? '');
$usuario = usuario_logado();

if (!$filme_id || !$nota || $nota < 1 || $nota > 5) {
    definir_toast('Selecione uma nota válida entre 1 e 5 estrelas!', 'warning');
    header('Location: ../filme.php?id=' . $filme_id);
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

    // Inserir ou atualizar avaliação
    $stmtUpsert = $pdo->prepare("
        INSERT INTO avaliacoes (filme_id, usuario_id, nota, comentario)
        VALUES (:filme_id, :usuario_id, :nota, :comentario)
        ON DUPLICATE KEY UPDATE 
            nota = VALUES(nota),
            comentario = VALUES(comentario),
            criado_em = CURRENT_TIMESTAMP
    ");
    $stmtUpsert->execute([
        ':filme_id'   => $filme_id,
        ':usuario_id' => $usuario['id'],
        ':nota'       => $nota,
        ':comentario' => $comentario
    ]);

    // Registrar no histórico de auditoria
    registrar_log($pdo, 'AVALIAR_FILME', "Usuário '{$usuario['nome']}' avaliou '{$filme['titulo']}' com {$nota} estrela(s).", $usuario['id'], $usuario['nome']);

    definir_toast("Obrigado pela sua avaliação de {$nota} estrelas para {$filme['titulo']}! ⭐", 'success');
    header('Location: ../filme.php?id=' . $filme_id);
    exit;

} catch (PDOException $e) {
    error_log("Erro ao avaliar filme: " . $e->getMessage());
    definir_toast('Erro ao registrar sua avaliação.', 'error');
    header('Location: ../filme.php?id=' . $filme_id);
    exit;
}
