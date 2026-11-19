<?php
/**
 * API Endpoint: Retorna dados do filme em JSON para o modal
 * CineKids
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/bd.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(['sucesso' => false, 'erro' => 'ID inválido']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $filme = $stmt->fetch();

    if ($filme) {
        echo json_encode(['sucesso' => true, 'dados' => $filme]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Filme não encontrado']);
    }
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno']);
}
