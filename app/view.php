<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

function panel_start(string $title, ?array $user = null, bool $setup = false, ?array $content = null): void
{
    $prefix = $setup ? 'admin/' : '';
    ?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($title) ?> — Codora Tech</title>
<script src="<?= e($prefix) ?>theme-init.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e($prefix) ?>admin.css?v=<?= substr(hash_file('sha256', SITE_ROOT . '/admin/admin.css'), 0, 12) ?>"><link rel="stylesheet" href="<?= e($prefix) ?>editor.css"><script src="<?= e($prefix) ?>admin.js" defer></script></head><body class="panel-body">
<header class="panel-header"><a class="brand font-display" href="<?= $setup ? 'admin/editor.php' : 'editor.php' ?>"><span class="admin-logo"><img class="logo-light" src="<?= $setup ? '' : '../' ?>assets/img/brand/logo-claro.png?v=<?= filemtime(SITE_ROOT . '/assets/img/brand/logo-claro.png') ?>" alt="Codora Tech"><img class="logo-dark" src="<?= $setup ? '' : '../' ?>assets/img/brand/logo-escuro.png?v=<?= filemtime(SITE_ROOT . '/assets/img/brand/logo-escuro.png') ?>" alt="Codora Tech"></span></a><nav aria-label="Administração">
<?php if ($user): ?><button type="button" id="search-open" class="button-secondary search-open" aria-label="Buscar textos do site" aria-haspopup="dialog" title="Buscar (Ctrl+K)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="10.5" cy="10.5" r="7.5"/><path d="m16 16 5 5"/></svg><span>Buscar</span></button><a href="senha.php">Trocar senha</a><a href="../index.php" target="_blank" rel="noopener">Ver site</a><form method="post" action="logout.php"><?= csrf_field() ?><button class="button-secondary" type="submit">Sair</button></form><?php endif; ?>
<button type="button" id="theme-toggle" class="theme-toggle" aria-label="Alternar tema"><span class="knob"><span aria-hidden="true">◐</span></span></button></nav></header>
<?php if ($user):
    require_once __DIR__ . '/admin-search.php';
    try { $searchIndex = admin_search_index($content ?? site_content(false)); } catch (Throwable) { $searchIndex = null; }
?>
<dialog id="site-search" aria-labelledby="search-title">
<div class="search-heading"><h2 id="search-title">Buscar no site</h2><button type="button" id="search-close" class="button-secondary" aria-label="Fechar busca">Fechar</button></div>
<label for="site-search-input">Texto, nome do campo ou chave · Português e English</label>
<input type="search" id="site-search-input" placeholder="Ex.: Projetos que já entregamos" autocomplete="off" role="combobox" aria-expanded="false" aria-controls="site-search-results" aria-autocomplete="list">
<p id="site-search-status" role="status">Digite pelo menos 2 caracteres.</p>
<div id="site-search-results" role="listbox" aria-label="Resultados da busca"></div>
<p class="hint">↑ ↓ selecionam · Enter abre · Esc fecha · Ctrl+K abre</p>
</dialog>
<script type="application/json" id="site-search-data" nonce="<?= e(nonce()) ?>"><?= json_encode($searchIndex, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) ?></script>
<link rel="stylesheet" href="search.css"><script src="search.js" defer></script>
<?php endif; ?>
<main class="panel-main"><div class="page-heading"><p class="font-mono text-muted-c"><?= $user ? e($user['nome']) : 'Configuração inicial' ?></p><h1 class="font-display"><?= e($title) ?></h1></div>
    <?php
}

function panel_end(): void
{
    echo '</main><footer class="panel-footer">Codora Tech · Painel Administrativo</footer></body></html>';
}

function notice(string $message, bool $error = false): void
{
    if ($message !== '') echo '<p class="notice' . ($error ? ' error' : '') . '" role="' . ($error ? 'alert' : 'status') . '">' . e($message) . '</p>';
}
