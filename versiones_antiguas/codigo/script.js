document.addEventListener('DOMContentLoaded', () => {
  const navLinks = document.querySelectorAll('.nav-link');

  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = link.dataset.target || (link.getAttribute('href') || '').replace('#', '');
      if (!targetId) return;

      // quitar activos
      document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
      document.querySelectorAll('.container-section').forEach(sec => sec.classList.remove('active-section'));

      // activar seleccionado (si existe)
      link.classList.add('active');
      const targetEl = document.getElementById(targetId);
      if (targetEl) targetEl.classList.add('active-section');
    });
  });
});