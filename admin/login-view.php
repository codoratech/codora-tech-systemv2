<?php if (!defined('CODORA_LOGIN_VIEW')) { http_response_code(404); exit; } ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login — Painel Administrativo | Codora Tech</title>
<meta name="description" content="Acesso ao painel administrativo da Codora Tech." />

<script nonce="<?= e(nonce()) ?>" src="theme-init.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<script nonce="<?= e(nonce()) ?>" src="https://cdn.tailwindcss.com"></script>
<script nonce="<?= e(nonce()) ?>">
  tailwind.config = { darkMode: 'class', theme: { extend: {
    fontFamily: { display: ['"Space Grotesk"', 'sans-serif'], body: ['Inter', 'sans-serif'], mono: ['"JetBrains Mono"', 'monospace'] }
  } } };
</script>

<link rel="stylesheet" href="admin.css?v=<?= filemtime(__DIR__ . '/admin.css') ?>">
</head>
<body class="antialiased">

<div class="min-h-screen flex flex-col lg:flex-row">

  <!-- ============ ESQUERDA: FORMULÁRIO ============ -->
  <main class="relative flex-1 flex items-center justify-center px-6 py-12 sm:px-10">
    <button type="button" id="theme-toggle" class="theme-toggle absolute top-6 right-6" aria-label="Alternar tema claro/escuro">
      <span class="knob">
        <svg id="theme-icon-sun" class="hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
        <svg id="theme-icon-moon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21 12.6A9 9 0 1111.4 3a7 7 0 009.6 9.6z"/></svg>
      </span>
    </button>

    <div class="w-full max-w-[380px]">
      <!-- Logotipo -->
      <div class="fade-in mb-1 logo-switch" style="animation-delay:.05s">
        <img class="logo logo-light" src="logo-expansiva-roxa.png" alt="Logo da empresa">
        <img class="logo logo-dark" src="logo-expansiva-branca.png" alt="Logo da empresa">
      </div>

      <h1 class="fade-in font-display font-semibold text-[1.7rem] tracking-tight mb-2.5" style="animation-delay:.1s">Acesse seu painel administrativo</h1>
      <p class="fade-in text-sm text-secondary-c leading-relaxed mb-9" style="animation-delay:.15s">
        Entre com suas credenciais para acessar o ambiente administrativo da Codora Tech.
      </p>

      <form id="login-form" class="fade-in space-y-5" style="animation-delay:.2s" method="post">
        <?= csrf_field() ?>
        <?php if ($error !== ''): ?><p role="alert" class="notice"><?= e($error) ?></p><?php endif; ?>
        <div>
          <label for="login-email" class="block text-xs font-medium mb-1.5">E-mail</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted-c pointer-events-none" aria-hidden="true">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2.5"/><path d="M2 6l10 7 10-7"/></svg>
            </span>
            <input id="login-email" name="email" type="email" required maxlength="190" value="<?= e($email) ?>" autocomplete="email" placeholder="Digite seu e-mail" class="w-full rounded-xl pl-11 pr-4 py-3 text-sm" />
          </div>
        </div>

        <div>
          <label for="login-password" class="block text-xs font-medium mb-1.5">Senha</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted-c pointer-events-none" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M7 10V7a5 5 0 0110 0v3"/></svg>
            </span>
            <input id="login-password" name="password" type="password" required maxlength="72" autocomplete="current-password" placeholder="Digite sua senha" class="w-full rounded-xl pl-11 pr-11 py-3 text-sm" />
            <button type="button" id="toggle-password" class="eye-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-c" aria-label="Mostrar senha" aria-pressed="false">
              <svg id="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg id="eye-closed" class="hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3l18 18M10.6 10.6a3 3 0 004.24 4.24M9.4 5.2A10.9 10.9 0 0112 5c7 0 10.5 7 10.5 7a15.6 15.6 0 01-3.1 4.1M6.4 6.4C3.6 8.1 1.5 12 1.5 12s3.5 7 10.5 7a10.7 10.7 0 004.9-1.1"/></svg>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-left pt-1">
          <span class="text-xs text-muted-c">Acesso seguro</span>
        </div>

        <button type="submit" id="login-btn" class="btn-login w-full rounded-xl py-3.5 font-semibold text-[15px] flex items-center justify-center gap-2 mt-2">
          <svg id="login-spinner" class="spinner hidden" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="9" opacity=".25"/><path d="M21 12a9 9 0 00-9-9"/></svg>
          <span id="login-btn-label">Entrar no painel</span>
        </button>
      </form>
      <p id="forgot-help" class="text-xs text-secondary-c mt-5">Esqueceu a senha? Fale com o administrador do sistema para recuperar o acesso.</p>
		<a class="block text-center text-sm text-secondary-c" href="../index.php">Voltar ao site</a>
      <p class="fade-in text-xs text-muted-c text-center mt-10" style="animation-delay:.25s">
        © 2026 Codora Tech. Todos os direitos reservados.<br>
        <span class="opacity-70">Painel Administrativo</span>
      </p>
    </div>
  </main>

  <!-- ============ DIREITA: VISUAL ============ -->
  <aside class="visual-panel relative hidden lg:flex lg:w-[46%] items-center justify-center overflow-hidden px-14" aria-hidden="true">
    <div class="absolute inset-0 grid-overlay"></div>
    <div class="glow-orb float-a w-72 h-72 -top-10 -left-16" style="background: var(--secondary); opacity:.35;"></div>
    <div class="glow-orb float-b w-64 h-64 bottom-0 right-[-60px]" style="background: #fff; opacity:.08;"></div>

    <svg class="absolute inset-0 w-full h-full opacity-60" viewBox="0 0 700 800" preserveAspectRatio="xMidYMid slice">
      <g stroke="#fff" stroke-width="1" opacity="0.25">
        <line class="line-draw" x1="120" y1="160" x2="330" y2="260"/>
        <line class="line-draw" x1="330" y1="260" x2="540" y2="190" style="animation-delay:.2s"/>
        <line class="line-draw" x1="330" y1="260" x2="380" y2="470" style="animation-delay:.3s"/>
        <line class="line-draw" x1="380" y1="470" x2="580" y2="520" style="animation-delay:.4s"/>
        <line class="line-draw" x1="120" y1="160" x2="100" y2="400" style="animation-delay:.15s"/>
      </g>
      <g fill="var(--secondary)">
        <circle class="node-pulse" cx="120" cy="160" r="3.5"/>
        <circle class="node-pulse" cx="330" cy="260" r="3.5" style="animation-delay:.5s"/>
        <circle class="node-pulse" cx="540" cy="190" r="3.5" style="animation-delay:1s"/>
        <circle class="node-pulse" cx="380" cy="470" r="3.5" style="animation-delay:.7s"/>
        <circle class="node-pulse" cx="580" cy="520" r="3.5" style="animation-delay:1.2s"/>
        <circle class="node-pulse" cx="100" cy="400" r="3.5" style="animation-delay:.3s"/>
      </g>
    </svg>

    <!-- motivo abstrato de cartão de interface -->
    <div class="fade-in relative z-10 w-full max-w-xs" style="animation-delay:.3s">
      <div class="rounded-2xl p-5 backdrop-blur-md" style="background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14);">
        <div class="flex items-center gap-1.5 mb-4">
          <span class="w-2.5 h-2.5 rounded-full" style="background:#ff5f57"></span>
          <span class="w-2.5 h-2.5 rounded-full" style="background:#febc2e"></span>
          <span class="w-2.5 h-2.5 rounded-full" style="background:#28c840"></span>
        </div>
        <div class="space-y-2.5">
          <div class="h-2.5 rounded-full" style="background: rgba(255,255,255,.28); width: 70%;"></div>
          <div class="h-2.5 rounded-full" style="background: rgba(255,255,255,.18); width: 90%;"></div>
          <div class="h-2.5 rounded-full" style="background: rgba(255,255,255,.18); width: 55%;"></div>
        </div>
        <div class="h-9 rounded-lg mt-5" style="background: linear-gradient(135deg, var(--secondary), #ffcc80);"></div>
      </div>
    </div>

    <div class="fade-in relative z-10 text-center mt-10 max-w-sm" style="animation-delay:.4s">
      <p class="font-display font-semibold text-2xl text-white leading-snug mb-3">
        "Soluções digitais pensadas para pessoas."
      </p>
      <p class="text-sm text-white/70 leading-relaxed">
        Tecnologia, inovação e soluções para transformar ideias em resultados.
      </p>
    </div>
  </aside>
</div>

<script nonce="<?= e(nonce()) ?>" src="admin.js" defer></script>
</body>
</html>
