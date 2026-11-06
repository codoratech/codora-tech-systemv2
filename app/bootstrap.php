<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');
define('SITE_ROOT', dirname(__DIR__));

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function nonce(): string
{
    static $nonce;
    return $nonce ??= base64_encode(random_bytes(24));
}

function security_headers(bool $private = false): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Type: text/html; charset=UTF-8');
    // Tailwind CDN compila CSS no navegador e injeta estilos; manter o CDN exige estas permissões.
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-" . nonce() . "' https://cdn.tailwindcss.com 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://flagcdn.com; connect-src 'self' https://cdn.tailwindcss.com; object-src 'none'; base-uri 'self'; frame-ancestors 'self'; form-action 'self'");
    if ($private) {
        header('Cache-Control: no-store, private');
        header('X-Robots-Tag: noindex, nofollow');
    }
}

function config(): array
{
    static $config;
    if ($config !== null) return $config;
    $outside = dirname(SITE_ROOT) . '/codora-config.php';
    $path = getenv('CODORA_CONFIG_PATH') ?: (is_file($outside) ? $outside : SITE_ROOT . '/config.php');
    if (!is_file($path)) throw new RuntimeException('Configuração do banco ausente.');
    $loaded = require $path;
    if (!is_array($loaded)) throw new RuntimeException('Configuração inválida.');
    foreach (['db_host', 'db_name', 'db_user', 'db_password'] as $key) {
        if (!isset($loaded[$key]) || !is_string($loaded[$key])) throw new RuntimeException('Configuração incompleta.');
        if (in_array($loaded[$key], ['SEU_HOST', 'SEU_BANCO', 'SEU_USUARIO', 'SUA_SENHA'], true)) {
            throw new RuntimeException('Substitua os placeholders da configuração antes de instalar.');
        }
    }
    if (!preg_match('/^[a-zA-Z0-9_.-]+$/D', $loaded['db_name']) || preg_match('/[;\x00-\x20]/', $loaded['db_host'])) {
        throw new RuntimeException('Host ou banco inválido.');
    }
    return $config = $loaded;
}

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    $c = config();
    $port = (int) ($c['db_port'] ?? 3306);
    if ($port < 1 || $port > 65535) throw new RuntimeException('Porta inválida.');
    $pdo = new PDO('mysql:host=' . $c['db_host'] . ';port=' . $port . ';dbname=' . $c['db_name'] . ';charset=utf8mb4', $c['db_user'], $c['db_password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 4,
    ]);
    $pdo->prepare("SET time_zone = '+00:00'")->execute();
    return $pdo;
}

function redirect(string $url): never
{
    header('Location: ' . $url, true, 303);
    exit;
}

function session_start_secure(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) return;
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('codora_admin');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') == 443),
    ]);
    session_start();
}

function destroy_session(): void
{
    $_SESSION = [];
    $p = session_get_cookie_params();
    setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => $p['path'], 'secure' => $p['secure'], 'httponly' => true, 'samesite' => 'Lax']);
    session_destroy();
}

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function require_csrf(): void
{
    $value = $_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($value) || !isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $value)) {
        http_response_code(403);
        exit('Requisição inválida. Recarregue a página e tente novamente.');
    }
}

function require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Allow: POST');
        http_response_code(405);
        exit('Utilize o formulário para esta ação.');
    }
    require_csrf();
}

function log_change(PDO $pdo, int $user, string $section, array $keys = []): void
{
    $pdo->prepare('INSERT INTO alteracoes_log (usuario_id, secao, detalhes) VALUES (?, ?, ?)')->execute([$user, $section, json_encode($keys, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)]);
}
