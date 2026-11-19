<?php
/**
 * Módulo de Auditoria e Histórico de Logs
 * CineKids
 */

if (!function_exists('registrar_log')) {
    /**
     * Registra qualquer alteração ou evento relevante no sistema para auditoria futura
     *
     * @param PDO $pdo Instância PDO ativa
     * @param string $acao Nome da ação (ex: 'LOGIN', 'CRIAR_FILME', 'EDITAR_FILME', 'EXCLUIR_FILME')
     * @param string $detalhes Descrição detalhada do evento
     * @param int|null $usuario_id ID do usuário (ou puxado da sessão)
     * @param string|null $usuario_nome Nome do usuário (ou puxado da sessão)
     * @return bool
     */
    function registrar_log($pdo, $acao, $detalhes, $usuario_id = null, $usuario_nome = null) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Se não foi passado explicitamente, busca na sessão do usuário logado
            if ($usuario_id === null && isset($_SESSION['usuario']['id'])) {
                $usuario_id = (int)$_SESSION['usuario']['id'];
            }

            if ($usuario_nome === null && isset($_SESSION['usuario']['nome'])) {
                $usuario_nome = $_SESSION['usuario']['nome'];
            } elseif ($usuario_nome === null && $usuario_id === null) {
                $usuario_nome = 'Visitante / Sistema';
            }

            // Captura IP do cliente com segurança
            $ip = '127.0.0.1';
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
            } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $stmt = $pdo->prepare("
                INSERT INTO logs_auditoria (usuario_id, usuario_nome, acao, detalhes, ip)
                VALUES (:usuario_id, :usuario_nome, :acao, :detalhes, :ip)
            ");

            return $stmt->execute([
                ':usuario_id'   => $usuario_id,
                ':usuario_nome' => $usuario_nome,
                ':acao'         => strtoupper(trim($acao)),
                ':detalhes'     => trim($detalhes),
                ':ip'           => substr(trim($ip), 0, 45)
            ]);
        } catch (Exception $e) {
            // Em caso de erro na auditoria, registra no error_log do PHP sem travar o sistema
            error_log("Falha ao gravar log de auditoria: " . $e->getMessage());
            return false;
        }
    }
}
