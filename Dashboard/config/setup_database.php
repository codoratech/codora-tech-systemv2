<?php
/**
 * Script de Configuração Automática do Banco de Dados — Codora Tech
 */

$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Conectar ao MySQL sem especificar o banco
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Ler e executar o codora_db.sql
    $sqlFile = __DIR__ . '/../../codora_db.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Executar queries separadas por ponto e vírgula
        // Remove comentários SQL básicos se necessário, ou executa via PDO
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $stmt) {
            if (!empty($stmt)) {
                $pdo->exec($stmt);
            }
        }
        echo "<h3>Banco de dados criado e estruturado com sucesso!</h3>";
    } else {
        // Fallback caso o arquivo SQL não esteja na raiz
        $pdo->exec("CREATE DATABASE IF NOT EXISTS codora_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $pdo->exec("USE codora_db;");
        echo "<h3>Banco de dados codora_db verificado/criado!</h3>";
    }

    // Conectar ao banco codora_db para inserir o admin
    $pdoDb = new PDO("mysql:host=$host;dbname=codora_db;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Garantir tabela de usuários caso o SQL não a tenha criado
    $pdoDb->exec("CREATE TABLE IF NOT EXISTS usuarios (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(100) NOT NULL,
      email VARCHAR(100) NOT NULL UNIQUE,
      senha_hash VARCHAR(255) NOT NULL,
      cargo VARCHAR(50) DEFAULT 'Administrador',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Verificar se já existe admin
    $stmt = $pdoDb->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->execute(['admin@codoratech.com.br']);
    if ($stmt->fetchColumn() == 0) {
        $senhaHash = password_hash('123456', PASSWORD_DEFAULT);
        $insert = $pdoDb->prepare("INSERT INTO usuarios (nome, email, senha_hash, cargo) VALUES (?, ?, ?, ?)");
        $insert->execute(['Administrador', 'admin@codoratech.com.br', $senhaHash, 'Administrador']);
        echo "<p style='color: green;'>Usuário Administrador criado com sucesso!</p>";
    } else {
        echo "<p style='color: blue;'>Usuário Administrador já existe.</p>";
    }

    echo "<br><a href='../html/auth-login.php' style='font-size: 18px; padding: 10px 20px; background: #696cff; color: white; text-decoration: none; border-radius: 5px;'>Ir para a Tela de Login</a>";

} catch (Throwable $e) {
    echo "<h3 style='color: red;'>Erro ao configurar o banco de dados:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p>Certifique-se de que o <strong>MySQL</strong> está ligado no XAMPP Control Panel.</p>";
}
