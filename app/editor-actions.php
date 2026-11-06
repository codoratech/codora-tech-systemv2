<?php
declare(strict_types=1);
require_once __DIR__ . '/uploads.php';

function save_texts(PDO $pdo, array $user, string $section): void
{
    $keys = section_keys($section);
    $submitted = $_POST['textos'] ?? null;
    if (!is_array($submitted) || array_diff($keys, array_keys($submitted)) || array_diff(array_keys($submitted), $keys)) throw new InvalidArgumentException('FormulÃ¡rio incompleto. Recarregue a pÃ¡gina antes de salvar.');
    $values = [];
    foreach ($keys as $key) {
        $input = $submitted[$key];
        if (!is_array($input)) throw new InvalidArgumentException('Campo invÃ¡lido.');
        $values[$key] = [validate_content($key, $input['pt'] ?? null), validate_content($key, $input['en'] ?? null)];
        if (in_array($key, ['contactEmail', 'contactWhatsapp'], true) && $values[$key][0] !== $values[$key][1]) throw new InvalidArgumentException('E-mail e WhatsApp devem ser iguais nos dois idiomas.');
    }
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT chave, texto_pt, texto_en FROM site_conteudo WHERE secao = ? FOR UPDATE');
        $stmt->execute([$section]);
        $old = [];
        foreach ($stmt->fetchAll() as $row) $old[$row['chave']] = [$row['texto_pt'], $row['texto_en']];
        $update = $pdo->prepare('UPDATE site_conteudo SET texto_pt = ?, texto_en = ?, atualizado_em = UTC_TIMESTAMP() WHERE chave = ? AND secao = ?');
        $changed = [];
        foreach ($values as $key => $pair) {
            if (!isset($old[$key])) throw new RuntimeException('ConteÃºdo inicial incompleto.');
            if ($old[$key] !== $pair) {
                $update->execute([$pair[0], $pair[1], $key, $section]);
                $changed[] = $key;
            }
        }
        if ($changed) log_change($pdo, (int) $user['id'], $section, $changed);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $error;
    }
}

function save_media(PDO $pdo, array $user, string $action): void
{
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $isTeam = $action === 'equipe';
    $isFeatured = $action === 'destaque';
    if (!$id || ($isTeam && !isset(default_team()[$id])) || (!$isTeam && !isset(default_portfolio()[$id])) || ($isFeatured && $id !== 1)) throw new InvalidArgumentException('Registro invÃ¡lido.');
    $instagram = $isTeam ? http_url($_POST['instagram'] ?? null) : '';
    $linkedin = $isTeam ? http_url($_POST['linkedin'] ?? null) : '';
    $category = (!$isTeam && !$isFeatured) ? plain($_POST['categoria'] ?? null, 'Categoria', 20, true) : '';
    if (!$isTeam && !$isFeatured && !in_array($category, ['site', 'sistema', 'landing', 'marca'], true)) throw new InvalidArgumentException('Categoria invÃ¡lida.');
    $newImage = receive_image('imagem');
    $oldImage = '';
    $committed = false;
    try {
        $pdo->beginTransaction();
        if ($isTeam) {
            $stmt = $pdo->prepare('SELECT foto, instagram, linkedin FROM equipe WHERE id = ? FOR UPDATE');
        } else {
            $stmt = $pdo->prepare('SELECT categoria, imagem, imagem_destaque FROM portfolio WHERE id = ? FOR UPDATE');
        }
        $stmt->execute([$id]);
        $old = $stmt->fetch();
        if (!$old) throw new InvalidArgumentException('Registro inexistente.');
        $column = $isTeam ? 'foto' : ($isFeatured ? 'imagem_destaque' : 'imagem');
        $oldImage = $old[$column];
        $image = $newImage ?? $oldImage;
        $changed = [];
        if ($newImage !== null) $changed[] = $column;
        if ($isTeam) {
            foreach (['instagram' => $instagram, 'linkedin' => $linkedin] as $key => $value) if ($old[$key] !== $value) $changed[] = $key;
            $pdo->prepare('UPDATE equipe SET foto = ?, instagram = ?, linkedin = ?, atualizado_em = UTC_TIMESTAMP() WHERE id = ?')->execute([$image, $instagram, $linkedin, $id]);
        } elseif ($isFeatured) {
            $pdo->prepare('UPDATE portfolio SET imagem_destaque = ?, atualizado_em = UTC_TIMESTAMP() WHERE id = ?')->execute([$image, $id]);
        } else {
            if ($old['categoria'] !== $category) $changed[] = 'categoria';
            $pdo->prepare('UPDATE portfolio SET categoria = ?, imagem = ?, atualizado_em = UTC_TIMESTAMP() WHERE id = ?')->execute([$category, $image, $id]);
        }
        if ($changed) log_change($pdo, (int) $user['id'], $action . ':' . $id, $changed);
        $pdo->commit();
        $committed = true;
    } finally {
        if ($pdo->inTransaction()) $pdo->rollBack();
        if (!$committed && $newImage !== null) delete_upload($newImage);
    }
    if ($newImage !== null && $oldImage !== $newImage) delete_upload($oldImage);
}

function save_portfolio_status(PDO $pdo, array $user): array
{
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $value = $_POST['ativo'] ?? null;
    if (!$id || !isset(default_portfolio()[$id]) || !in_array($value, ['0', '1'], true)) throw new InvalidArgumentException('Projeto ou status inválido.');
    require_once __DIR__ . '/schema.php';
    migrate_portfolio_status($pdo);
    $pdo->beginTransaction();
    try {
        // Lock every project in the same order to protect the last active project.
        $rows = $pdo->query('SELECT id, ativo FROM portfolio ORDER BY id FOR UPDATE')->fetchAll();
        $states = array_column($rows, 'ativo', 'id');
        if (!isset($states[$id])) throw new InvalidArgumentException('Projeto inexistente.');
        if ($value === '0' && (int) $states[$id] === 1 && array_sum($states) <= 1) throw new InvalidArgumentException('Mantenha pelo menos um projeto ativo no portfólio.');
        if ((int) $states[$id] !== (int) $value) {
            $pdo->prepare('UPDATE portfolio SET ativo = ?, atualizado_em = UTC_TIMESTAMP() WHERE id = ?')->execute([(int) $value, $id]);
            log_change($pdo, (int) $user['id'], 'portfolio:' . $id, ['projeto' => $id, 'acao' => $value === '1' ? 'ativar' : 'inativar', 'anterior' => (int) $states[$id], 'ativo' => (int) $value]);
        }
        $pdo->commit();
        return ['id' => $id, 'ativo' => (int) $value];
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $error;
    }
}
