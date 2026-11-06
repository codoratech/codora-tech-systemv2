<?php
require_once dirname(__DIR__) . '/app/auth.php';
session_start_secure();
security_headers(true);
$error = '';
$email = '';
if (!empty($_SESSION['admin_id']) && ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    require_admin();
    redirect('editor.php');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_csrf();
    try {
        // Mesmo entradas inválidas participam do limite por IP + e-mail.
        $rawEmail = $_POST['email'] ?? '';
        $email = is_string($rawEmail) ? strtolower(trim(substr($rawEmail, 0, 190))) : '';
        $password = $_POST['password'] ?? '';
        $password = is_string($password) && strlen($password) <= 72 && !str_contains($password, "\0") ? $password : '';
        try {
            $loginEmail = email_value($rawEmail);
        } catch (InvalidArgumentException) {
            $loginEmail = 'invalid-' . hash('sha256', is_string($rawEmail) ? $rawEmail : '') . '@invalid.local';
            $password = '';
        }
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (attempt_login($loginEmail, $password, $ip)) redirect('editor.php');
        $error = 'E-mail ou senha inválidos';
    } catch (Throwable $exception) {
        error_log('Codora login: ' . $exception->getMessage());
        http_response_code(503);
        $error = 'Acesso temporariamente indisponível. Verifique a instalação ou tente novamente mais tarde.';
    }
}
if (isset($_GET['expired']) && $error === '') $error = 'Sua sessão expirou por inatividade. Entre novamente.';
define('CODORA_LOGIN_VIEW', true);
require __DIR__ . '/login-view.php';
