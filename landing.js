(function () {
  var stored = localStorage.getItem('codora-theme');
  var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  var theme = stored || (prefersDark ? 'dark' : 'light');
  if (theme === 'dark') document.documentElement.classList.add('dark');
  document.documentElement.setAttribute('data-theme', theme);
})();

if (typeof tailwind !== 'undefined') {
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        fontFamily: {
          display: ['"Space Grotesk"', 'sans-serif'],
          body: ['Inter', 'sans-serif'],
          mono: ['"JetBrains Mono"', 'monospace'],
        },
      },
    },
  };
}

document.addEventListener('DOMContentLoaded', function () {
  "use strict";

  /* ============ INTERNACIONALIZAÇÃO ============ */
  var dict = window.CODORA_I18N || {pt: {}, en: {}};

  function displayPhone(value) {
    var digits = String(value).replace(/[^0-9]/g, '');
    var parts = digits.match(/^55([0-9]{2})([0-9]{8,9})$/) || digits.match(/^([0-9]{2})([0-9]{8,9})$/);
    return parts ? '+55 ' + parts[1] + ' ' + parts[2].slice(0, -4) + '-' + parts[2].slice(-4) : value;
  }

  function applyLang(lang) {
    if (lang !== 'pt' && lang !== 'en') lang = 'pt';
    document.querySelectorAll('[data-tech-tags]').forEach(function (container) {
      container.replaceChildren();
      (dict[lang].featTechTags || '').split('\n').filter(Boolean).forEach(function (tag) {
        var span = document.createElement('span');
        span.className = 'text-xs font-mono px-3 py-1 rounded-full border border-c';
        span.textContent = tag;
        container.appendChild(span);
      });
    });
    document.querySelectorAll('[data-whatsapp-link]').forEach(function (link) {
      link.href = 'https://wa.me/' + String(dict[lang].contactWhatsapp).replace(/[^0-9]/g, '') + '?text=' + encodeURIComponent(dict[lang].whatsappMessage);
    });
    document.documentElement.lang = lang === 'pt' ? 'pt-BR' : 'en-US';
    document.querySelectorAll('[data-i18n]').forEach(function (el) {
      var key = el.getAttribute('data-i18n');
      if (dict[lang][key] !== undefined) el.textContent = key === 'contactWhatsapp' ? displayPhone(dict[lang][key]) : dict[lang][key];
    });
    document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
      var key = el.getAttribute('data-i18n-placeholder');
      if (dict[lang][key] !== undefined) el.setAttribute('placeholder', dict[lang][key]);
    });
    document.querySelectorAll('[data-lang-btn]').forEach(function (btn) {
      btn.setAttribute('aria-pressed', btn.getAttribute('data-lang-btn') === lang ? 'true' : 'false');
    });
    localStorage.setItem('codora-lang', lang);
  }

  var savedLang = localStorage.getItem('codora-lang') || (navigator.language && navigator.language.toLowerCase().indexOf('pt') === 0 ? 'pt' : 'en');
  applyLang(savedLang);
  document.querySelectorAll('[data-lang-btn]').forEach(function (btn) {
    btn.addEventListener('click', function () { applyLang(btn.getAttribute('data-lang-btn')); });
  });

  /* ============ ALTERNADOR DE TEMA ============ */
  var themeBtn = document.getElementById('theme-toggle');
  var sunIcon = document.getElementById('theme-icon-sun');
  var moonIcon = document.getElementById('theme-icon-moon');
  function setThemeIcons() {
    var isDark = document.documentElement.classList.contains('dark');
    if (sunIcon) sunIcon.classList.toggle('hidden', !isDark);
    if (moonIcon) moonIcon.classList.toggle('hidden', isDark);
    if (themeBtn) themeBtn.setAttribute('aria-pressed', String(isDark));
  }
  setThemeIcons();
  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      var isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('codora-theme', isDark ? 'dark' : 'light');
      document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
      setThemeIcons();
    });
  }

  /* ============ NAVEGAÇÃO FIXA E RASTREAMENTO DE ROLAGEM ============ */
  var nav = document.getElementById('site-nav');
  var sections = Array.prototype.slice.call(document.querySelectorAll('main section[id], section[id]'));
  var navLinks = Array.prototype.slice.call(document.querySelectorAll('.nav-link'));

  if (nav) {
    window.addEventListener('scroll', function () {
      nav.classList.toggle('scrolled', window.scrollY > 12);
    }, { passive: true });
  }

  var spy = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        var id = entry.target.getAttribute('id');
        navLinks.forEach(function (l) {
          l.classList.toggle('active', l.getAttribute('href') === '#' + id);
        });
      }
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  sections.forEach(function (s) { spy.observe(s); });

  /* ============ MENU MÓVEL ============ */
  var mobileBtn = document.getElementById('mobile-menu-btn');
  var mobileMenu = document.getElementById('mobile-menu');
  if (mobileBtn && mobileMenu) {
    mobileBtn.addEventListener('click', function () {
      var open = mobileMenu.classList.toggle('open');
      mobileBtn.setAttribute('aria-expanded', String(open));
    });
    document.querySelectorAll('.mobile-link').forEach(function (l) {
      l.addEventListener('click', function () { mobileMenu.classList.remove('open'); mobileBtn.setAttribute('aria-expanded', 'false'); });
    });
  }

  /* ============ REVELAR AO ROLAR ============ */
  var revealItems = document.querySelectorAll('.reveal, .reveal-scale');
  var ro = new IntersectionObserver(function (entries, obs) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) { entry.target.classList.add('in-view'); obs.unobserve(entry.target); }
    });
  }, { threshold: 0.15 });
  revealItems.forEach(function (el) { ro.observe(el); });

  /* ============ CONTADORES ============ */
  var counters = document.querySelectorAll('.counter-num');
  var counterObs = new IntersectionObserver(function (entries, obs) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      var el = entry.target;
      var target = parseInt(el.getAttribute('data-count'), 10) || 0;
      var suffix = el.getAttribute('data-suffix') || '';
      var start = 0;
      var duration = 1400;
      var startTime = null;
      function step(ts) {
        if (!startTime) startTime = ts;
        var progress = Math.min((ts - startTime) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.round(start + (target - start) * eased) + suffix;
        if (progress < 1) requestAnimationFrame(step); else el.textContent = target + suffix;
      }
      requestAnimationFrame(step);
      obs.unobserve(el);
    });
  }, { threshold: 0.4 });
  counters.forEach(function (el) { counterObs.observe(el); });

  /* ============ FILTRO DE PORTFÓLIO ============ */
  var filterBtns = document.querySelectorAll('.portfolio-filter-btn');
  var portfolioItems = document.querySelectorAll('.portfolio-item');
  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      filterBtns.forEach(function (b) { b.setAttribute('aria-pressed', 'false'); });
      btn.setAttribute('aria-pressed', 'true');
      var filter = btn.getAttribute('data-filter');
      portfolioItems.forEach(function (item) {
        var show = filter === 'all' || item.getAttribute('data-category') === filter;
        item.style.display = show ? '' : 'none';
      });
    });
  });

  /* ============ NOTIFICAÇÕES (TOAST) ============ */
  var toast = document.getElementById('toast');
  var toastTimer;
  function showToast(msg) {
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toast.classList.remove('show'); }, 4000);
  }

  /* Acesso ao login real */
  ['open-login-modal', 'open-login-modal-mobile'].forEach(function (id) {
    var link = document.getElementById(id);
    if (link) link.addEventListener('click', function (event) { event.preventDefault(); window.location.assign('login.php'); });
  });
});
