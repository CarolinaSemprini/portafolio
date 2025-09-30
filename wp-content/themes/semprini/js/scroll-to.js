document.addEventListener('click', function (e) {
  const a = e.target.closest('a[href^="#"]');
  if (!a) return;
  const id = a.getAttribute('href');
  if (id.length <= 1) return;

  const target = document.querySelector(id);
  if (!target) return;

  e.preventDefault();

  // Altura del header (ajusta si tu nav tiene otra altura)
  const header = document.querySelector('.site-header, header, .nav-glass');
  const headerH = header ? header.offsetHeight : 0;

  const y = target.getBoundingClientRect().top + window.pageYOffset - (headerH + 10);
  window.scrollTo({ top: y, behavior: 'smooth' });
});
