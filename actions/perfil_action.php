<?php
/**
 * Ação de Atualização de Perfil
 * CineKids
 */
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logs.php';

exigir_login('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../perfil.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$senha_atual = $_POST['senha_atual'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';
$usuario = usuario_logado();

if (empty($nome)) {
    definir_toast('O nome não pode estar vazio!', 'warning');
    header('Location: ../perfil.php');
    exit;
}

try {
    // Se solicitou troca de senha
    if (!empty($nova_senha)) {
        if (strlen($nova_senha) < 6) {
            definir_toast('A nova senha deve ter pelo menos 6 caracteres!', 'warning');
            header('Location: ../perfil.php');
            exit;
        }

        // Verificar senha atual
        $stmtUser = $pdo->prepare("SELECT senha FROM usuarios WHERE id = :id LIMIT 1");
        $stmtUser->execute([':id' => $usuario['id']]);
        $hashAtual = $stmtUser->fetchColumn();

        if (!password_verify($senha_atual, $hashAtual)) {
            definir_toast('Senha atual incorreta! A alteração de senha foi cancelada.', 'error');
            header('Location: ../perfil.php');
            exit;
        }

        $novaSenhaHash = password_hash($nova_senha, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, senha = :senha WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':senha' => $novaSenhaHash, ':id' => $usuario['id']]);

        registrar_log($pdo, 'ALTERAR_SENHA', "Usuário '{$usuario['nome']}' alterou sua senha de acesso.", $usuario['id'], $usuario['nome']);
        definir_toast('Perfil e senha atualizados com sucesso!', 'success');
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $usuario['id']]);

        registrar_log($pdo, 'ATUALIZAR_PERFIL', "Usuário '{$usuario['nome']}' alterou seu nome para '{$nome}'.", $usuario['id'], $usuario['nome']);
        definir_toast('Perfil atualizado com sucesso!', 'success');
    }

    $_SESSION['usuario']['nome'] = $nome;
    header('Location: ../perfil.php');
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar perfil: " . $e->getMessage());
    definir_toast('Erro no banco de dados ao salvar alterações.', 'error');
    header('Location: ../perfil.php');
    exit;
}
