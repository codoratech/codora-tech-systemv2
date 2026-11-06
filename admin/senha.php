<?php
require_once dirname(__DIR__) . '/app/auth.php';
require_once dirname(__DIR__) . '/app/view.php';
$user = require_admin();
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_csrf();
    try {
        $current = $_POST['atual'] ?? '';
        $password = password_value($_POST['nova'] ?? '');
        $confirmation = $_POST['confirmar'] ?? '';
        if (!is_string($current) || strlen($current) > 72 || str_contains($current, "\0")) throw new InvalidArgumentException('Senha atual inválida.');
        if (!is_string($confirmation) || !hash_equals($password, $confirmation)) throw new InvalidArgumentException('As novas senhas não coincidem.');
        if (hash_equals($current, $password)) throw new InvalidArgumentException('Escolha uma senha diferente da atual.');
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT senha_hash FROM admin_users WHERE id = ? FOR UPDATE');
            $stmt->execute([$user['id']]);
            $hash = $stmt->fetchColumn();
            if (!$hash || !password_verify($current, $hash)) throw new InvalidArgumentException('Senha atual inválida.');
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare('UPDATE admin_users SET senha_hash = ? WHERE id = ?')->execute([$newHash, $user['id']]);
            log_change($pdo, (int) $user['id'], 'senha', ['senha alterada']);
            $pdo->commit();
        } finally {
            if ($pdo->inTransaction()) $pdo->rollBack();
        }
        session_regenerate_id(true);
        $_SESSION['auth_signature'] = hash('sha256', $newHash);
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        $_SESSION['notice'] = 'Senha alterada. As outras sessões desta conta foram encerradas.';
        redirect('senha.php');
    } catch (InvalidArgumentException $exception) {
        $error = $exception->getMessage();
    } catch (Throwable $exception) {
        error_log('Codora senha: ' . $exception->getMessage());
        http_response_code(503);
        $error = 'Não foi possível alterar a senha. Tente novamente.';
    }
}
panel_start('Trocar minha senha', $user);
notice($_SESSION['notice'] ?? ''); unset($_SESSION['notice']); notice($error, true);
?>
<form method="post" class="editor-card narrow fields-stack"><?= csrf_field() ?>
<label>Senha atual<input type="password" name="atual" required maxlength="72" autocomplete="current-password"></label>
<label>Nova senha (12 a 72 bytes)<input type="password" name="nova" required minlength="12" maxlength="72" autocomplete="new-password"></label>
<label>Confirmar nova senha<input type="password" name="confirmar" required minlength="12" maxlength="72" autocomplete="new-password"></label>
<button type="submit" class="button-primary">Alterar senha</button></form>
<?php panel_end(); ?>
