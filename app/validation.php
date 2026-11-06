<?php
declare(strict_types=1);

function plain(mixed $value, string $label, int $max = 5000, bool $required = false): string
{
    if (!is_string($value) || !preg_match('//u', $value)) throw new InvalidArgumentException($label . ': texto inválido.');
    $value = trim(str_replace("\r\n", "\n", $value));
    $length = function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    if ($length > $max || ($required && $value === '')) throw new InvalidArgumentException($label . ': preencha até ' . $max . ' caracteres.');
    if (preg_match('/[<>\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', $value)) throw new InvalidArgumentException($label . ': use apenas texto, sem HTML.');
    return $value;
}

function email_value(mixed $value): string
{
    $email = strtolower(plain($value, 'E-mail', 190, true));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('Informe um e-mail válido.');
    return $email;
}

function http_url(mixed $value): string
{
    $url = plain($value, 'URL', 1000);
    if ($url === '') return '';
    $parts = parse_url($url);
    if (!filter_var($url, FILTER_VALIDATE_URL) || !is_array($parts) || !in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true) || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) {
        throw new InvalidArgumentException('Os links devem ser URLs http/https válidas, sem credenciais.');
    }
    return $url;
}

function password_value(mixed $value): string
{
    if (!is_string($value) || strlen($value) < 12 || strlen($value) > 72 || str_contains($value, "\0")) {
        throw new InvalidArgumentException('A senha deve ter entre 12 e 72 bytes (acentos podem ocupar mais de um byte).');
    }
    return $value;
}
