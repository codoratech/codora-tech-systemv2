document.addEventListener('DOMContentLoaded', function () {
  var theme = document.getElementById('theme-toggle');
  function syncTheme() {
    var dark = document.documentElement.classList.contains('dark');
    if (theme) { theme.setAttribute('aria-pressed', String(dark)); theme.setAttribute('aria-label', dark ? 'Ativar tema claro' : 'Ativar tema escuro'); }
    var sun = document.getElementById('theme-icon-sun');
    var moon = document.getElementById('theme-icon-moon');
    if (sun) sun.classList.toggle('hidden', !dark);
    if (moon) moon.classList.toggle('hidden', dark);
  }
  syncTheme();
  if (theme) theme.addEventListener('click', function () {
    var dark = document.documentElement.classList.toggle('dark');
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
    try { localStorage.setItem('codora-theme', dark ? 'dark' : 'light'); } catch (_) {}
    syncTheme();
  });
  var toggle = document.getElementById('toggle-password');
  if (toggle) toggle.addEventListener('click', function () {
    var input = document.getElementById('login-password');
    var visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    document.getElementById('eye-open').classList.toggle('hidden', visible);
    document.getElementById('eye-closed').classList.toggle('hidden', !visible);
    toggle.setAttribute('aria-label', visible ? 'Ocultar senha' : 'Mostrar senha');
    toggle.setAttribute('aria-pressed', String(visible));
  });
  // O navegador envia o POST real. O indicador só acompanha o envio.
  var form = document.getElementById('login-form');
  if (form) form.addEventListener('submit', function () {
    document.getElementById('login-btn').disabled = true;
    document.getElementById('login-spinner').classList.remove('hidden');
    document.getElementById('login-btn-label').textContent = 'Entrando...';
  });
});
