/**
 * Codora Tech — light/dark theme switcher.
 * Persists preference in localStorage and toggles the same
 * `light-style` / `dark-style` classes the template already uses.
 */
'use strict';

(function () {
  var STORAGE_KEY = 'codora-theme';

  function applyTheme(theme) {
    var html = document.documentElement;
    html.classList.remove('light-style', 'dark-style');
    html.classList.add(theme + '-style');
    html.setAttribute('data-bs-theme', theme);

    var icon = document.getElementById('codoraThemeIcon');
    if (icon) {
      icon.classList.remove('bx-sun', 'bx-moon');
      icon.classList.add(theme === 'dark' ? 'bx-sun' : 'bx-moon');
    }
  }

  function getTheme() {
    return localStorage.getItem(STORAGE_KEY) || 'light';
  }

  function setTheme(theme) {
    localStorage.setItem(STORAGE_KEY, theme);
    applyTheme(theme);
  }

  // Apply immediately (before DOMContentLoaded) to avoid a flash of the wrong theme.
  applyTheme(getTheme());

  document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('codoraThemeToggle');
    if (toggle) {
      toggle.addEventListener('click', function () {
        setTheme(getTheme() === 'dark' ? 'light' : 'dark');
      });
    }
  });

  window.codoraTheme = { setTheme: setTheme, getTheme: getTheme };
})();
