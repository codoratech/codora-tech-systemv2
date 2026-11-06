<?php
declare(strict_types=1);
require_once __DIR__ . '/content.php';

function delete_upload(string $path): void
{
    // Nunca remover os assets originais nem aceitar caminhos enviados pelo navegador.
    if (preg_match('~^uploads/[a-f0-9]{32}\.(jpg|png|webp)$~D', $path)) {
        $absolute = SITE_ROOT . '/' . $path;
        if (is_file($absolute) && !unlink($absolute)) error_log('Codora: não foi possível remover upload antigo.');
    }
}

function receive_image(string $field): ?string
{
    if (!isset($_FILES[$field])) return null;
    $file = $_FILES[$field];
    if (!is_array($file) || !isset($file['error']) || !is_int($file['error'])) throw new InvalidArgumentException('Upload inválido.');
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) throw new InvalidArgumentException('Falha no upload. Use uma imagem de até 3 MB.');
    if (!is_string($file['tmp_name'] ?? null) || !is_uploaded_file($file['tmp_name'])) throw new InvalidArgumentException('Upload inválido.');
    $size = filesize($file['tmp_name']);
    if ($size === false || $size < 1 || $size > 3 * 1024 * 1024) throw new InvalidArgumentException('A imagem deve ter até 3 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = ['image/jpeg' => ['jpg', 'imagecreatefromjpeg'], 'image/png' => ['png', 'imagecreatefrompng'], 'image/webp' => ['webp', 'imagecreatefromwebp']];
    if (!isset($types[$mime])) throw new InvalidArgumentException('Envie apenas JPG, PNG ou WEBP.');
    if (!extension_loaded('gd') || !function_exists($types[$mime][1])) throw new InvalidArgumentException('Uploads indisponíveis: habilite GD com suporte JPG, PNG e WEBP na hospedagem.');
    $info = @getimagesize($file['tmp_name']);
    if (!$info || $info[0] < 1 || $info[1] < 1 || $info[0] > 6000 || $info[1] > 6000 || $info[0] * $info[1] > 8000000 || $info['mime'] !== $mime) {
        throw new InvalidArgumentException('Imagem inválida ou muito grande: máximo 6000 px por lado e 8 megapixels.');
    }
    // Uma imagem comprimida pequena pode consumir muita memória ao ser decodificada.
    // Recusar antes do GD evita esgotar o processo PHP em hospedagens com limites baixos.
    $ratio = min(1, 1600 / max($info[0], $info[1]));
    $width = max(1, (int) round($info[0] * $ratio));
    $height = max(1, (int) round($info[1] * $ratio));
    $limit = trim((string) ini_get('memory_limit'));
    if ($limit !== '-1') {
        $unit = strtolower(substr($limit, -1));
        $bytes = (int) $limit * match ($unit) { 'g' => 1073741824, 'm' => 1048576, 'k' => 1024, default => 1 };
        $estimate = $info[0] * $info[1] * 12 + $width * $height * 8 + 16 * 1024 * 1024;
        if ($bytes > 0 && memory_get_usage(true) + $estimate > $bytes) {
            throw new InvalidArgumentException('Esta imagem exige mais memória do que a hospedagem permite. Reduza as dimensões e tente novamente.');
        }
    }
    $source = null;
    $target = null;
    $newPath = null;
    try {
        $source = @$types[$mime][1]($file['tmp_name']);
        if (!$source) throw new InvalidArgumentException('Não foi possível decodificar a imagem.');
        $target = imagecreatetruecolor($width, $height);
        if ($mime !== 'image/jpeg') {
            imagealphablending($target, false);
            imagesavealpha($target, true);
            imagefill($target, 0, 0, imagecolorallocatealpha($target, 0, 0, 0, 127));
        }
        if (!imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $info[0], $info[1])) throw new RuntimeException('Falha ao redimensionar imagem.');
        $newPath = 'uploads/' . bin2hex(random_bytes(16)) . '.' . $types[$mime][0];
        $absolute = SITE_ROOT . '/' . $newPath;
        if (!is_dir(SITE_ROOT . '/uploads') || !is_writable(SITE_ROOT . '/uploads')) throw new InvalidArgumentException('A pasta uploads precisa permitir gravação pelo PHP.');
        $written = match ($mime) {
            'image/jpeg' => imagejpeg($target, $absolute, 85),
            'image/png' => imagepng($target, $absolute, 6),
            'image/webp' => imagewebp($target, $absolute, 85),
        };
        if (!$written || !is_file($absolute) || filesize($absolute) === 0) throw new RuntimeException('Falha ao salvar imagem.');
        return $newPath;
    } catch (Throwable $error) {
        if ($newPath !== null) delete_upload($newPath);
        throw $error;
    } finally {
        if ($source) imagedestroy($source);
        if ($target) imagedestroy($target);
    }
}
