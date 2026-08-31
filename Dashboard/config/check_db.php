<?php
require_once 'conexao.php';

try {
    // Check table structure
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll();
    echo "<h3>Estrutura da tabela usuarios:</h3><pre>";
    print_r($columns);
    echo "</pre>";

    // Check if test user exists
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $count = $stmt->fetch()['total'];

    if ($count == 0) {
        $senhaHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash, cargo) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrador Codora', 'admin@codoratech.com', $senhaHash, 'Administrador']);
        echo "Usuário de teste criado com sucesso:<br>Email: admin@codoratech.com<br>Senha: admin123<br>";
    } else {
        $stmt = $pdo->query("SELECT id, nome, email, cargo FROM usuarios");
        $users = $stmt->fetchAll();
        echo "<h3>Usuários cadastrados:</h3><pre>";
        print_r($users);
        echo "</pre>";
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
