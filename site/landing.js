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

  /* ============ i18n ============ */
  var dict = {
    pt: {
      skipLink: "Pular para o conteúdo",
      navHome: "Início", navServices: "Serviços", navAbout: "Sobre Nós", navPortfolio: "Portfólio", navTeam: "Nossa Equipe", navContact: "Contato", navLogin: "Login", navCta: "Pedir Orçamento",
      heroEyebrow: "Software · Web · Automação",
      heroTitle1: "Transformamos ideias em", heroTitle2: "soluções digitais inteligentes.",
      heroSub: "Desenvolvemos software sob medida, sites profissionais e automações que fazem sua empresa crescer — sem soluções genéricas, sem enrolação.",
      heroBtn1: "Pedir Orçamento", heroBtn2: "Ver Portfólio",
      heroCodeLine1: "status: 'em produção',", heroCodeLine2: "qualidade: 'nível agência',", heroCodeLine3: "entrega: 'no prazo'",
      heroCardLabel: "Satisfação dos clientes",
      trust1: "Soluções sob medida", trust2: "Tecnologias modernas", trust3: "Suporte completo", trust4: "Design responsivo", trust5: "Entrega mais rápida", trust6: "Equipe especializada",
      servicesEyebrow: "// Serviços", servicesTitle: "O que fazemos por sua empresa", servicesSub: "Cada projeto é construído sob medida — nunca vendemos soluções prontas para problemas que são únicos.",
      svc1Title: "Desenvolvimento de Software", svc1Desc: "Sistemas sob medida que resolvem processos reais da sua operação, do zero ao deploy.",
      svc2Title: "Sites Profissionais", svc2Desc: "Sites rápidos, elegantes e otimizados para converter visitantes em clientes.",
      svc3Title: "Landing Pages", svc3Desc: "Páginas de alta conversão, pensadas para campanhas e lançamentos.",
      svc4Title: "Design Digital", svc4Desc: "Interfaces cuidadosamente desenhadas, com identidade visual própria.",
      svc5Title: "Identidade de Marca", svc5Desc: "Logotipo, paleta e diretrizes visuais que comunicam confiança.",
      svc6Title: "Automação de Processos", svc6Desc: "Eliminamos tarefas manuais repetitivas com fluxos automatizados.",
      svcCta: "Solicitar",
      aboutEyebrow: "// Sobre nós", aboutTitle: "Tecnologia com propósito",
      aboutP1: "A Codora Tech nasceu para ajudar empresas a crescer através da tecnologia — sem fórmulas prontas, sem soluções genéricas.",
      aboutP2: "Cada projeto é pensado a partir da realidade do cliente: seu processo, seu público e o problema específico que precisa ser resolvido.",
      aboutSlogan: "Soluções digitais desenhadas para pessoas.",
      missionTitle: "Missão", missionDesc: "Entregar soluções digitais inovadoras com qualidade, eficiência e atendimento personalizado.",
      visionTitle: "Visão", visionDesc: "Ser reconhecida como referência em inovação e satisfação dos clientes.",
      valuesTitle: "Valores", valuesDesc: "Inovação, compromisso, transparência, qualidade e melhoria contínua.",
      whyTitle: "Por que escolher a Codora Tech",
      why1Title: "Soluções Personalizadas", why1Desc: "Feito sob medida para seu negócio.",
      why2Title: "Tecnologias Modernas", why2Desc: "Stack atual, performática e segura.",
      why3Title: "Suporte Ágil", why3Desc: "Resposta rápida em cada etapa.",
      why4Title: "Parceria de Longo Prazo", why4Desc: "Acompanhamos o crescimento do cliente.",
      processEyebrow: "// Como trabalhamos", processTitle: "Do primeiro contato à entrega",
      step1Title: "Contato Inicial", step1Desc: "Você nos conta o desafio do seu negócio.",
      step2Title: "Entendimento do Cliente", step2Desc: "Mapeamos processos, público e objetivos.",
      step3Title: "Planejamento", step3Desc: "Definimos escopo, prazos e arquitetura.",
      step4Title: "Protótipo / Design", step4Desc: "Você visualiza e valida antes de desenvolver.",
      step5Title: "Desenvolvimento", step5Desc: "Construção com atualizações periódicas.",
      step6Title: "Testes", step6Desc: "Qualidade, performance e segurança verificadas.",
      step7Title: "Aprovação", step7Desc: "Ajustes finais junto com você.",
      step8Title: "Entrega", step8Desc: "Seu projeto no ar, pronto para uso.",
      step9Title: "Suporte Contínuo", step9Desc: "Estamos com você depois da entrega também.",
      portfolioEyebrow: "// Portfólio", portfolioTitle: "Projetos que já entregamos",
      filterAll: "Todos", filterSites: "Sites", filterSystems: "Sistemas", filterLanding: "Landing Pages", filterBrand: "Identidade de Marca",
      pf1Cat: "Sites", pf1Desc: "Site institucional para estúdio de paisagismo.", pfDetails: "Ver detalhes",
      pf2Cat: "Sistemas", pf2Name: "Sistema de Gestão", pf2Name2: "Gestão Financeira", pf2Desc: "Plataforma de controle financeiro para pequenas empresas.",
      pf3Name: "Lançamento de Produto", pf3Name2: "Página de Conversão", pf3Desc: "Landing page para campanha de lançamento.",
      pf4Name: "Rebranding Completo", pf4Name2: "Identidade Visual", pf4Desc: "Nova identidade de marca para rede de clínicas.",
      pf5Name: "Loja Institucional", pf5Name2: "Site Corporativo", pf5Desc: "Presença digital completa para distribuidora regional.",
      pf6Name: "Automação de Pedidos", pf6Name2: "Fluxo de Pedidos", pf6Desc: "Automação do processo de pedidos e estoque.",
      featuredEyebrow: "// Projeto em destaque", featuredTitle: "Golden Jardim",
      featDescTitle: "Sobre o projeto",
      featDesc: "A Golden Jardim precisava de uma presença digital que refletisse a qualidade dos seus projetos de paisagismo. Criamos um site institucional com galeria de portfólio, formulário de orçamento e otimização para buscas locais.",
      featObjTitle: "Objetivos", featObj1: "Aumentar solicitações de orçamento pelo site", featObj2: "Apresentar o portfólio de projetos com destaque visual", featObj3: "Melhorar o posicionamento em buscas regionais",
      featTechTitle: "Tecnologias utilizadas", featResultsTitle: "Resultados",
      featResults: "Aumento nas solicitações de orçamento e maior tempo de permanência no site após o lançamento.",
      featQuote: "\u201cDepoimento do cliente em breve.\u201d",
      teamEyebrow: "// Nossa equipe", teamTitle: "Quem constrói com você",
      team1Role: "Desenvolvedor de Software · UI Designer", team1Desc: "Responsável pela arquitetura de software, desenvolvimento e design de interfaces.",
      team2Role: "Desenvolvedor de Software · Gestão Administrativa e Financeira", team2Desc: "Responsável pelo desenvolvimento, organização de projetos e gestão financeira.",
      team3Role: "UI/UX Designer · Designer Gráfica", team3Desc: "Responsável pela identidade visual, design de interfaces e branding.",
      team4Role: "Gestão Administrativa e Financeira", team4Desc: "Responsável pela organização administrativa, atendimento e processos financeiros.",
      ctaTitle: "Pronto para transformar seu negócio?", ctaSub: "Vamos construir juntos a solução digital ideal para sua empresa.",
      ctaBtn1: "Pedir Orçamento", ctaBtn2: "Falar Conosco",
      contactEyebrow: "// Contato", contactTitle: "Seu próximo projeto começa com uma conversa.", contactSub: "Desenvolvemos sistemas, sites e soluções digitais pensadas para pessoas. Vamos entender sua necessidade e apresentar a melhor solução para o seu negócio.",
      ctaWhatsBtn: "Conversar com a Codora Tech", ctaInfo1: "Atendimento via WhatsApp", ctaInfo2: "Segunda a Sexta • 09h às 17h", ctaInfo3: "Primeira resposta em poucos minutos",
      fName: "Nome completo *", fCompany: "Empresa", fEmail: "E-mail *", fPhone: "Telefone", fWhats: "WhatsApp", fSegment: "Segmento do negócio",
      fService: "Serviço desejado *", fServiceOpt0: "Selecione...", fServiceOpt1: "Software sob medida", fServiceOpt2: "Site profissional", fServiceOpt3: "Landing page", fServiceOpt4: "Design digital", fServiceOpt5: "Identidade de marca", fServiceOpt6: "Automação de processos",
      fBudget: "Orçamento estimado", fBudgetOpt0: "A definir", fDeadline: "Prazo desejado", fDeadlinePh: "Ex: 2 meses", fFile: "Anexo (opcional)",
      fDesc: "Descrição do projeto *", fNotes: "Observações adicionais", fSubmit: "Enviar Solicitação",
      fSuccess: "Solicitação enviada com sucesso! Entraremos em contato em breve.",
      errName: "Informe seu nome completo.", errEmail: "Informe um e-mail válido.", errService: "Selecione um serviço.", errDesc: "Conte um pouco sobre seu projeto.",
      footerSlogan: "Soluções digitais desenhadas para pessoas.", footerNavTitle: "Navegação", footerServicesTitle: "Serviços", footerContactTitle: "Contato",
      footerLocation: "Campinas, SP — Brasil", footerRights: "Todos os direitos reservados.", footerBuilt: "Feito com tecnologia própria."
    },
    en: {
      skipLink: "Skip to content",
      navHome: "Home", navServices: "Services", navAbout: "About Us", navPortfolio: "Portfolio", navTeam: "Our Team", navContact: "Contact", navLogin: "Login", navCta: "Request a Quote",
      heroEyebrow: "Software · Web · Automation",
      heroTitle1: "Transforming ideas into", heroTitle2: "intelligent digital solutions.",
      heroSub: "We build custom software, professional websites and automations that help your business grow — no generic solutions, no fluff.",
      heroBtn1: "Request a Quote", heroBtn2: "View Portfolio",
      heroCodeLine1: "status: 'in production',", heroCodeLine2: "quality: 'agency-grade',", heroCodeLine3: "delivery: 'on time'",
      heroCardLabel: "Client satisfaction",
      trust1: "Custom solutions", trust2: "Modern technologies", trust3: "Complete support", trust4: "Responsive design", trust5: "Faster delivery", trust6: "Professional team",
      servicesEyebrow: "// Services", servicesTitle: "What we do for your business", servicesSub: "Every project is built to order — we never sell off-the-shelf answers to problems that are unique.",
      svc1Title: "Custom Software Development", svc1Desc: "Tailored systems that solve real processes in your operation, from scratch to deploy.",
      svc2Title: "Professional Websites", svc2Desc: "Fast, elegant websites optimized to turn visitors into clients.",
      svc3Title: "Landing Pages", svc3Desc: "High-conversion pages built for campaigns and launches.",
      svc4Title: "Digital Design", svc4Desc: "Carefully crafted interfaces with a visual identity of their own.",
      svc5Title: "Brand Identity", svc5Desc: "Logo, palette and visual guidelines that communicate trust.",
      svc6Title: "Business Automation", svc6Desc: "We remove repetitive manual tasks with automated workflows.",
      svcCta: "Get Started",
      aboutEyebrow: "// About us", aboutTitle: "Technology with purpose",
      aboutP1: "Codora Tech was created to help businesses grow through technology — no cookie-cutter formulas, no generic solutions.",
      aboutP2: "Every project starts from the client's reality: their process, their audience, and the specific problem that needs solving.",
      aboutSlogan: "Digital solutions designed for people.",
      missionTitle: "Mission", missionDesc: "Deliver innovative digital solutions with quality, efficiency and personalized service.",
      visionTitle: "Vision", visionDesc: "Become a trusted technology company recognized for innovation and customer satisfaction.",
      valuesTitle: "Values", valuesDesc: "Innovation, commitment, transparency, quality and continuous improvement.",
      whyTitle: "Why choose Codora Tech",
      why1Title: "Personalized Solutions", why1Desc: "Built to order for your business.",
      why2Title: "Modern Technologies", why2Desc: "A current, high-performing, secure stack.",
      why3Title: "Fast Support", why3Desc: "Quick response at every stage.",
      why4Title: "Long-Term Partnership", why4Desc: "We grow alongside our clients.",
      processEyebrow: "// How we work", processTitle: "From first contact to delivery",
      step1Title: "Initial Contact", step1Desc: "You tell us about your business challenge.",
      step2Title: "Understanding the Client", step2Desc: "We map processes, audience and goals.",
      step3Title: "Planning", step3Desc: "We define scope, timeline and architecture.",
      step4Title: "Prototype / Design", step4Desc: "You preview and approve before development.",
      step5Title: "Development", step5Desc: "Built with regular progress updates.",
      step6Title: "Testing", step6Desc: "Quality, performance and security checks.",
      step7Title: "Approval", step7Desc: "Final adjustments together with you.",
      step8Title: "Delivery", step8Desc: "Your project live and ready to use.",
      step9Title: "Ongoing Support", step9Desc: "We stay with you after delivery too.",
      portfolioEyebrow: "// Portfolio", portfolioTitle: "Projects we've delivered",
      filterAll: "All", filterSites: "Websites", filterSystems: "Systems", filterLanding: "Landing Pages", filterBrand: "Brand Identity",
      pf1Cat: "Websites", pf1Desc: "Institutional website for a landscaping studio.", pfDetails: "View details",
      pf2Cat: "Systems", pf2Name: "Management System", pf2Name2: "Financial Management", pf2Desc: "Financial control platform for small businesses.",
      pf3Name: "Product Launch", pf3Name2: "Conversion Page", pf3Desc: "Landing page for a launch campaign.",
      pf4Name: "Full Rebrand", pf4Name2: "Visual Identity", pf4Desc: "New brand identity for a clinic network.",
      pf5Name: "Institutional Store", pf5Name2: "Corporate Website", pf5Desc: "Full digital presence for a regional distributor.",
      pf6Name: "Order Automation", pf6Name2: "Order Workflow", pf6Desc: "Automation of the order and inventory process.",
      featuredEyebrow: "// Featured project", featuredTitle: "Golden Jardim",
      featDescTitle: "About the project",
      featDesc: "Golden Jardim needed a digital presence that reflected the quality of its landscaping work. We built an institutional website with a portfolio gallery, quote form and local search optimization.",
      featObjTitle: "Objectives", featObj1: "Increase quote requests through the website", featObj2: "Showcase the project portfolio with visual impact", featObj3: "Improve regional search rankings",
      featTechTitle: "Technologies used", featResultsTitle: "Results",
      featResults: "An increase in quote requests and longer average time on site after launch.",
      featQuote: "\u201cClient testimonial coming soon.\u201d",
      teamEyebrow: "// Our team", teamTitle: "Who builds with you",
      team1Role: "Software Developer · UI Designer", team1Desc: "Responsible for software architecture, development and interface design.",
      team2Role: "Software Developer · Admin & Financial Manager", team2Desc: "Responsible for software development, project organization and financial management.",
      team3Role: "UI/UX Designer · Graphic Designer", team3Desc: "Responsible for visual identity, interface design and branding.",
      team4Role: "Admin & Financial Manager", team4Desc: "Responsible for administrative organization, client service and financial processes.",
      ctaTitle: "Ready to transform your business?", ctaSub: "Let's build the ideal digital solution for your company.",
      ctaBtn1: "Request a Quote", ctaBtn2: "Talk to Us",
      contactEyebrow: "// Contact", contactTitle: "Your next project starts with a conversation.", contactSub: "We build systems, websites and digital solutions designed for people. Let's understand your needs and find the best solution for your business.",
      ctaWhatsBtn: "Chat with Codora Tech", ctaInfo1: "Support via WhatsApp", ctaInfo2: "Monday to Friday • 9am–5pm", ctaInfo3: "First reply within minutes",
      fName: "Full name *", fCompany: "Company", fEmail: "Email *", fPhone: "Phone", fWhats: "WhatsApp", fSegment: "Business segment",
      fService: "Service required *", fServiceOpt0: "Select...", fServiceOpt1: "Custom software", fServiceOpt2: "Professional website", fServiceOpt3: "Landing page", fServiceOpt4: "Digital design", fServiceOpt5: "Brand identity", fServiceOpt6: "Business automation",
      fBudget: "Estimated budget", fBudgetOpt0: "To be defined", fDeadline: "Desired deadline", fDeadlinePh: "E.g. 2 months", fFile: "Attachment (optional)",
      fDesc: "Project description *", fNotes: "Additional notes", fSubmit: "Send Request",
      fSuccess: "Request sent successfully! We'll be in touch soon.",
      errName: "Please enter your full name.", errEmail: "Please enter a valid email.", errService: "Please select a service.", errDesc: "Tell us a bit about your project.",
      footerSlogan: "Digital solutions designed for people.", footerNavTitle: "Navigation", footerServicesTitle: "Services", footerContactTitle: "Contact",
      footerLocation: "Campinas, SP — Brazil", footerRights: "All rights reserved.", footerBuilt: "Built with our own technology."
    }
  };

  function applyLang(lang) {
    document.documentElement.lang = lang === 'pt' ? 'pt-BR' : 'en-US';
    document.querySelectorAll('[data-i18n]').forEach(function (el) {
      var key = el.getAttribute('data-i18n');
      if (dict[lang][key] !== undefined) el.textContent = dict[lang][key];
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

  /* ============ Theme toggle ============ */
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
      setThemeIcons();
    });
  }

  /* ============ Sticky nav + scroll spy ============ */
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

  /* ============ Mobile menu ============ */
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

  /* ============ Reveal on scroll ============ */
  var revealItems = document.querySelectorAll('.reveal, .reveal-scale');
  var ro = new IntersectionObserver(function (entries, obs) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) { entry.target.classList.add('in-view'); obs.unobserve(entry.target); }
    });
  }, { threshold: 0.15 });
  revealItems.forEach(function (el) { ro.observe(el); });

  /* ============ Counters ============ */
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

  /* ============ Portfolio filter ============ */
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

  /* ============ Toast ============ */
  var toast = document.getElementById('toast');
  var toastTimer;
  function showToast(msg) {
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toast.classList.remove('show'); }, 4000);
  }

  /* ============ Login Modal Transition ============ */
  var loginOverlay = document.getElementById('login-modal-overlay');
  var loginCard = document.getElementById('login-modal-card');
  var openLoginBtn = document.getElementById('open-login-modal');
  var openLoginMobileBtn = document.getElementById('open-login-modal-mobile');
  var closeLoginBtn = document.getElementById('close-login-modal');

  function openLoginModal(e) {
    if (e) e.preventDefault();
    if (mobileMenu) mobileMenu.classList.remove('open');
    if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'false');
    if (loginOverlay && loginCard) {
      loginOverlay.classList.remove('pointer-events-none', 'opacity-0');
      loginOverlay.classList.add('opacity-100');
      loginCard.classList.remove('scale-95');
      loginCard.classList.add('scale-100');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeLoginModal() {
    if (loginOverlay && loginCard) {
      loginOverlay.classList.remove('opacity-100');
      loginOverlay.classList.add('opacity-0');
      loginCard.classList.remove('scale-100');
      loginCard.classList.add('scale-95');
      loginOverlay.classList.add('pointer-events-none');
      document.body.style.overflow = '';
    }
  }

  if (openLoginBtn) openLoginBtn.addEventListener('click', openLoginModal);
  if (openLoginMobileBtn) openLoginMobileBtn.addEventListener('click', openLoginModal);
  if (closeLoginBtn) closeLoginBtn.addEventListener('click', closeLoginModal);
  if (loginOverlay) {
    loginOverlay.addEventListener('click', function (e) {
      if (e.target === loginOverlay) closeLoginModal();
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLoginModal();
  });

  // Modal password toggle
  var modalPwInput = document.getElementById('modal-login-password');
  var modalToggleBtn = document.getElementById('modal-toggle-password');
  var modalEyeOpen = document.getElementById('modal-eye-open');
  var modalEyeClosed = document.getElementById('modal-eye-closed');
  if (modalToggleBtn && modalPwInput) {
    modalToggleBtn.addEventListener('click', function () {
      var showing = modalPwInput.type === 'text';
      modalPwInput.type = showing ? 'password' : 'text';
      if (modalEyeOpen) modalEyeOpen.classList.toggle('hidden', !showing);
      if (modalEyeClosed) modalEyeClosed.classList.toggle('hidden', showing);
    });
  }

  // Modal form submit simulation
  var modalForm = document.getElementById('modal-login-form');
  var modalLoginBtn = document.getElementById('modal-login-btn');
  var modalBtnLabel = document.getElementById('modal-login-btn-label');
  var modalSpinner = document.getElementById('modal-login-spinner');
  if (modalForm) {
    modalForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (modalLoginBtn) modalLoginBtn.disabled = true;
      if (modalSpinner) modalSpinner.classList.remove('hidden');
      if (modalBtnLabel) modalBtnLabel.textContent = 'Entrando...';
      setTimeout(function () {
        if (modalSpinner) modalSpinner.classList.add('hidden');
        if (modalBtnLabel) modalBtnLabel.textContent = 'Entrar no painel';
        if (modalLoginBtn) modalLoginBtn.disabled = false;
        closeLoginModal();
        showToast('Login realizado com sucesso!');
      }, 1200);
    });
  }
});
