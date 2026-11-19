<?php
/**
 * Conexão com o Banco de Dados MySQL via PDO
 * CineKids - Segurança contra SQL Injection
 */

$host = '127.0.0.1';
$port = '3306';
$dbname = 'cinekids';
$username = 'root';
$password = '';

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Tratamento amigável e seguro de erro de conexão
    die("Erro ao conectar com o banco de dados do CineKids: " . htmlspecialchars($e->getMessage()));
}
