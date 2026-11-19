<?php
/**
 * Ação de Salvar/Atualizar Filme
 * CineKids - Somente Administradores
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

exigir_admin('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_filmes.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$titulo = trim($_POST['titulo'] ?? '');
$titulo_original = trim($_POST['titulo_original'] ?? '');
$sinopse = trim($_POST['sinopse'] ?? '');
$ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
$duracao = trim($_POST['duracao'] ?? '');
$classificacao = trim($_POST['classificacao'] ?? 'L');
$categoria = trim($_POST['categoria'] ?? 'Disney & Pixar');
$generos = trim($_POST['generos'] ?? '');
$nota_imdb = filter_input(INPUT_POST, 'nota_imdb', FILTER_VALIDATE_FLOAT);
$poster_url = trim($_POST['poster_url'] ?? '');
$banner_url = trim($_POST['banner_url'] ?? '');
$trailer_url = trim($_POST['trailer_url'] ?? '');
$diretor = trim($_POST['diretor'] ?? '');
$elenco = trim($_POST['elenco'] ?? '');
$destaque = isset($_POST['destaque']) ? 1 : 0;

// Validações
if (empty($titulo) || empty($sinopse) || empty($ano) || empty($duracao) || empty($poster_url) || empty($trailer_url)) {
    definir_toast('Preencha todos os campos obrigatórios do filme!', 'warning');
    header('Location: ../admin_filmes.php' . ($id ? "?editar={$id}" : ''));
    exit;
}

// REGRA CORE: Proibido filmes para público +14 para cima no CineKids!
$classificacoes_permitidas = ['L', '10', '12'];
if (!in_array($classificacao, $classificacoes_permitidas)) {
    definir_toast('Erro: O CineKids aceita exclusivamente filmes infantis e juvenis (Classificação Livre, 10 ou 12 anos). Proibido conteúdo +14!', 'error');
    header('Location: ../admin_filmes.php' . ($id ? "?editar={$id}" : ''));
    exit;
}

// Limpar trailer_url se for um link do YouTube completo para extrair apenas o ID
if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $trailer_url, $match)) {
    $trailer_url = $match[1];
}

if (empty($banner_url)) {
    $banner_url = $poster_url;
}

$admin = usuario_logado();

try {
    if ($id) {
        // Atualização de Filme Existente
        $stmt = $pdo->prepare("
            UPDATE filmes SET 
                titulo = :titulo,
                titulo_original = :titulo_original,
                sinopse = :sinopse,
                ano = :ano,
                duracao = :duracao,
                classificacao = :classificacao,
                categoria = :categoria,
                generos = :generos,
                nota_imdb = :nota_imdb,
                poster_url = :poster_url,
                banner_url = :banner_url,
                trailer_url = :trailer_url,
                diretor = :diretor,
                elenco = :elenco,
                destaque = :destaque
            WHERE id = :id
        ");
        $stmt->execute([
            ':titulo'          => $titulo,
            ':titulo_original' => $titulo_original,
            ':sinopse'         => $sinopse,
            ':ano'             => $ano,
            ':duracao'         => $duracao,
            ':classificacao'   => $classificacao,
            ':categoria'       => $categoria,
            ':generos'         => $generos,
            ':nota_imdb'       => $nota_imdb ?: 8.0,
            ':poster_url'      => $poster_url,
            ':banner_url'      => $banner_url,
            ':trailer_url'     => $trailer_url,
            ':diretor'         => $diretor,
            ':elenco'          => $elenco,
            ':destaque'        => $destaque,
            ':id'              => $id
        ]);

        registrar_log($pdo, 'EDITAR_FILME', "Administrador '{$admin['nome']}' atualizou o filme '{$titulo}' (ID #{$id}).", $admin['id'], $admin['nome']);
        definir_toast("Filme '{$titulo}' atualizado com sucesso! 🎬", 'success');

    } else {
        // Inserção de Novo Filme
        $stmt = $pdo->prepare("
            INSERT INTO filmes 
                (titulo, titulo_original, sinopse, ano, duracao, classificacao, categoria, generos, nota_imdb, poster_url, banner_url, trailer_url, diretor, elenco, destaque)
            VALUES 
                (:titulo, :titulo_original, :sinopse, :ano, :duracao, :classificacao, :categoria, :generos, :nota_imdb, :poster_url, :banner_url, :trailer_url, :diretor, :elenco, :destaque)
        ");
        $stmt->execute([
            ':titulo'          => $titulo,
            ':titulo_original' => $titulo_original,
            ':sinopse'         => $sinopse,
            ':ano'             => $ano,
            ':duracao'         => $duracao,
            ':classificacao'   => $classificacao,
            ':categoria'       => $categoria,
            ':generos'         => $generos,
            ':nota_imdb'       => $nota_imdb ?: 8.0,
            ':poster_url'      => $poster_url,
            ':banner_url'      => $banner_url,
            ':trailer_url'     => $trailer_url,
            ':diretor'         => $diretor,
            ':elenco'          => $elenco,
            ':destaque'        => $destaque
        ]);

        $novoId = $pdo->lastInsertId();
        registrar_log($pdo, 'INSERIR_FILME', "Administrador '{$admin['nome']}' cadastrou novo filme infantil '{$titulo}' (ID #{$novoId}).", $admin['id'], $admin['nome']);
        definir_toast("Novo filme '{$titulo}' adicionado ao catálogo! 🎈", 'success');
    }

    header('Location: ../admin_filmes.php');
    exit;

} catch (PDOException $e) {
    error_log("Erro ao salvar filme: " . $e->getMessage());
    definir_toast('Erro no banco de dados ao salvar o filme: ' . $e->getMessage(), 'error');
    header('Location: ../admin_filmes.php');
    exit;
}
