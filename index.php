<?php
require_once __DIR__ . '/app/content.php';
security_headers();
header('Cache-Control: no-store, max-age=0');
$content = site_content();
$dict = $content['dict'];
$team = $content['team'];
$portfolio = $content['portfolio'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Codora Tech — Soluções digitais desenhadas para pessoas</title>
  <meta name="description"
    content="Codora Tech desenvolve software sob medida, sites profissionais, design digital e automação de processos para empresas que querem crescer." />

  <script nonce="<?= e(nonce()) ?>" src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="landing.css">
  <script nonce="<?= e(nonce()) ?>">window.CODORA_I18N = <?= json_encode($dict, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) ?>;</script>
  <script nonce="<?= e(nonce()) ?>" src="landing.js" defer></script>
</head>

<body class="bg-surface text-primary-c font-body antialiased">
  <a href="#main-content" class="skip-link font-medium" data-i18n="skipLink"><?= e($dict['pt']['skipLink']) ?></a>

  <!-- ============ NAVEGAÇÃO ============ -->
  <header id="site-nav" class="fixed top-0 inset-x-0 z-50">
    <nav class="max-w-7xl mx-auto px-5 md:px-8 h-[72px] flex items-center justify-between"
      aria-label="Navegação principal">
      <a href="#home">
        <img src="assets/img/brand/logoBranca.png" alt="Logo Codora Tech" class="h-8 md:h-10 w-auto object-contain">
      </a>

      

      <ul class="hidden lg:flex items-center gap-8 font-medium text-sm">
        <li><a href="#home" class="nav-link active py-1" data-i18n="navHome"><?= e($dict['pt']['navHome']) ?></a></li>
        <li><a href="#about" class="nav-link py-1" data-i18n="navAbout"><?= e($dict['pt']['navAbout']) ?></a></li>
        <li><a href="#team" class="nav-link py-1" data-i18n="navTeam"><?= e($dict['pt']['navTeam']) ?></a></li>
        <li><a href="#services" class="nav-link py-1" data-i18n="navServices"><?= e($dict['pt']['navServices']) ?></a></li>
        <li><a href="#portfolio" class="nav-link py-1" data-i18n="navPortfolio"><?= e($dict['pt']['navPortfolio']) ?></a></li>
        <li><a href="#contact" class="nav-link py-1" data-i18n="navContact"><?= e($dict['pt']['navContact']) ?></a></li>
        <li><a href="login.php" class="nav-link py-1" data-i18n="navLogin" id="open-login-modal"><?= e($dict['pt']['navLogin']) ?></a></li>
      </ul>

      <div class="flex items-center gap-3">
        <!-- Seletor de idioma -->
        <div class="hidden sm:flex items-center gap-1 p-1 rounded-full border border-c" role="group"
          aria-label="Selecionar idioma">

          <button type="button" data-lang-btn="pt"
            class="lang-select-btn aria-pressed:font-semibold flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-medium border border-transparent"
            aria-pressed="true">

            <img src="https://flagcdn.com/w20/br.png" srcset="https://flagcdn.com/w40/br.png 2x" width="20"
              alt="Bandeira do Brasil" class="rounded-sm">

          </button>



          <button type="button" data-lang-btn="en"
            class="lang-select-btn flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-medium border border-transparent"
            aria-pressed="false">

            <img src="https://flagcdn.com/w20/us.png" srcset="https://flagcdn.com/w40/us.png 2x" width="20"
              alt="Bandeira dos Estados Unidos" class="rounded-sm">

          </button>

        </div>

        <!-- Alternador de tema -->
        <button type="button" id="theme-toggle" class="theme-toggle" aria-label="Alternar tema claro/escuro"
          aria-pressed="false">
          <span class="knob">
            <svg id="theme-icon-sun" class="hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2.2" aria-hidden="true">
              <circle cx="12" cy="12" r="4" />
              <path
                d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" />
            </svg>
            <svg id="theme-icon-moon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M21 12.6A9 9 0 1111.4 3a7 7 0 009.6 9.6z" />
            </svg>
          </span>
        </button>

        <a href="#contact" class="hidden md:inline-flex btn-primary rounded-full px-5 py-2.5 text-sm font-semibold"
          data-i18n="navCta"><?= e($dict['pt']['navCta']) ?></a>

        <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg border border-c"
          aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-menu">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </nav>

    <div id="mobile-menu"
      class="mobile-menu lg:hidden absolute top-full inset-x-0 bg-elevated border-b border-c px-5 py-6">
      <ul class="flex flex-col gap-4 font-medium">
        <li><a href="#home" class="mobile-link" data-i18n="navHome"><?= e($dict['pt']['navHome']) ?></a></li>
        <li><a href="#services" class="mobile-link" data-i18n="navServices"><?= e($dict['pt']['navServices']) ?></a></li>
        <li><a href="#about" class="mobile-link" data-i18n="navAbout"><?= e($dict['pt']['navAbout']) ?></a></li>
        <li><a href="#portfolio" class="mobile-link" data-i18n="navPortfolio"><?= e($dict['pt']['navPortfolio']) ?></a></li>
        <li><a href="#team" class="mobile-link" data-i18n="navTeam"><?= e($dict['pt']['navTeam']) ?></a></li>
        <li><a href="#contact" class="mobile-link" data-i18n="navContact"><?= e($dict['pt']['navContact']) ?></a></li>
        <li><a href="login.php" class="mobile-link" data-i18n="navLogin" id="open-login-modal-mobile"><?= e($dict['pt']['navLogin']) ?></a></li>
        <li class="flex items-center gap-2 pt-2">
          <button type="button" data-lang-btn="pt"
            class="lang-select-btn px-3 py-1.5 rounded-full text-xs font-medium border border-c"
            aria-pressed="true">🇧🇷 PT</button>
          <button type="button" data-lang-btn="en"
            class="lang-select-btn px-3 py-1.5 rounded-full text-xs font-medium border border-c"
            aria-pressed="false">🇺🇸 EN</button>
        </li>
        <li><a href="#contact"
            class="btn-primary rounded-full px-5 py-2.5 text-sm font-semibold inline-block text-center"
            data-i18n="navCta"><?= e($dict['pt']['navCta']) ?></a></li>
      </ul>
    </div>
  </header>

  <main id="main-content">

    <!-- ============ CAPA ============ -->
    <section id="home" class="relative min-h-[100svh] flex items-center pt-28 pb-20 overflow-hidden">
      <div class="hero-canvas-wrap" aria-hidden="true">
        <div class="absolute inset-0 grid-fade"></div>
        <div class="glow-orb float-slow w-[420px] h-[420px] -top-20 -left-24" style="background: var(--primary);"></div>
        <div class="glow-orb float-slower w-[380px] h-[380px] top-10 right-[-100px]"
          style="background: var(--secondary);"></div>
        <svg class="absolute inset-0 w-full h-full opacity-70" viewBox="0 0 1200 800"
          preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <g stroke="var(--primary)" stroke-width="1" opacity="0.35">
            <line class="line-draw" x1="180" y1="140" x2="420" y2="260" />
            <line class="line-draw" x1="420" y1="260" x2="700" y2="180" style="animation-delay:.2s" />
            <line class="line-draw" x1="700" y1="180" x2="960" y2="300" style="animation-delay:.4s" />
            <line class="line-draw" x1="420" y1="260" x2="520" y2="480" style="animation-delay:.3s" />
            <line class="line-draw" x1="520" y1="480" x2="820" y2="520" style="animation-delay:.5s" />
            <line class="line-draw" x1="960" y1="300" x2="820" y2="520" style="animation-delay:.6s" />
            <line class="line-draw" x1="180" y1="140" x2="150" y2="380" style="animation-delay:.15s" />
          </g>
          <g fill="var(--secondary)">
            <circle class="node-pulse" cx="180" cy="140" r="3" />
            <circle class="node-pulse" cx="420" cy="260" r="3" style="animation-delay:.4s" />
            <circle class="node-pulse" cx="700" cy="180" r="3" style="animation-delay:.8s" />
            <circle class="node-pulse" cx="960" cy="300" r="3" style="animation-delay:1.2s" />
            <circle class="node-pulse" cx="520" cy="480" r="3" style="animation-delay:.6s" />
            <circle class="node-pulse" cx="820" cy="520" r="3" style="animation-delay:1s" />
            <circle class="node-pulse" cx="150" cy="380" r="3" style="animation-delay:.2s" />
          </g>
        </svg>
      </div>

      <div class="relative max-w-7xl mx-auto px-5 md:px-8 w-full grid lg:grid-cols-[1.1fr,0.9fr] gap-16 items-center">
        <div>
          <div
            class="reveal in-view inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-c bg-elevated/60 text-xs font-mono text-secondary-c mb-7">
            <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary)]"></span>
            <span data-i18n="heroEyebrow"><?= e($dict['pt']['heroEyebrow']) ?></span>
          </div>
          <h1
            class="reveal in-view font-display font-semibold text-[2.5rem] leading-[1.08] sm:text-5xl lg:text-[3.4rem] tracking-tight mb-6"
            style="transition-delay:.05s">
            <span data-i18n="heroTitle1"><?= e($dict['pt']['heroTitle1']) ?></span>
            <span class="brand-grad-text block" data-i18n="heroTitle2"><?= e($dict['pt']['heroTitle2']) ?></span>
          </h1>
          <p class="reveal in-view text-lg text-secondary-c max-w-xl mb-10 leading-relaxed" style="transition-delay:.1s"
            data-i18n="heroSub"><?= e($dict['pt']['heroSub']) ?></p>
          <div class="reveal in-view flex flex-wrap gap-4" style="transition-delay:.15s">
            <a href="#contact" class="btn-primary rounded-full px-7 py-3.5 font-semibold text-[15px]"
              data-i18n="heroBtn1"><?= e($dict['pt']['heroBtn1']) ?></a>
            <a href="#portfolio" class="btn-ghost rounded-full px-7 py-3.5 font-semibold text-[15px]"
              data-i18n="heroBtn2"><?= e($dict['pt']['heroBtn2']) ?></a>
          </div>
        </div>

        <div class="reveal-scale in-view hidden lg:block" style="transition-delay:.2s">
          <div class="card rounded-2xl p-6 max-w-sm ml-auto backdrop-blur-sm">
            <div class="flex items-center gap-2 mb-5">
              <span class="w-3 h-3 rounded-full bg-[#ff5f57]"></span><span
                class="w-3 h-3 rounded-full bg-[#febc2e]"></span><span class="w-3 h-3 rounded-full bg-[#28c840]"></span>
            </div>
            <p class="font-mono text-xs text-muted-c mb-2">// codora.deploy()</p>
            <p class="font-mono text-sm mb-1"><span class="text-[var(--secondary)]">const</span> <span
                class="text-[var(--primary)]">project</span> = {</p>
            <p class="font-mono text-sm pl-4" data-i18n="heroCodeLine1"><?= e($dict['pt']['heroCodeLine1']) ?></p>
            <p class="font-mono text-sm pl-4" data-i18n="heroCodeLine2"><?= e($dict['pt']['heroCodeLine2']) ?></p>
            <p class="font-mono text-sm pl-4" data-i18n="heroCodeLine3"><?= e($dict['pt']['heroCodeLine3']) ?></p>
            <p class="font-mono text-sm mb-4">}</p>
            <div class="h-px bg-[var(--border)] my-4"></div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-muted-c" data-i18n="heroCardLabel"><?= e($dict['pt']['heroCardLabel']) ?></span>
              <span class="font-display font-semibold text-lg brand-grad-text">98%</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ INDICADORES DE CONFIANÇA ============ -->
    <section aria-label="Diferenciais" class="relative border-t border-c bg-subtle">
      <div class="max-w-7xl mx-auto px-5 md:px-8 py-14 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <div class="reveal text-center" data-trust>
          <div class="font-display font-semibold text-2xl brand-grad-text counter-num" data-count="100" data-suffix="%">
            0%</div>
          <p class="text-xs text-muted-c mt-1.5" data-i18n="trust1"><?= e($dict['pt']['trust1']) ?></p>
        </div>
        <div class="reveal text-center" style="transition-delay:.1s" data-trust>
          <div class="font-display font-semibold text-2xl brand-grad-text counter-num" data-count="10" data-suffix="+">0
          </div>
          <p class="text-xs text-muted-c mt-1.5" data-i18n="trust2"><?= e($dict['pt']['trust2']) ?></p>
        </div>
        <div class="reveal text-center" style="transition-delay:.15s" data-trust>
          <div class="font-display font-semibold text-2xl brand-grad-text counter-num" data-count="100" data-suffix="%">
            0%</div>
          <p class="text-xs text-muted-c mt-1.5" data-i18n="trust4"><?= e($dict['pt']['trust4']) ?></p>
        </div>
        <div class="reveal text-center" style="transition-delay:.2s" data-trust>
          <div class="font-display font-semibold text-2xl brand-grad-text counter-num" data-count="2" data-suffix="x">0
          </div>
          <p class="text-xs text-muted-c mt-1.5" data-i18n="trust5"><?= e($dict['pt']['trust5']) ?></p>
        </div>
        <div class="reveal text-center" style="transition-delay:.25s" data-trust>
          <div class="font-display font-semibold text-2xl brand-grad-text counter-num" data-count="100" data-suffix="%">
            0%</div>
          <p class="text-xs text-muted-c mt-1.5" data-i18n="trust6"><?= e($dict['pt']['trust6']) ?></p>
        </div>
      </div>
    </section>

    <!-- ============ SOBRE NÓS ============ -->
    <section id="about" class="py-28 bg-subtle border-y border-c">
      <div class="max-w-7xl mx-auto px-5 md:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-start mb-20">
          <div>
            <p class="font-mono text-xs text-[var(--secondary)] mb-3 tracking-wide" data-i18n="aboutEyebrow"><?= e($dict['pt']['aboutEyebrow']) ?></p>
            <h2 class="font-display font-semibold text-3xl md:text-4xl tracking-tight mb-6 reveal"
              data-i18n="aboutTitle"><?= e($dict['pt']['aboutTitle']) ?></h2>
            <p class="text-secondary-c leading-relaxed mb-4 reveal" data-i18n="aboutP1"><?= e($dict['pt']['aboutP1']) ?></p>
            <p class="text-secondary-c leading-relaxed mb-4 reveal" data-i18n="aboutP2"><?= e($dict['pt']['aboutP2']) ?></p>
            <p class="font-display font-medium text-lg brand-grad-text reveal">"<span data-i18n="aboutSlogan"><?= e($dict['pt']['aboutSlogan']) ?></span>"</p>
          </div>
          <div class="grid sm:grid-cols-3 gap-5">
            <div class="reveal card rounded-2xl p-6">
              <h3 class="font-display font-semibold mb-2 text-[var(--primary)]" data-i18n="missionTitle"><?= e($dict['pt']['missionTitle']) ?></h3>
              <p class="text-sm text-secondary-c leading-relaxed" data-i18n="missionDesc"><?= e($dict['pt']['missionDesc']) ?></p>
            </div>
            <div class="reveal card rounded-2xl p-6" style="transition-delay:.05s">
              <h3 class="font-display font-semibold mb-2 text-[var(--primary)]" data-i18n="visionTitle"><?= e($dict['pt']['visionTitle']) ?></h3>
              <p class="text-sm text-secondary-c leading-relaxed" data-i18n="visionDesc"><?= e($dict['pt']['visionDesc']) ?></p>
            </div>
            <div class="reveal card rounded-2xl p-6" style="transition-delay:.1s">
              <h3 class="font-display font-semibold mb-2 text-[var(--primary)]" data-i18n="valuesTitle"><?= e($dict['pt']['valuesTitle']) ?></h3>
              <p class="text-sm text-secondary-c leading-relaxed" data-i18n="valuesDesc"><?= e($dict['pt']['valuesDesc']) ?></p>
            </div>
          </div>
        </div>

        <!-- Por que escolher -->
        <div class="mb-4">
          <h3 class="font-display font-semibold text-2xl tracking-tight mb-8 reveal" data-i18n="whyTitle"><?= e($dict['pt']['whyTitle']) ?></h3>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <div class="reveal card rounded-xl p-5">
            <p class="font-display font-semibold mb-1" data-i18n="why1Title"><?= e($dict['pt']['why1Title']) ?></p>
            <p class="text-sm text-muted-c" data-i18n="why1Desc"><?= e($dict['pt']['why1Desc']) ?></p>
          </div>
          <div class="reveal card rounded-xl p-5" style="transition-delay:.05s">
            <p class="font-display font-semibold mb-1" data-i18n="why2Title"><?= e($dict['pt']['why2Title']) ?></p>
            <p class="text-sm text-muted-c" data-i18n="why2Desc"><?= e($dict['pt']['why2Desc']) ?></p>
          </div>
          <div class="reveal card rounded-xl p-5" style="transition-delay:.1s">
            <p class="font-display font-semibold mb-1" data-i18n="why3Title"><?= e($dict['pt']['why3Title']) ?></p>
            <p class="text-sm text-muted-c" data-i18n="why3Desc"><?= e($dict['pt']['why3Desc']) ?></p>
          </div>
          <div class="reveal card rounded-xl p-5" style="transition-delay:.15s">
            <p class="font-display font-semibold mb-1" data-i18n="why4Title"><?= e($dict['pt']['why4Title']) ?></p>
            <p class="text-sm text-muted-c" data-i18n="why4Desc"><?= e($dict['pt']['why4Desc']) ?></p>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ NOSSA EQUIPE ============ -->
    <section id="team" class="py-28 bg-subtle border-y border-c">
      <div class="max-w-7xl mx-auto px-5 md:px-8">
        <div class="text-center max-w-xl mx-auto mb-16">
          <p class="font-mono text-xs text-[var(--secondary)] mb-3 tracking-wide" data-i18n="teamEyebrow"><?= e($dict['pt']['teamEyebrow']) ?></p>
          <h2 class="font-display font-semibold text-3xl md:text-4xl tracking-tight" data-i18n="teamTitle"><?= e($dict['pt']['teamTitle']) ?></h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-7">
          <div class="reveal team-card card rounded-2xl p-6 text-center">
            <div class="team-avatar-ring w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-2 border-[var(--primary)]">
              <img src="<?= e(image_path($team[1]['foto'])) ?>" alt="<?= e($dict['pt']['team1Name']) ?>" class="w-full h-full object-cover" style="object-position: 50% 30%;">
            </div>
            <h3 class="font-display font-semibold" data-i18n="team1Name"><?= e($dict['pt']['team1Name']) ?></h3>
            <p class="text-xs text-[var(--secondary)] font-medium mt-1 mb-3" data-i18n="team1Role"><?= e($dict['pt']['team1Role']) ?></p>
            <p class="text-sm text-muted-c mb-4" data-i18n="team1Desc"><?= e($dict['pt']['team1Desc']) ?></p>
            <div class="flex justify-center gap-3">
              <?php if ($team[1]['instagram'] !== ''): ?><a href="<?= e(public_url($team[1]['instagram'])) ?>" rel="noopener noreferrer" target="_blank" aria-label="<?= e('Instagram: ' . $dict['pt']['team1Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <rect x="3" y="3" width="18" height="18" rx="5" />
                  <circle cx="12" cy="12" r="4" />
                  <circle cx="17.5" cy="6.5" r="1" />
                </svg></a><?php endif; ?>
              <?php if ($team[1]['linkedin'] !== ''): ?><a href="<?= e(public_url($team[1]['linkedin'])) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e('LinkedIn: ' . $dict['pt']['team1Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M4 4h16v16H4z" opacity="0" />
                  <path d="M6.5 9v9M6.5 6.2v.1M11 18v-5.2c0-1.7 1-2.8 2.6-2.8 1.5 0 2.4 1 2.4 2.8V18" />
                </svg></a><?php endif; ?>
            </div>
          </div>

          <div class="reveal team-card card rounded-2xl p-6 text-center" style="transition-delay:.05s">
            <div class="team-avatar-ring w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-2 border-[var(--primary)]">
              <img src="<?= e(image_path($team[2]['foto'])) ?>" alt="<?= e($dict['pt']['team2Name']) ?>" class="w-full h-full object-cover" style="object-position: 50% 30%;">
            </div>
            <h3 class="font-display font-semibold" data-i18n="team2Name"><?= e($dict['pt']['team2Name']) ?></h3>
            <p class="text-xs text-[var(--secondary)] font-medium mt-1 mb-3" data-i18n="team2Role"><?= e($dict['pt']['team2Role']) ?></p>
            <p class="text-sm text-muted-c mb-4" data-i18n="team2Desc"><?= e($dict['pt']['team2Desc']) ?></p>
            <div class="flex justify-center gap-3">
              <?php if ($team[2]['instagram'] !== ''): ?><a href="<?= e(public_url($team[2]['instagram'])) ?>" rel="noopener noreferrer" target="_blank"  aria-label="<?= e('Instagram: ' . $dict['pt']['team2Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <rect x="3" y="3" width="18" height="18" rx="5" />
                  <circle cx="12" cy="12" r="4" />
                  <circle cx="17.5" cy="6.5" r="1" />
                </svg></a><?php endif; ?>
              <?php if ($team[2]['linkedin'] !== ''): ?><a href="<?= e(public_url($team[2]['linkedin'])) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e('LinkedIn: ' . $dict['pt']['team2Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M6.5 9v9M6.5 6.2v.1M11 18v-5.2c0-1.7 1-2.8 2.6-2.8 1.5 0 2.4 1 2.4 2.8V18" />
                </svg></a><?php endif; ?>
            </div>
          </div>

          <div class="reveal team-card card rounded-2xl p-6 text-center" style="transition-delay:.1s">
            <div class="team-avatar-ring w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-2 border-[var(--primary)]">
              <img src="<?= e(image_path($team[3]['foto'])) ?>" alt="<?= e($dict['pt']['team3Name']) ?>" class="w-full h-full object-cover" style="object-position: 50% 30%;">
            </div>
            <h3 class="font-display font-semibold" data-i18n="team3Name"><?= e($dict['pt']['team3Name']) ?></h3>
            <p class="text-xs text-[var(--secondary)] font-medium mt-1 mb-3" data-i18n="team3Role"><?= e($dict['pt']['team3Role']) ?></p>
            <p class="text-sm text-muted-c mb-4" data-i18n="team3Desc"><?= e($dict['pt']['team3Desc']) ?></p>
            <div class="flex justify-center gap-3">
              <?php if ($team[3]['instagram'] !== ''): ?><a href="<?= e(public_url($team[3]['instagram'])) ?>" rel="noopener noreferrer" target="_blank" aria-label="<?= e('Instagram: ' . $dict['pt']['team3Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <rect x="3" y="3" width="18" height="18" rx="5" />
                  <circle cx="12" cy="12" r="4" />
                  <circle cx="17.5" cy="6.5" r="1" />
                </svg></a><?php endif; ?>
              <?php if ($team[3]['linkedin'] !== ''): ?><a href="<?= e(public_url($team[3]['linkedin'])) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e('LinkedIn: ' . $dict['pt']['team3Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M6.5 9v9M6.5 6.2v.1M11 18v-5.2c0-1.7 1-2.8 2.6-2.8 1.5 0 2.4 1 2.4 2.8V18" />
                </svg></a><?php endif; ?>
            </div>
          </div>

          <div class="reveal team-card card rounded-2xl p-6 text-center" style="transition-delay:.15s">
            <div class="team-avatar-ring w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-2 border-[var(--primary)]">
              <img src="<?= e(image_path($team[4]['foto'])) ?>" alt="<?= e($dict['pt']['team4Name']) ?>" class="w-full h-full object-cover" style="object-position: 50% 30%;">
            </div>
            <h3 class="font-display font-semibold" data-i18n="team4Name"><?= e($dict['pt']['team4Name']) ?></h3>
            <p class="text-xs text-[var(--secondary)] font-medium mt-1 mb-3" data-i18n="team4Role"><?= e($dict['pt']['team4Role']) ?></p>
            <p class="text-sm text-muted-c mb-4" data-i18n="team4Desc"><?= e($dict['pt']['team4Desc']) ?></p>
            <div class="flex justify-center gap-3">
              <?php if ($team[4]['instagram'] !== ''): ?><a href="<?= e(public_url($team[4]['instagram'])) ?>" rel="noopener noreferrer" target="_blank" aria-label="<?= e('Instagram: ' . $dict['pt']['team4Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <rect x="3" y="3" width="18" height="18" rx="5" />
                  <circle cx="12" cy="12" r="4" />
                  <circle cx="17.5" cy="6.5" r="1" />
                </svg></a><?php endif; ?>
              <?php if ($team[4]['linkedin'] !== ''): ?><a href="<?= e(public_url($team[4]['linkedin'])) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e('LinkedIn: ' . $dict['pt']['team4Name']) ?>"
                class="text-muted-c hover:text-[var(--primary)] transition-colors"><svg width="18" height="18"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M6.5 9v9M6.5 6.2v.1M11 18v-5.2c0-1.7 1-2.8 2.6-2.8 1.5 0 2.4 1 2.4 2.8V18" />
                </svg></a><?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ SERVIÇOS ============ -->
    <section id="services" class="py-28 max-w-7xl mx-auto px-5 md:px-8">
      <div class="max-w-2xl mb-16">
        <p class="font-mono text-xs text-[var(--secondary)] mb-3 tracking-wide" data-i18n="servicesEyebrow"><?= e($dict['pt']['servicesEyebrow']) ?></p>
        <h2 class="font-display font-semibold text-3xl md:text-4xl tracking-tight mb-4" data-i18n="servicesTitle"><?= e($dict['pt']['servicesTitle']) ?></h2>
        <p class="text-secondary-c leading-relaxed" data-i18n="servicesSub"><?= e($dict['pt']['servicesSub']) ?></p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- modelo de card de serviço -->
        <article class="reveal card rounded-2xl p-7 group">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5"
            style="background: color-mix(in srgb, var(--primary) 12%, transparent);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8">
              <path d="M8 3H5a2 2 0 00-2 2v3m18-5h-3a2 2 0 00-2 2v3M8 21H5a2 2 0 01-2-2v-3m18 5h-3a2 2 0 01-2-2v-3" />
            </svg>
          </div>
          <h3 class="font-display font-semibold text-lg mb-2" data-i18n="svc1Title"><?= e($dict['pt']['svc1Title']) ?></h3>
          <p class="text-sm text-secondary-c leading-relaxed mb-5" data-i18n="svc1Desc"><?= e($dict['pt']['svc1Desc']) ?></p>
          <a href="#contact"
            class="text-sm font-semibold inline-flex items-center gap-1.5 text-[var(--primary)] group-hover:gap-2.5 transition-all"
            data-i18n="svcCta"><?= e($dict['pt']['svcCta']) ?></a>
        </article>

        <article class="reveal card rounded-2xl p-7 group" style="transition-delay:.05s">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5"
            style="background: color-mix(in srgb, var(--secondary) 14%, transparent);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="1.8">
              <circle cx="12" cy="12" r="9" />
              <path d="M3 12h18M12 3c2.5 2.5 3.8 5.6 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.6-3.8-9s1.3-6.5 3.8-9z" />
            </svg>
          </div>
          <h3 class="font-display font-semibold text-lg mb-2" data-i18n="svc2Title"><?= e($dict['pt']['svc2Title']) ?></h3>
          <p class="text-sm text-secondary-c leading-relaxed mb-5" data-i18n="svc2Desc"><?= e($dict['pt']['svc2Desc']) ?></p>
          <a href="#contact"
            class="text-sm font-semibold inline-flex items-center gap-1.5 text-[var(--primary)] group-hover:gap-2.5 transition-all"
            data-i18n="svcCta"><?= e($dict['pt']['svcCta']) ?></a>
        </article>

        <article class="reveal card rounded-2xl p-7 group" style="transition-delay:.1s">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5"
            style="background: color-mix(in srgb, var(--primary) 12%, transparent);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8">
              <rect x="3" y="4" width="18" height="14" rx="2" />
              <path d="M3 9h18M8 18v2m8-2v2" />
            </svg>
          </div>
          <h3 class="font-display font-semibold text-lg mb-2" data-i18n="svc3Title"><?= e($dict['pt']['svc3Title']) ?></h3>
          <p class="text-sm text-secondary-c leading-relaxed mb-5" data-i18n="svc3Desc"><?= e($dict['pt']['svc3Desc']) ?></p>
          <a href="#contact"
            class="text-sm font-semibold inline-flex items-center gap-1.5 text-[var(--primary)] group-hover:gap-2.5 transition-all"
            data-i18n="svcCta"><?= e($dict['pt']['svcCta']) ?></a>
        </article>

        <article class="reveal card rounded-2xl p-7 group" style="transition-delay:.15s">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5"
            style="background: color-mix(in srgb, var(--secondary) 14%, transparent);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="1.8">
              <path d="M12 2l2.4 6.6L21 11l-6.6 2.4L12 20l-2.4-6.6L3 11l6.6-2.4z" />
            </svg>
          </div>
          <h3 class="font-display font-semibold text-lg mb-2" data-i18n="svc4Title"><?= e($dict['pt']['svc4Title']) ?></h3>
          <p class="text-sm text-secondary-c leading-relaxed mb-5" data-i18n="svc4Desc"><?= e($dict['pt']['svc4Desc']) ?></p>
          <a href="#contact"
            class="text-sm font-semibold inline-flex items-center gap-1.5 text-[var(--primary)] group-hover:gap-2.5 transition-all"
            data-i18n="svcCta"><?= e($dict['pt']['svcCta']) ?></a>
        </article>

        <article class="reveal card rounded-2xl p-7 group" style="transition-delay:.2s">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5"
            style="background: color-mix(in srgb, var(--primary) 12%, transparent);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8">
              <path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z" />
            </svg>
          </div>
          <h3 class="font-display font-semibold text-lg mb-2" data-i18n="svc5Title"><?= e($dict['pt']['svc5Title']) ?></h3>
          <p class="text-sm text-secondary-c leading-relaxed mb-5" data-i18n="svc5Desc"><?= e($dict['pt']['svc5Desc']) ?></p>
          <a href="#contact"
            class="text-sm font-semibold inline-flex items-center gap-1.5 text-[var(--primary)] group-hover:gap-2.5 transition-all"
            data-i18n="svcCta"><?= e($dict['pt']['svcCta']) ?></a>
        </article>

        <article class="reveal card rounded-2xl p-7 group" style="transition-delay:.25s">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5"
            style="background: color-mix(in srgb, var(--secondary) 14%, transparent);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="1.8">
              <path d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z" />
            </svg>
          </div>
          <h3 class="font-display font-semibold text-lg mb-2" data-i18n="svc6Title"><?= e($dict['pt']['svc6Title']) ?></h3>
          <p class="text-sm text-secondary-c leading-relaxed mb-5" data-i18n="svc6Desc"><?= e($dict['pt']['svc6Desc']) ?></p>
          <a href="#contact"
            class="text-sm font-semibold inline-flex items-center gap-1.5 text-[var(--primary)] group-hover:gap-2.5 transition-all"
            data-i18n="svcCta"><?= e($dict['pt']['svcCta']) ?></a>
        </article>
      </div>
    </section>

    <!-- ============ LINHA DO TEMPO DO PROCESSO ============ -->
    <section class="py-28 max-w-5xl mx-auto px-5 md:px-8">
      <div class="text-center max-w-xl mx-auto mb-16">
        <p class="font-mono text-xs text-[var(--secondary)] mb-3 tracking-wide" data-i18n="processEyebrow"><?= e($dict['pt']['processEyebrow']) ?></p>
        <h2 class="font-display font-semibold text-3xl md:text-4xl tracking-tight" data-i18n="processTitle"><?= e($dict['pt']['processTitle']) ?></h2>
      </div>
      <ol class="relative pl-10">
        <div class="absolute left-[15px] top-2 bottom-2 w-0.5 timeline-line rounded-full" aria-hidden="true"></div>
        <li class="reveal relative pb-10">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">01</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step1Title"><?= e($dict['pt']['step1Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step1Desc"><?= e($dict['pt']['step1Desc']) ?></p>
        </li>
        <li class="reveal relative pb-10" style="transition-delay:.05s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">02</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step2Title"><?= e($dict['pt']['step2Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step2Desc"><?= e($dict['pt']['step2Desc']) ?></p>
        </li>
        <li class="reveal relative pb-10" style="transition-delay:.1s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">03</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step3Title"><?= e($dict['pt']['step3Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step3Desc"><?= e($dict['pt']['step3Desc']) ?></p>
        </li>
        <li class="reveal relative pb-10" style="transition-delay:.15s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">04</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step4Title"><?= e($dict['pt']['step4Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step4Desc"><?= e($dict['pt']['step4Desc']) ?></p>
        </li>
        <li class="reveal relative pb-10" style="transition-delay:.2s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">05</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step5Title"><?= e($dict['pt']['step5Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step5Desc"><?= e($dict['pt']['step5Desc']) ?></p>
        </li>
        <li class="reveal relative pb-10" style="transition-delay:.25s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">06</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step6Title"><?= e($dict['pt']['step6Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step6Desc"><?= e($dict['pt']['step6Desc']) ?></p>
        </li>
        <li class="reveal relative pb-10" style="transition-delay:.3s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">07</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step7Title"><?= e($dict['pt']['step7Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step7Desc"><?= e($dict['pt']['step7Desc']) ?></p>
        </li>
        <li class="reveal relative pb-10" style="transition-delay:.35s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full bg-elevated border-2 border-[var(--primary)] flex items-center justify-center font-mono text-xs font-semibold">08</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step8Title"><?= e($dict['pt']['step8Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step8Desc"><?= e($dict['pt']['step8Desc']) ?></p>
        </li>
        <li class="reveal relative" style="transition-delay:.4s">
          <span
            class="absolute -left-10 top-0 w-8 h-8 rounded-full flex items-center justify-center font-mono text-xs font-semibold text-white"
            style="background: linear-gradient(135deg, var(--primary), var(--secondary));">09</span>
          <h3 class="font-display font-semibold mb-1" data-i18n="step9Title"><?= e($dict['pt']['step9Title']) ?></h3>
          <p class="text-sm text-secondary-c" data-i18n="step9Desc"><?= e($dict['pt']['step9Desc']) ?></p>
        </li>
      </ol>
    </section>

    <!-- ============ PORTFÓLIO ============ -->
    <section id="portfolio" class="py-28 bg-subtle border-y border-c">
      <div class="max-w-7xl mx-auto px-5 md:px-8">
        <div class="flex flex-wrap items-end justify-between gap-6 mb-10">
          <div>
            <p class="font-mono text-xs text-[var(--secondary)] mb-3 tracking-wide" data-i18n="portfolioEyebrow"><?= e($dict['pt']['portfolioEyebrow']) ?></p>
            <h2 class="font-display font-semibold text-3xl md:text-4xl tracking-tight" data-i18n="portfolioTitle"><?= e($dict['pt']['portfolioTitle']) ?></h2>
          </div>
          <div class="flex flex-wrap gap-2" role="group" aria-label="Filtrar por categoria">
            <button type="button"
              class="portfolio-filter-btn text-sm font-medium px-4 py-2 rounded-full border border-c" data-filter="all"
              aria-pressed="true" data-i18n="filterAll"><?= e($dict['pt']['filterAll']) ?></button>
            <button type="button"
              class="portfolio-filter-btn text-sm font-medium px-4 py-2 rounded-full border border-c" data-filter="site"
              aria-pressed="false" data-i18n="filterSites"><?= e($dict['pt']['filterSites']) ?></button>
            <button type="button"
              class="portfolio-filter-btn text-sm font-medium px-4 py-2 rounded-full border border-c"
              data-filter="sistema" aria-pressed="false" data-i18n="filterSystems"><?= e($dict['pt']['filterSystems']) ?></button>
            <button type="button"
              class="portfolio-filter-btn text-sm font-medium px-4 py-2 rounded-full border border-c"
              data-filter="landing" aria-pressed="false" data-i18n="filterLanding"><?= e($dict['pt']['filterLanding']) ?></button>
            <button type="button"
              class="portfolio-filter-btn text-sm font-medium px-4 py-2 rounded-full border border-c"
              data-filter="marca" aria-pressed="false" data-i18n="filterBrand"><?= e($dict['pt']['filterBrand']) ?></button>
          </div>
        </div>

        <div id="portfolio-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php if ($portfolio[1]['ativo']): ?>
<article class="reveal portfolio-item card rounded-2xl overflow-hidden group" data-category="<?= e($portfolio[1]['categoria']) ?>">
            <div class="h-44 relative overflow-hidden"
              style="background: linear-gradient(135deg, var(--primary), #7c2fd1);">
              <?php if ($portfolio[1]['imagem'] !== ''): ?><img src="<?= e(image_path($portfolio[1]['imagem'])) ?>" alt="<?= e($dict['pt']['pf1Name']) ?>" class="absolute inset-0 w-full h-full object-cover" loading="lazy"><?php else: ?>
              <div
                class="absolute inset-0 flex items-center justify-center font-display font-semibold text-white/90 text-sm tracking-wide transition-transform duration-500 group-hover:scale-110" data-i18n="pf1Name"><?= e($dict['pt']['pf1Name']) ?></div>
              <?php endif; ?>
            </div>
            <div class="p-5">
              <p class="text-xs font-mono text-[var(--secondary)] mb-1" data-i18n="pf1Cat"><?= e($dict['pt']['pf1Cat']) ?></p>
              <h3 class="font-display font-semibold mb-1" data-i18n="pf1Name2"><?= e($dict['pt']['pf1Name2']) ?></h3>
              <p class="text-sm text-muted-c mb-4" data-i18n="pf1Desc"><?= e($dict['pt']['pf1Desc']) ?></p>
              <div class="flex gap-3 text-sm font-medium">
                <a href="#featured-project" class="text-[var(--primary)]" data-i18n="pfDetails"><?= e($dict['pt']['pfDetails']) ?></a>
              </div>
            </div>
          </article>
<?php endif; ?>

          <?php if ($portfolio[2]['ativo']): ?>
<article class="reveal portfolio-item card rounded-2xl overflow-hidden group" data-category="<?= e($portfolio[2]['categoria']) ?>"
            style="transition-delay:.05s">
            <div class="h-44 relative overflow-hidden" style="background: linear-gradient(135deg, #1e1730, #420f74);">
              <?php if ($portfolio[2]['imagem'] !== ''): ?><img src="<?= e(image_path($portfolio[2]['imagem'])) ?>" alt="<?= e($dict['pt']['pf2Name']) ?>" class="absolute inset-0 w-full h-full object-cover" loading="lazy"><?php else: ?>
              <div
                class="absolute inset-0 flex items-center justify-center font-display font-semibold text-white/90 text-sm tracking-wide transition-transform duration-500 group-hover:scale-110"
                data-i18n="pf2Name"><?= e($dict['pt']['pf2Name']) ?></div>
              <?php endif; ?>
            </div>
            <div class="p-5">
              <p class="text-xs font-mono text-[var(--secondary)] mb-1" data-i18n="pf2Cat"><?= e($dict['pt']['pf2Cat']) ?></p>
              <h3 class="font-display font-semibold mb-1" data-i18n="pf2Name2"><?= e($dict['pt']['pf2Name2']) ?></h3>
              <p class="text-sm text-muted-c mb-4" data-i18n="pf2Desc"><?= e($dict['pt']['pf2Desc']) ?></p>
            </div>
          </article>
<?php endif; ?>

          <?php if ($portfolio[3]['ativo']): ?>
<article class="reveal portfolio-item card rounded-2xl overflow-hidden group" data-category="<?= e($portfolio[3]['categoria']) ?>"
            style="transition-delay:.1s">
            <div class="h-44 relative overflow-hidden"
              style="background: linear-gradient(135deg, var(--secondary), #ffcc80);">
              <?php if ($portfolio[3]['imagem'] !== ''): ?><img src="<?= e(image_path($portfolio[3]['imagem'])) ?>" alt="<?= e($dict['pt']['pf3Name']) ?>" class="absolute inset-0 w-full h-full object-cover" loading="lazy"><?php else: ?>
              <div
                class="absolute inset-0 flex items-center justify-center font-display font-semibold text-white/95 text-sm tracking-wide transition-transform duration-500 group-hover:scale-110"
                data-i18n="pf3Name"><?= e($dict['pt']['pf3Name']) ?></div>
              <?php endif; ?>
            </div>
            <div class="p-5">
              <p class="text-xs font-mono text-[var(--secondary)] mb-1" data-i18n="pf3Cat"><?= e($dict['pt']['pf3Cat']) ?></p>
              <h3 class="font-display font-semibold mb-1" data-i18n="pf3Name2"><?= e($dict['pt']['pf3Name2']) ?></h3>
              <p class="text-sm text-muted-c mb-4" data-i18n="pf3Desc"><?= e($dict['pt']['pf3Desc']) ?></p>
            </div>
          </article>
<?php endif; ?>

          <?php if ($portfolio[4]['ativo']): ?>
<article class="reveal portfolio-item card rounded-2xl overflow-hidden group" data-category="<?= e($portfolio[4]['categoria']) ?>"
            style="transition-delay:.15s">
            <div class="h-44 relative overflow-hidden"
              style="background: linear-gradient(135deg, #420f74, var(--secondary));">
              <?php if ($portfolio[4]['imagem'] !== ''): ?><img src="<?= e(image_path($portfolio[4]['imagem'])) ?>" alt="<?= e($dict['pt']['pf4Name']) ?>" class="absolute inset-0 w-full h-full object-cover" loading="lazy"><?php else: ?>
              <div
                class="absolute inset-0 flex items-center justify-center font-display font-semibold text-white/95 text-sm tracking-wide transition-transform duration-500 group-hover:scale-110"
                data-i18n="pf4Name"><?= e($dict['pt']['pf4Name']) ?></div>
              <?php endif; ?>
            </div>
            <div class="p-5">
              <p class="text-xs font-mono text-[var(--secondary)] mb-1" data-i18n="pf4Cat"><?= e($dict['pt']['pf4Cat']) ?></p>
              <h3 class="font-display font-semibold mb-1" data-i18n="pf4Name2"><?= e($dict['pt']['pf4Name2']) ?></h3>
              <p class="text-sm text-muted-c mb-4" data-i18n="pf4Desc"><?= e($dict['pt']['pf4Desc']) ?></p>
            </div>
          </article>
<?php endif; ?>

          <?php if ($portfolio[5]['ativo']): ?>
<article class="reveal portfolio-item card rounded-2xl overflow-hidden group" data-category="<?= e($portfolio[5]['categoria']) ?>"
            style="transition-delay:.2s">
            <div class="h-44 relative overflow-hidden" style="background: linear-gradient(135deg, #2a1b47, #6a21ab);">
              <?php if ($portfolio[5]['imagem'] !== ''): ?><img src="<?= e(image_path($portfolio[5]['imagem'])) ?>" alt="<?= e($dict['pt']['pf5Name']) ?>" class="absolute inset-0 w-full h-full object-cover" loading="lazy"><?php else: ?>
              <div
                class="absolute inset-0 flex items-center justify-center font-display font-semibold text-white/90 text-sm tracking-wide transition-transform duration-500 group-hover:scale-110"
                data-i18n="pf5Name"><?= e($dict['pt']['pf5Name']) ?></div>
              <?php endif; ?>
            </div>
            <div class="p-5">
              <p class="text-xs font-mono text-[var(--secondary)] mb-1" data-i18n="pf5Cat"><?= e($dict['pt']['pf5Cat']) ?></p>
              <h3 class="font-display font-semibold mb-1" data-i18n="pf5Name2"><?= e($dict['pt']['pf5Name2']) ?></h3>
              <p class="text-sm text-muted-c mb-4" data-i18n="pf5Desc"><?= e($dict['pt']['pf5Desc']) ?></p>
            </div>
          </article>
<?php endif; ?>

          <?php if ($portfolio[6]['ativo']): ?>
<article class="reveal portfolio-item card rounded-2xl overflow-hidden group" data-category="<?= e($portfolio[6]['categoria']) ?>"
            style="transition-delay:.25s">
            <div class="h-44 relative overflow-hidden" style="background: linear-gradient(135deg, #fb8500, #420f74);">
              <?php if ($portfolio[6]['imagem'] !== ''): ?><img src="<?= e(image_path($portfolio[6]['imagem'])) ?>" alt="<?= e($dict['pt']['pf6Name']) ?>" class="absolute inset-0 w-full h-full object-cover" loading="lazy"><?php else: ?>
              <div
                class="absolute inset-0 flex items-center justify-center font-display font-semibold text-white/95 text-sm tracking-wide transition-transform duration-500 group-hover:scale-110"
                data-i18n="pf6Name"><?= e($dict['pt']['pf6Name']) ?></div>
              <?php endif; ?>
            </div>
            <div class="p-5">
              <p class="text-xs font-mono text-[var(--secondary)] mb-1" data-i18n="pf6Cat"><?= e($dict['pt']['pf6Cat']) ?></p>
              <h3 class="font-display font-semibold mb-1" data-i18n="pf6Name2"><?= e($dict['pt']['pf6Name2']) ?></h3>
              <p class="text-sm text-muted-c mb-4" data-i18n="pf6Desc"><?= e($dict['pt']['pf6Desc']) ?></p>
            </div>
          </article>
<?php endif; ?>
        </div>
      </div>
    </section>

    <!-- ============ PROJETO EM DESTAQUE ============ -->
    <section id="featured-project" class="py-28 max-w-7xl mx-auto px-5 md:px-8">
      <p class="font-mono text-xs text-[var(--secondary)] mb-3 tracking-wide" data-i18n="featuredEyebrow"><?= e($dict['pt']['featuredEyebrow']) ?></p>
      <h2 class="font-display font-semibold text-3xl md:text-4xl tracking-tight mb-12 reveal" data-i18n="featuredTitle"><?= e($dict['pt']['featuredTitle']) ?></h2>

      <div class="grid lg:grid-cols-2 gap-12 items-start">
        <div class="reveal-scale rounded-2xl overflow-hidden h-80 lg:h-full"
          style="background: linear-gradient(135deg, var(--primary) 0%, #2a1b47 50%, var(--secondary) 130%);">
          <?php if ($portfolio[1]['imagem_destaque'] !== ''): ?><img src="<?= e(image_path($portfolio[1]['imagem_destaque'])) ?>" alt="<?= e($dict['pt']['featuredTitle']) ?>" class="w-full h-full object-cover" loading="lazy"><?php else: ?><div class="w-full h-full flex items-center justify-center"><span class="font-display text-white/80 text-2xl tracking-wide" data-i18n="featuredTitle"><?= e($dict['pt']['featuredTitle']) ?></span></div><?php endif; ?>
        </div>
        <div class="reveal space-y-7">
          <div>
            <h3 class="font-display font-semibold mb-2" data-i18n="featDescTitle"><?= e($dict['pt']['featDescTitle']) ?></h3>
            <p class="text-secondary-c leading-relaxed text-sm" data-i18n="featDesc"><?= e($dict['pt']['featDesc']) ?></p>
          </div>
          <div>
            <h3 class="font-display font-semibold mb-2" data-i18n="featObjTitle"><?= e($dict['pt']['featObjTitle']) ?></h3>
            <ul class="text-sm text-secondary-c space-y-1.5 list-disc pl-5">
              <li data-i18n="featObj1"><?= e($dict['pt']['featObj1']) ?></li>
              <li data-i18n="featObj2"><?= e($dict['pt']['featObj2']) ?></li>
              <li data-i18n="featObj3"><?= e($dict['pt']['featObj3']) ?></li>
            </ul>
          </div>
          <div>
            <h3 class="font-display font-semibold mb-2" data-i18n="featTechTitle"><?= e($dict['pt']['featTechTitle']) ?></h3>
            <div class="flex flex-wrap gap-2" data-tech-tags>
              <?php foreach (explode("\n", $dict['pt']['featTechTags']) as $tag): ?><span class="text-xs font-mono px-3 py-1 rounded-full border border-c"><?= e($tag) ?></span><?php endforeach; ?>
            </div>
          </div>
          <div>
            <h3 class="font-display font-semibold mb-2" data-i18n="featResultsTitle"><?= e($dict['pt']['featResultsTitle']) ?></h3>
            <p class="text-sm text-secondary-c leading-relaxed" data-i18n="featResults"><?= e($dict['pt']['featResults']) ?></p>
          </div>
          <blockquote class="border-l-2 border-[var(--secondary)] pl-4 italic text-sm text-muted-c"
            data-i18n="featQuote"><?= e($dict['pt']['featQuote']) ?></blockquote>
        </div>
      </div>
    </section>

    <!-- ============ CHAMADA PARA AÇÃO ============ -->
    <section class="relative py-24 overflow-hidden">
      <div class="absolute inset-0"
        style="background: linear-gradient(120deg, var(--primary) 0%, #2a1b47 55%, var(--secondary) 130%); background-size: 200% 200%; animation: gradientShift 10s ease infinite;">
      </div>
      <div class="relative max-w-3xl mx-auto px-5 text-center text-white">
        <h2 class="reveal font-display font-semibold text-3xl md:text-4xl tracking-tight mb-4" data-i18n="ctaTitle"><?= e($dict['pt']['ctaTitle']) ?></h2>
        <p class="reveal text-white/85 mb-9" data-i18n="ctaSub"><?= e($dict['pt']['ctaSub']) ?></p>
        <div class="reveal flex flex-wrap justify-center gap-4">
          <a href="#contact"
            class="bg-white text-[var(--primary)] rounded-full px-7 py-3.5 font-semibold text-[15px] hover:bg-white/10 transition-colors"
            data-i18n="ctaBtn2"><?= e($dict['pt']['ctaBtn2']) ?></a>
        </div>
      </div>
    </section>

    <!-- ============ CONTATO — CHAMADA WHATSAPP ============ -->
    <section id="contact" class="relative py-28 overflow-hidden">
      <div class="absolute inset-0 grid-fade" aria-hidden="true"></div>
      <div class="glow-orb float-slow w-80 h-80 -top-16 -left-20" style="background: var(--primary); opacity:.25;" aria-hidden="true"></div>
      <div class="glow-orb float-slower w-72 h-72 bottom-[-60px] right-[-60px]" style="background: var(--secondary); opacity:.2;" aria-hidden="true"></div>
      <svg class="absolute inset-0 w-full h-full opacity-40" viewBox="0 0 1200 500" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <g stroke="var(--primary)" stroke-width="1" opacity="0.3">
          <line x1="140" y1="90" x2="330" y2="180" />
          <line x1="870" y1="120" x2="1060" y2="60" />
          <line x1="900" y1="380" x2="1080" y2="320" />
        </g>
        <g fill="var(--secondary)">
          <circle cx="140" cy="90" r="3" />
          <circle cx="330" cy="180" r="3" />
          <circle cx="1060" cy="60" r="3" />
          <circle cx="1080" cy="320" r="3" />
        </g>
      </svg>

      <div class="relative max-w-2xl mx-auto px-5 md:px-8 text-center">
        <p class="reveal font-mono text-xs text-[var(--secondary)] mb-3 tracking-wide" data-i18n="contactEyebrow"><?= e($dict['pt']['contactEyebrow']) ?></p>
        <h2 class="reveal font-display font-semibold text-3xl md:text-4xl tracking-tight mb-5" style="transition-delay:.05s" data-i18n="contactTitle"><?= e($dict['pt']['contactTitle']) ?></h2>
        <p class="reveal text-secondary-c leading-relaxed mb-10 max-w-lg mx-auto" style="transition-delay:.1s" data-i18n="contactSub"><?= e($dict['pt']['contactSub']) ?></p>

        <a
          data-whatsapp-link href="<?= e('https://wa.me/' . preg_replace('/[^0-9]/', '', $dict['pt']['contactWhatsapp']) . '?text=' . rawurlencode($dict['pt']['whatsappMessage'])) ?>"
          target="_blank" rel="noopener noreferrer"
          class="reveal whatsapp-cta-btn btn-primary rounded-full px-8 py-4 font-semibold text-[15px] inline-flex items-center gap-3 group"
          style="transition-delay:.15s"
          aria-label="Conversar com a Codora Tech pelo WhatsApp (abre em nova aba)">
          <svg width="21" height="21" viewBox="0 0 24 24" fill="#25D366" aria-hidden="true">
            <path d="M12.02 2C6.5 2 2 6.48 2 12c0 1.85.5 3.58 1.36 5.07L2 22l5.08-1.33A9.96 9.96 0 0012.02 22C17.54 22 22 17.52 22 12S17.54 2 12.02 2zm5.9 14.3c-.25.7-1.45 1.35-2 1.44-.5.08-1.15.11-1.86-.12-.43-.14-.98-.32-1.7-.63-2.97-1.28-4.9-4.27-5.05-4.47-.15-.2-1.2-1.6-1.2-3.05 0-1.46.76-2.17 1.03-2.47.27-.3.6-.37.8-.37.2 0 .4 0 .58.01.19.01.44-.07.68.53.25.6.85 2.08.92 2.23.07.15.12.33.02.53-.1.2-.15.32-.3.5-.15.17-.31.39-.44.52-.15.15-.3.31-.13.6.17.3.76 1.28 1.65 2.08 1.13 1.02 2.08 1.34 2.38 1.49.3.15.47.13.65-.08.18-.2.75-.87.95-1.17.2-.3.4-.25.68-.15.27.1 1.75.83 2.05 1 .3.15.5.23.57.35.07.13.07.75-.18 1.45z"/>
          </svg>
          <span data-i18n="ctaWhatsBtn"><?= e($dict['pt']['ctaWhatsBtn']) ?></span>
          <svg class="transition-transform duration-300 group-hover:translate-x-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>

        <div class="reveal flex flex-col sm:flex-row items-center justify-center gap-x-8 gap-y-3 mt-9 text-sm text-secondary-c" style="transition-delay:.2s">
          <span class="inline-flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#25D366] shrink-0" aria-hidden="true"></span><span data-i18n="ctaInfo1"><?= e($dict['pt']['ctaInfo1']) ?></span></span>
          <span class="inline-flex items-center gap-2"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" aria-hidden="true" class="shrink-0"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 9h18M8 3v4M16 3v4"/></svg><span data-i18n="ctaInfo2"><?= e($dict['pt']['ctaInfo2']) ?></span></span>
          <span class="inline-flex items-center gap-2"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="1.8" aria-hidden="true" class="shrink-0"><path d="M13 2L4 14h6l-1 8 9-12h-6l1-8z"/></svg><span data-i18n="ctaInfo3"><?= e($dict['pt']['ctaInfo3']) ?></span></span>
        </div>
      </div>
    </section>

  </main>

  <!-- ============ RODAPÉ ============ -->
  <footer class="border-t border-c bg-subtle">
    <div class="max-w-7xl mx-auto px-5 md:px-8 py-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
      <div>
        <a href="#home" class="flex items-center gap-2.5 font-display font-semibold text-lg mb-3">
          <img src="assets/img/brand/logoBranca.png" alt="Logo Codora Tech" class="h-7 w-auto object-contain">
        </a>
        <p class="text-sm text-muted-c" data-i18n="footerSlogan"><?= e($dict['pt']['footerSlogan']) ?></p>
      </div>
      <div>
        <h4 class="font-display font-semibold text-sm mb-4" data-i18n="footerNavTitle"><?= e($dict['pt']['footerNavTitle']) ?></h4>
        <ul class="space-y-2.5 text-sm text-secondary-c">
          <li><a href="#services" class="hover:text-[var(--primary)]" data-i18n="navServices"><?= e($dict['pt']['navServices']) ?></a></li>
          <li><a href="#about" class="hover:text-[var(--primary)]" data-i18n="navAbout"><?= e($dict['pt']['navAbout']) ?></a></li>
          <li><a href="#portfolio" class="hover:text-[var(--primary)]" data-i18n="navPortfolio"><?= e($dict['pt']['navPortfolio']) ?></a></li>
          <li><a href="#team" class="hover:text-[var(--primary)]" data-i18n="navTeam"><?= e($dict['pt']['navTeam']) ?></a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-display font-semibold text-sm mb-4" data-i18n="footerServicesTitle"><?= e($dict['pt']['footerServicesTitle']) ?></h4>
        <ul class="space-y-2.5 text-sm text-secondary-c">
          <li data-i18n="svc1Title"><?= e($dict['pt']['svc1Title']) ?></li>
          <li data-i18n="svc2Title"><?= e($dict['pt']['svc2Title']) ?></li>
          <li data-i18n="svc6Title"><?= e($dict['pt']['svc6Title']) ?></li>
        </ul>
      </div>
      <div>
        <h4 class="font-display font-semibold text-sm mb-4" data-i18n="footerContactTitle"><?= e($dict['pt']['footerContactTitle']) ?></h4>
        <ul class="space-y-2.5 text-sm text-secondary-c">
          <li><a href="<?= e('mailto:' . $dict['pt']['contactEmail']) ?>" data-i18n="contactEmail"><?= e($dict['pt']['contactEmail']) ?></a></li>
          <li data-i18n="contactWhatsapp"><?= e(display_phone($dict['pt']['contactWhatsapp'])) ?></li>
          <li data-i18n="footerLocation"><?= e($dict['pt']['footerLocation']) ?></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-c">
      <div class="max-w-7xl mx-auto px-5 md:px-8 py-6 text-xs text-muted-c flex flex-wrap gap-3 justify-between">
        <span>© 2026 Codora Tech. <span data-i18n="footerRights"><?= e($dict['pt']['footerRights']) ?></span></span>
        <span data-i18n="footerBuilt"><?= e($dict['pt']['footerBuilt']) ?></span>
      </div>
    </div>
  </footer>

  <div id="toast"
    class="toast fixed bottom-6 right-6 z-[60] bg-elevated border border-c rounded-xl px-5 py-3.5 shadow-lg text-sm font-medium max-w-xs"
    role="status" aria-live="polite"></div>

</body>

</html>
