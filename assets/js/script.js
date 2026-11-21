document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const nav = document.querySelector('[data-nav]');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const opened = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', opened ? 'true' : 'false');
      toggle.textContent = opened ? '×' : '☰';
    });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
      nav.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.textContent = '☰';
    }));
  }

  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
      if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
  });

  document.querySelectorAll('[data-preview]').forEach(input => {
    input.addEventListener('change', () => {
      const img = document.querySelector('[data-image-preview]');
      if (!img) return;
      const file = input.files && input.files[0];
      if (file) {
        if (img.dataset.objectUrl) URL.revokeObjectURL(img.dataset.objectUrl);
        img.dataset.objectUrl = URL.createObjectURL(file);
        img.src = img.dataset.objectUrl;
        img.hidden = false;
      } else {
        img.hidden = true;
        img.removeAttribute('src');
      }
    });
  });

  // Aplikasi hanya menyediakan dua tema: light dan dark.
  const themeButtons = document.querySelectorAll('[data-theme-toggle]');
  const applyTheme = theme => {
    if (theme === 'dark') document.documentElement.dataset.theme = 'dark';
    else delete document.documentElement.dataset.theme;

    themeButtons.forEach(button => {
      const dark = theme === 'dark';
      const icon = button.querySelector('.theme-icon');
      const label = button.querySelector('[data-theme-label]');
      if (icon) icon.textContent = dark ? '☀' : '☾';
      if (label) label.textContent = dark ? 'Mode Terang' : 'Mode Gelap';
      button.setAttribute('aria-label', dark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
      button.setAttribute('aria-pressed', dark ? 'true' : 'false');
    });
  };

  let savedTheme = 'light';
  try {
    savedTheme = localStorage.getItem('lw-theme') === 'dark' ? 'dark' : 'light';
  } catch (_) {}
  applyTheme(savedTheme);

  themeButtons.forEach(button => {
    button.addEventListener('click', () => {
      const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
      applyTheme(nextTheme);
      try { localStorage.setItem('lw-theme', nextTheme); } catch (_) {}
    });
  });
});
