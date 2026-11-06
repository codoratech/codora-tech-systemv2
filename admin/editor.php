<?php
require_once dirname(__DIR__) . '/app/auth.php';
require_once dirname(__DIR__) . '/app/editor-actions.php';
require_once dirname(__DIR__) . '/app/view.php';
$user = require_admin();
$section = $_GET['secao'] ?? 'hero';
if (!is_string($section) || !isset(sections()[$section])) $section = 'hero';
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_csrf();
    try {
        $postedSection = $_POST['secao'] ?? '';
        if (!is_string($postedSection) || !isset(sections()[$postedSection])) throw new InvalidArgumentException('Seção inválida.');
        $section = $postedSection;
        $action = $_POST['acao'] ?? '';
        if ($action === 'textos') save_texts(db(), $user, $section);
        elseif (in_array($action, ['equipe', 'portfolio', 'destaque'], true) && $action === $section) save_media(db(), $user, $action);
        else throw new InvalidArgumentException('Ação inválida.');
        $_SESSION['notice'] = 'Alterações salvas. O site já exibe o novo conteúdo.';
        redirect('editor.php?secao=' . rawurlencode($section));
    } catch (InvalidArgumentException $exception) {
        $error = $exception->getMessage();
    } catch (Throwable $exception) {
        error_log('Codora editor: ' . $exception->getMessage());
        http_response_code(503);
        $error = 'Não foi possível salvar. Nenhuma alteração desta solicitação foi aplicada. Tente novamente.';
    }
}
try {
    $content = site_content(false);
} catch (Throwable) {
    http_response_code(503);
    panel_start('Editar site', $user);
    notice('O banco está indisponível. Tente novamente mais tarde.', true);
    panel_end();
    exit;
}
$dict = $content['dict'];
// Preservar os textos digitados se uma validação falhar, sem aplicá-los no site.
if ($error !== '' && ($_POST['acao'] ?? '') === 'textos' && is_array($_POST['textos'] ?? null)) {
    foreach (section_keys($section) as $key) foreach (['pt', 'en'] as $lang) {
        $submitted = $_POST['textos'][$key][$lang] ?? null;
        if (is_string($submitted)) $dict[$lang][$key] = substr($submitted, 0, 20000);
    }
}
panel_start('Editar site', $user, false, $content);
notice($_SESSION['notice'] ?? '');
unset($_SESSION['notice']);
notice($error, true);
?>
<nav class="tabs" aria-label="Seções do site"><?php foreach (sections() as $slug => $label): ?><a href="?secao=<?= e($slug) ?>" <?= $section === $slug ? 'aria-current="page"' : '' ?>><?= e($label) ?></a><?php endforeach; ?></nav>
<?php if ($section === 'formulario'): ?><p class="hint">Estas mensagens existem no dicionário original. O HTML atual usa WhatsApp e não contém um formulário de orçamento.</p><?php endif; ?>
<form method="post" class="editor-card">
<?= csrf_field() ?><input type="hidden" name="acao" value="textos"><input type="hidden" name="secao" value="<?= e($section) ?>">
<h2><?= e(sections()[$section]) ?></h2><p class="hint">Os campos aceitam texto puro. Salve os textos desta seção no botão abaixo.</p>
<?php foreach (section_keys($section) as $key): ?>
<?php $projectId = $section === 'portfolio' && preg_match('/^pf([1-6])/', $key, $match) ? (int) $match[1] : null; ?>
<?php if ($projectId && str_ends_with($key, 'Name2')): $active = (bool) $content['portfolio'][$projectId]['ativo']; ?>
<div class="project-visibility" data-project="<?= $projectId ?>" data-active="<?= $active ? '1' : '0' ?>">
<strong>Projeto <?= $projectId ?> — Visibilidade no site</strong>
<button type="button" role="switch" aria-checked="<?= $active ? 'true' : 'false' ?>" aria-label="Exibir projeto <?= $projectId ?> no site" data-project-toggle="<?= $projectId ?>"><span class="project-switch" aria-hidden="true"></span><span class="project-status"><?= $active ? 'Ativo' : 'Inativo' ?></span></button>
</div>
<?php endif; ?>
<div class="field-row"<?= $projectId ? ' data-project-fields="' . $projectId . '" data-active="' . (int) $content['portfolio'][$projectId]['ativo'] . '"' : '' ?>><p class="field-title"><?= e(key_label($key)) ?><small><?= e($key) ?></small></p>
<div class="bilingual"><?php foreach (['pt' => 'Português', 'en' => 'English'] as $lang => $label): ?>
<label for="<?= e($key . '-' . $lang) ?>"><?= e($label) ?>
<?php if (in_array($key, ['contactWhatsapp', 'contactEmail'], true)): ?>
<input id="<?= e($key . '-' . $lang) ?>" type="<?= $key === 'contactEmail' ? 'email' : 'text' ?>" name="textos[<?= e($key) ?>][<?= e($lang) ?>]" value="<?= e($dict[$lang][$key]) ?>" maxlength="<?= $key === 'contactEmail' ? '190' : '15' ?>" <?= $key === 'contactWhatsapp' ? 'inputmode="numeric" pattern="[1-9][0-9]{7,14}"' : '' ?> required>
<?php else: ?>
<textarea id="<?= e($key . '-' . $lang) ?>" name="textos[<?= e($key) ?>][<?= e($lang) ?>]" rows="<?= strlen($dict[$lang][$key]) > 200 ? '4' : '2' ?>" maxlength="5000"><?= e($dict[$lang][$key]) ?></textarea>
<?php endif; ?></label>
<?php endforeach; ?></div></div>
<?php endforeach; ?>
<div class="save-row"><button class="button-primary" type="submit">Salvar textos desta seção</button><span class="hint"><?= $section === 'contato' ? 'WhatsApp e e-mail devem ser iguais em PT e EN.' : 'Os outros grupos de campos possuem seus próprios botões de salvar.' ?></span></div>
</form>
<?php if ($section === 'equipe'): ?>
<h2 class="font-display">Fotos e redes sociais</h2><p class="hint">Nomes, cargos e descrições são editados nos campos acima.</p><div class="media-grid">
<?php foreach ($content['team'] as $id => $member): ?>
<form class="editor-card fields-stack" method="post" enctype="multipart/form-data">
<?= csrf_field() ?><input type="hidden" name="acao" value="equipe"><input type="hidden" name="secao" value="equipe"><input type="hidden" name="id" value="<?= e((string) $id) ?>">
<h2><?= e($dict['pt']['team' . $id . 'Name']) ?></h2><img class="preview" src="../<?= e(image_path($member['foto'])) ?>" alt="Foto atual">
<label>Substituir foto (JPG, PNG ou WEBP; até 3 MB)<input type="file" id="team-media-<?= e((string) $id) ?>-foto" name="imagem" accept="image/jpeg,image/png,image/webp"></label>
<label>Instagram<input type="url" id="team-media-<?= e((string) $id) ?>-instagram" name="instagram" maxlength="1000" value="<?= e($member['instagram']) ?>" placeholder="https://www.instagram.com/..."></label>
<label>LinkedIn<input type="url" id="team-media-<?= e((string) $id) ?>-linkedin" name="linkedin" maxlength="1000" value="<?= e($member['linkedin']) ?>" placeholder="https://www.linkedin.com/in/..."></label>
<button class="button-primary" type="submit">Salvar foto e redes do membro <?= e((string) $id) ?></button></form>
<?php endforeach; ?></div>
<?php elseif ($section === 'portfolio'): ?>
<h2 class="font-display">Imagens e filtros</h2><p class="hint">A categoria exibida no cartão é editada nos textos acima; o filtro correspondente é escolhido abaixo. Os seis cartões atuais são mantidos.</p><div class="media-grid">
<?php foreach ($content['portfolio'] as $id => $project): ?>
<form class="editor-card fields-stack" method="post" enctype="multipart/form-data">
<?= csrf_field() ?><input type="hidden" name="acao" value="portfolio"><input type="hidden" name="secao" value="portfolio"><input type="hidden" name="id" value="<?= e((string) $id) ?>">
<h2><?= e($dict['pt']['pf' . $id . 'Name']) ?></h2>
<?php if ($project['imagem'] !== ''): ?><img class="preview" src="../<?= e(image_path($project['imagem'])) ?>" alt="Imagem atual"><?php else: ?><p class="hint">Sem imagem: o site exibe a capa com gradiente original.</p><?php endif; ?>
<label>Filtro de categoria<select id="portfolio-media-<?= e((string) $id) ?>-categoria" name="categoria"><?php foreach (['site' => 'Sites', 'sistema' => 'Sistemas', 'landing' => 'Landing Pages', 'marca' => 'Identidade de Marca'] as $slug => $label): ?><option value="<?= e($slug) ?>" <?= $project['categoria'] === $slug ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
<label>Substituir imagem (até 3 MB)<input type="file" id="portfolio-media-<?= e((string) $id) ?>-imagem" name="imagem" accept="image/jpeg,image/png,image/webp"></label>
<button class="button-primary" type="submit">Salvar imagem e filtro do projeto <?= e((string) $id) ?></button></form>
<?php endforeach; ?></div>
<?php elseif ($section === 'destaque'): $image = $content['portfolio'][1]['imagem_destaque']; ?>
<form class="editor-card narrow fields-stack" method="post" enctype="multipart/form-data">
<?= csrf_field() ?><input type="hidden" name="acao" value="destaque"><input type="hidden" name="secao" value="destaque"><input type="hidden" name="id" value="1">
<h2>Imagem do projeto em destaque</h2>
<?php if ($image !== ''): ?><img class="preview" src="../<?= e(image_path($image)) ?>" alt="Imagem do destaque"><?php endif; ?>
<label>Substituir imagem (até 3 MB)<input type="file" id="featured-image" name="imagem" accept="image/jpeg,image/png,image/webp"></label>
<button class="button-primary" type="submit">Salvar imagem do destaque</button></form>
<?php endif; ?>
<?php if ($section === 'portfolio'): ?><p id="portfolio-status-notice" class="portfolio-status-notice" role="status" aria-live="polite"></p><script src="portfolio-status.js" defer></script><?php endif; ?>
<?php panel_end(); ?>
