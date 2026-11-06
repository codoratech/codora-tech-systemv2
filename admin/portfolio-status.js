document.addEventListener('DOMContentLoaded', function () {
  const notice = document.getElementById('portfolio-status-notice');
  let timer;
  document.querySelectorAll('[data-project-toggle]').forEach(button => {
    button.addEventListener('click', async () => {
      const id = button.dataset.projectToggle;
      const active = button.getAttribute('aria-checked') !== 'true';
      document.querySelectorAll('[data-project-toggle]').forEach(b => b.disabled = true);
      try {
        const response = await fetch('portfolio-status.php', {
          method: 'POST', credentials: 'same-origin',
          body: new URLSearchParams({id, ativo: active ? '1' : '0', csrf: document.querySelector('[name="csrf"]').value})
        });
        if (response.redirected) throw new Error('Sessão expirada. Recarregue a página para entrar novamente.');
        const data = await response.json();
        if (!response.ok) throw new Error(data.message);
        button.setAttribute('aria-checked', String(!!data.ativo));
        button.querySelector('.project-status').textContent = data.ativo ? 'Ativo' : 'Inativo';
        document.querySelectorAll('[data-project="'+id+'"], [data-project-fields="'+id+'"]').forEach(el => el.dataset.active = String(data.ativo));
        notice.textContent = 'Projeto '+id+(data.ativo ? ' ativado.' : ' inativado.');
      } catch (error) {
        notice.textContent = error.message || 'Não foi possível salvar. Tente novamente.';
      } finally {
        document.querySelectorAll('[data-project-toggle]').forEach(b => b.disabled = false);
        notice.hidden = false;
        clearTimeout(timer);
        timer = setTimeout(() => notice.hidden = true, 5000);
      }
    });
  });
});
