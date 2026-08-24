<?php
/**
 * Conexão Centralizada com o Banco de Dados — Codora Tech
 * Utiliza PDO com prepared statements, charset UTF-8 e tratamento de exceções.
 */

$host = 'localhost';
$dbname = 'codora_db';
$username = 'root';
$password = ''; // Padrão XAMPP local

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Não expõe detalhes sensíveis do erro ao usuário final
    error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
    die("Erro interno ao conectar com a base de dados. Por favor, tente novamente mais tarde.");
}
