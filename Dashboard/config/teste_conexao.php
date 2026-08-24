<?php
/**
 * Teste de Conexão PHP → PDO → MySQL → codora_db
 */
require_once 'conexao.php';

header('Content-Type: text/html; charset=utf-8');

try {
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT VERSION() as version, DATABASE() as dbname");
        $result = $stmt->fetch();
        
        echo "<div style='font-family: Arial, sans-serif; padding: 20px; color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px;'>";
        echo "<h3>✅ Sucesso na Conexão!</h3>";
        echo "<p>Conectado com sucesso ao banco de dados <strong>" . htmlspecialchars($result['dbname']) . "</strong> via PDO.</p>";
        echo "<p>Versão do MySQL: " . htmlspecialchars($result['version']) . "</p>";
        echo "</div>";
    }
} catch (Exception $e) {
    echo "<div style='font-family: Arial, sans-serif; padding: 20px; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px;'>";
    echo "<h3>❌ Erro no Teste de Conexão</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
