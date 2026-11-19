<?php
/**
 * Ação de Login de Usuários
 * CineKids
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    definir_toast('Por favor, preencha todos os campos!', 'warning');
    header('Location: ../login.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido
        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id'     => $usuario['id'],
            'nome'   => $usuario['nome'],
            'email'  => $usuario['email'],
            'tipo'   => $usuario['tipo'],
            'avatar' => $usuario['avatar']
        ];

        // Auditoria
        registrar_log($pdo, 'LOGIN', "Usuário '{$usuario['nome']}' efetuou login com sucesso.", $usuario['id'], $usuario['nome']);

        definir_toast("Bem-vindo de volta, {$usuario['nome']}! 🍿", 'success');
        
        $destino = ($usuario['tipo'] === 'admin') ? '../admin_filmes.php' : '../index.php';
        header("Location: {$destino}");
        exit;
    } else {
        // Falha no login
        registrar_log($pdo, 'LOGIN_FALHOU', "Tentativa fracassada de login para o e-mail: {$email}");
        definir_toast('E-mail ou senha incorretos! Tente novamente.', 'error');
        header('Location: ../login.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());
    definir_toast('Erro no sistema ao realizar o login. Tente mais tarde.', 'error');
    header('Location: ../login.php');
    exit;
}
