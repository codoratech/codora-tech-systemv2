<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/validation.php';

function require_admin(): array
{
    session_start_secure();
    security_headers(true);
    if (empty($_SESSION['admin_id'])) redirect('login.php');
    try {
        $timeout = max(300, min(7200, (int) (config()['session_timeout'] ?? 1800)));
        if (time() - (int) ($_SESSION['last_activity'] ?? 0) > $timeout) {
            destroy_session();
            redirect('login.php?expired=1');
        }
        $stmt = db()->prepare('SELECT id, nome, email, senha_hash, ativo FROM admin_users WHERE id = ?');
        $stmt->execute([$_SESSION['admin_id']]);
        $user = $stmt->fetch();
        if (!$user || !$user['ativo'] || !hash_equals(hash('sha256', $user['senha_hash']), $_SESSION['auth_signature'] ?? '')) {
            destroy_session();
            redirect('login.php');
        }
        $_SESSION['last_activity'] = time();
        return $user;
    } catch (Throwable $error) {
        error_log('Codora auth: ' . $error->getMessage());
        http_response_code(503);
        exit('Painel temporariamente indisponível. Tente novamente mais tarde.');
    }
}

function attempt_login(string $email, string $password, string $ip): bool
{
    $pdo = db();
    $key = hash('sha256', $ip . "\0" . $email);
    $pdo->beginTransaction();
    try {
        // A linha única e o lock serializam falhas concorrentes do mesmo IP + e-mail.
        $pdo->prepare('INSERT INTO login_tentativas (chave, ip, email, falhas) VALUES (?, ?, ?, 0) ON DUPLICATE KEY UPDATE chave = VALUES(chave)')->execute([$key, $ip, $email]);
        $stmt = $pdo->prepare('SELECT falhas, bloqueado_ate, (bloqueado_ate > UTC_TIMESTAMP()) AS bloqueado FROM login_tentativas WHERE chave = ? FOR UPDATE');
        $stmt->execute([$key]);
        $attempt = $stmt->fetch();
        if ($attempt['bloqueado']) {
            $pdo->commit();
            return false;
        }
        $failures = $attempt['bloqueado_ate'] !== null ? 0 : (int) $attempt['falhas'];
        $stmt = $pdo->prepare('SELECT id, senha_hash, ativo FROM admin_users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        $dummy = password_hash(bin2hex(random_bytes(24)), PASSWORD_DEFAULT);
        $valid = password_verify($password, $user['senha_hash'] ?? $dummy);
        if (!$user || !$user['ativo'] || !$valid) {
            $failures++;
            $pdo->prepare('UPDATE login_tentativas SET falhas = ?, bloqueado_ate = IF(? >= 5, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 15 MINUTE), NULL), atualizado_em = UTC_TIMESTAMP() WHERE chave = ?')->execute([$failures, $failures, $key]);
            $pdo->commit();
            return false;
        }
        $hash = $user['senha_hash'];
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare('UPDATE admin_users SET senha_hash = ? WHERE id = ?')->execute([$hash, $user['id']]);
        }
        $pdo->prepare('DELETE FROM login_tentativas WHERE chave = ?')->execute([$key]);
        $pdo->prepare('UPDATE admin_users SET ultimo_login = UTC_TIMESTAMP() WHERE id = ?')->execute([$user['id']]);
        $pdo->commit();
        session_regenerate_id(true);
        $_SESSION = ['admin_id' => (int) $user['id'], 'auth_signature' => hash('sha256', $hash), 'last_activity' => time(), 'csrf' => bin2hex(random_bytes(32))];
        return true;
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $error;
    }
}
