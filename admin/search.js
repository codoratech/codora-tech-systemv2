(function () {
  'use strict';
  // O mapa preserva offsets do texto original, inclusive caracteres acentuados.
  function mapped(text) {
    var value = '', starts = [], ends = [], offset = 0;
    for (var char of String(text)) {
      var folded = char.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
      for (var part of folded) { value += part; starts.push(offset); ends.push(offset + char.length); }
      offset += char.length;
    }
    return {value: value, starts: starts, ends: ends};
  }
  function ranges(text, term) {
    var map = mapped(text), query = mapped(term.trim()).value, found = [], pos = 0;
    if (query.length < 2) return found;
    while ((pos = map.value.indexOf(query, pos)) !== -1) {
      found.push([map.starts[pos], map.ends[pos + query.length - 1]]); pos += query.length;
    }
    return found;
  }
  function search(index, term) {
    return index.filter(function (item) { return ['text', 'label', 'key'].some(function (field) { return ranges(item[field], term).length; }); });
  }
  if (typeof module !== 'undefined' && module.exports) module.exports = {search: search, ranges: ranges};
  if (typeof document === 'undefined') return;
  document.addEventListener('DOMContentLoaded', function () {
    var dialog = document.getElementById('site-search');
    if (!dialog) return;
    var data = JSON.parse(document.getElementById('site-search-data').textContent);
    var input = document.getElementById('site-search-input'), list = document.getElementById('site-search-results');
    var status = document.getElementById('site-search-status'), opener = document.getElementById('search-open');
    var hits = [], selected = -1, timer, previousFocus;
    function open() { previousFocus = document.activeElement; if (!dialog.open) dialog.showModal(); input.focus(); input.select(); }
    opener.addEventListener('click', open);
    document.getElementById('search-close').addEventListener('click', function () { dialog.close(); });
    dialog.addEventListener('close', function () { clearTimeout(timer); if (previousFocus) previousFocus.focus(); });
    dialog.addEventListener('click', function (event) { if (event.target === dialog) { var rect = dialog.getBoundingClientRect(); if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) dialog.close(); } });
    document.addEventListener('keydown', function (event) { if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') { event.preventDefault(); open(); } });
    function highlight(node, text, term, snippet) {
      var matches = ranges(text, term), start = snippet && matches.length ? Math.max(0, matches[0][0] - 45) : 0;
      var end = snippet ? Math.min(text.length, start + 180) : text.length, cursor = start;
      if (start) node.appendChild(document.createTextNode('…'));
      matches.forEach(function (range) {
        if (range[0] < start || range[0] >= end) return;
        node.appendChild(document.createTextNode(text.slice(cursor, range[0])));
        var mark = document.createElement('mark'); mark.textContent = text.slice(range[0], Math.min(end, range[1])); node.appendChild(mark); cursor = Math.min(end, range[1]);
      });
      node.appendChild(document.createTextNode(text.slice(cursor, end) || (text ? '' : 'Sem valor preenchido')));
      if (end < text.length) node.appendChild(document.createTextNode('…'));
    }
    function select(position) {
      selected = position;
      Array.from(list.children).forEach(function (el, i) { el.setAttribute('aria-selected', String(i === selected)); });
      if (selected >= 0) { input.setAttribute('aria-activedescendant', list.children[selected].id); list.children[selected].scrollIntoView({block: 'nearest'}); }
      else input.removeAttribute('aria-activedescendant');
    }
    function focusTarget(target) {
      var field = document.getElementById(target); if (!field) return;
      field.scrollIntoView({block: 'center', behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'});
      field.focus({preventScroll: true}); field.classList.remove('search-target'); void field.offsetWidth; field.classList.add('search-target');
      setTimeout(function () { field.classList.remove('search-target'); }, 4500);
    }
    function navigate(item) {
      var url = new URL('editor.php', location.href); url.searchParams.set('secao', item.section); url.searchParams.set('campo', item.target);
      if (location.pathname.endsWith('/editor.php') && new URLSearchParams(location.search).get('secao') === item.section) { dialog.close(); focusTarget(item.target); }
      else location.assign(url.href);
    }
    function render() {
      clearTimeout(timer); timer = null; list.replaceChildren(); hits = []; selected = -1; input.removeAttribute('aria-activedescendant'); input.setAttribute('aria-expanded', 'false');
      if (data === null) { status.textContent = 'Busca indisponível. Tente recarregar o painel.'; return; }
      if (input.value.trim().length < 2) { status.textContent = 'Digite pelo menos 2 caracteres.'; return; }
      hits = search(data, input.value); status.textContent = hits.length ? hits.length + ' resultados encontrados.' : 'Nenhum resultado encontrado';
      hits.forEach(function (item, i) {
        var option = document.createElement('a'); option.id = 'search-result-' + i; option.className = 'search-result'; option.setAttribute('role', 'option'); option.setAttribute('aria-selected', 'false');
        var url = new URL('editor.php', location.href); url.searchParams.set('secao', item.section); url.searchParams.set('campo', item.target); option.href = url.href;
        var meta = document.createElement('small'); meta.textContent = item.sectionLabel + ' · ' + item.lang; option.appendChild(meta);
        var title = document.createElement('strong'); highlight(title, item.label, input.value, false); option.appendChild(title);
        var key = document.createElement('code'); highlight(key, item.key, input.value, false); option.appendChild(key);
        var excerpt = document.createElement('span'); highlight(excerpt, item.text, input.value, true); option.appendChild(excerpt);
        option.addEventListener('click', function (event) { if (!event.ctrlKey && !event.metaKey && !event.shiftKey && event.button === 0) { event.preventDefault(); navigate(item); } });
        option.addEventListener('focus', function () { select(i); }); list.appendChild(option);
      });
      input.setAttribute('aria-expanded', String(hits.length > 0)); if (hits.length) select(0);
    }
    input.addEventListener('input', function () { clearTimeout(timer); hits = []; list.replaceChildren(); selected = -1; input.removeAttribute('aria-activedescendant'); input.setAttribute('aria-expanded', 'false'); status.textContent = input.value.trim().length < 2 ? 'Digite pelo menos 2 caracteres.' : 'Buscando…'; timer = setTimeout(render, 250); });
    dialog.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') { event.preventDefault(); dialog.close(); return; }
      if (event.key === 'ArrowDown' || event.key === 'ArrowUp') { event.preventDefault(); if (timer) render(); if (hits.length) { input.focus(); select((selected + (event.key === 'ArrowDown' ? 1 : -1) + hits.length) % hits.length); } }
      if (event.key === 'Enter' && event.target === input) { event.preventDefault(); if (timer) render(); if (hits.length) navigate(hits[Math.max(0, selected)]); }
    });
    var target = new URLSearchParams(location.search).get('campo'); if (target) focusTarget(target);
  });
})();
