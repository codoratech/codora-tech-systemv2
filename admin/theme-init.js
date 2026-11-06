(function () {
  var saved;
  try { saved = localStorage.getItem('codora-theme'); } catch (_) {}
  var dark = saved === 'dark' || (saved !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches);
  document.documentElement.classList.toggle('dark', dark);
  document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
})();
