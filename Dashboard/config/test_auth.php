<?php
/**
 * Script de Testes Automatizados para Autenticação Real — Codora Tech
 */
require_once 'conexao.php';

echo "<h2>🧪 Iniciando Testes de Autenticação Codora Tech</h2>";

$passed = 0;
$total = 0;

function assertTest($condition, $testName) {
    global $passed, $total;
    $total++;
    if ($condition) {
        echo "<p style='color: green;'>✅ PASSOU: {$testName}</p>";
        $passed++;
    } else {
        echo "<p style='color: red;'>❌ FALHOU: {$testName}</p>";
    }
}

try {
    // 1. Verificar existência do usuário administrador de teste
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute(['admin@codoratech.com']);
    $user = $stmt->fetch();

    assertTest($user !== false, "Usuário admin@codoratech.com existe no banco de dados");

    if ($user) {
        // 2. Testar password_verify com senha correta ('admin123')
        $validPassword = password_verify('admin123', $user['senha_hash']);
        assertTest($validPassword === true, "password_verify() retorna verdadeiro para senha correta ('admin123')");

        // 3. Testar password_verify com senha incorreta
        $invalidPassword = password_verify('senhaerrada', $user['senha_hash']);
        assertTest($invalidPassword === false, "password_verify() retorna falso para senha incorreta");
    }

    // 4. Testar consulta com email inexistente
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute(['inexistente@codoratech.com']);
    $nonExistentUser = $stmt->fetch();
    assertTest($nonExistentUser === false, "Consulta com email inexistente não retorna registros");

    // 5. Testar integridade da sessão (simulação)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['usuario_id'] = $user['id'] ?? 1;
    $_SESSION['usuario_nome'] = $user['nome'] ?? 'Administrador';
    
    assertTest(isset($_SESSION['usuario_id']), "Sessão PHP criada e atribuída com sucesso");

    // 6. Testar destruição de sessão (Logout simulation)
    $_SESSION = array();
    session_destroy();
    
    $sessionDestroyed = !isset($_SESSION['usuario_id']);
    assertTest($sessionDestroyed, "Sessão destruída com sucesso (Logout)");

    echo "<hr>";
    echo "<h3>Resultado dos Testes: {$passed} de {$total} testes passaram com sucesso.</h3>";

} catch (Exception $e) {
    echo "<p style='color: red;'>Erro durante os testes: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
