<?php
/**
 * Ação de Cadastro de Novos Usuários
 * CineKids - Senhas criptografadas com BCRYPT
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cadastro.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

// Validações
if (empty($nome) || empty($email) || empty($senha)) {
    definir_toast('Todos os campos são obrigatórios!', 'warning');
    header('Location: ../cadastro.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    definir_toast('Formato de e-mail inválido!', 'warning');
    header('Location: ../cadastro.php');
    exit;
}

if (strlen($senha) < 6) {
    definir_toast('A senha deve ter pelo menos 6 caracteres!', 'warning');
    header('Location: ../cadastro.php');
    exit;
}

if ($senha !== $confirmar_senha) {
    definir_toast('As senhas digitadas não coincidem!', 'warning');
    header('Location: ../cadastro.php');
    exit;
}

try {
    // Verificar se o e-mail já existe
    $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->fetch()) {
        definir_toast('Este e-mail já está cadastrado no CineKids! Tente fazer login.', 'warning');
        header('Location: ../cadastro.php');
        exit;
    }

    // Regra CORE: Tratar senhas de usuários com hash bcrypt
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nome, email, senha, tipo, avatar)
        VALUES (:nome, :email, :senha, 'usuario', 'avatar_kid.png')
    ");
    $stmt->execute([
        ':nome'  => $nome,
        ':email' => $email,
        ':senha' => $senhaHash
    ]);

    $novoId = (int)$pdo->lastInsertId();

    // Auditoria
    registrar_log($pdo, 'CADASTRO', "Novo usuário cadastrado com sucesso: {$nome} ({$email})", $novoId, $nome);

    // Login automático da nova conta
    $_SESSION['usuario'] = [
        'id'     => $novoId,
        'nome'   => $nome,
        'email'  => $email,
        'tipo'   => 'usuario',
        'avatar' => 'avatar_kid.png'
    ];

    definir_toast("Parabéns, {$nome}! Sua conta CineKids foi criada com sucesso! 🎈", 'success');
    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    definir_toast('Erro no banco de dados ao realizar o cadastro. Tente novamente.', 'error');
    header('Location: ../cadastro.php');
    exit;
}
