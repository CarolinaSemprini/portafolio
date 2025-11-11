/**
 * scroll-to.js — Vanilla JS smooth anchors
 * Calcula offset del header y hace scroll suave.
 */

(function () {
  'use strict';

  function getHeaderHeight() {
    const header = document.querySelector('.site-header, header');
    return header ? header.offsetHeight : 0;
  }

  document.addEventListener('click', function (e) {
    const a = e.target.closest('a[href^="#"]');
    if (!a) return;
    const href = a.getAttribute('href');
    if (!href || href === '#') return;

    const target = document.querySelector(href);
    if (!target) return;

    e.preventDefault();

    const headerH = getHeaderHeight();
    const top = target.getBoundingClientRect().top + window.pageYOffset - (headerH + 10);

    window.scrollTo({
      top: Math.max(0, Math.floor(top)),
      behavior: 'smooth'
    });
  });
})();
