/**
 * scroll-effects.js — Vanilla JS
 * - Revelado eficiente de elementos con .fade-in
 * - Marca orientación de imágenes (portrait / landscape)
 */

(function () {
  'use strict';

  function revealInit() {
    const items = document.querySelectorAll('.fade-in');

    if (!items.length) return;

    // Fallback: si no hay IntersectionObserver, mostramos todo
    if (!('IntersectionObserver' in window)) {
      items.forEach(el => el.classList.add('visible'));
      return;
    }

    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    items.forEach(el => io.observe(el));
  }

  // Imagen orientation utility (portrait/landscape)
  function imageOrientation() {
    const imgs = document.querySelectorAll('img[data-orient]');
    imgs.forEach(img => {
      function setClass() {
        const w = img.naturalWidth, h = img.naturalHeight;
        if (!w || !h) return;
        img.classList.toggle('is-portrait', h > w);
        img.classList.toggle('is-landscape', w >= h);
      }
      if (img.complete) setClass(); else img.addEventListener('load', setClass);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    revealInit();
    imageOrientation();
  });

})();
