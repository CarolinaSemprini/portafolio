/**
 * nav.js — Vanilla JS
 * Responsive menu toggle + basic scrollspy
 * Requisitos: ninguno (vanilla JS)
 */

(function () {
  'use strict';

  // Helpers
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  document.addEventListener('DOMContentLoaded', () => {
    const toggle = $('.nav-toggle');         // botón hamburguer
    const nav = $('.main-nav');             // contenedor del nav
    const body = document.body;
    const header = document.querySelector('.site-header') || null;

    // Accessible toggle (si existe)
    if (toggle && nav) {
      toggle.addEventListener('click', () => {
        const opened = nav.classList.toggle('open');
        toggle.setAttribute('aria-expanded', opened ? 'true' : 'false');
        body.classList.toggle('menu-open', opened);
      });
    }

    // Cerrar menú al hacer click en link (mobile)
    document.addEventListener('click', (e) => {
      const a = e.target.closest('.main-nav a[href^="#"]');
      if (!a) return;
      if (window.innerWidth <= 900 && nav && nav.classList.contains('open')) {
        nav.classList.remove('open');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
        body.classList.remove('menu-open');
      }
    });

    // SCROLLSPY: observa secciones con id que coincidan con links del menu
    const menuLinks = $$('.main-nav a[href^="#"]');
    const sectionIds = menuLinks.map(l => l.getAttribute('href')).filter(h => h && h.startsWith('#'));
    const sections = sectionIds.map(id => document.querySelector(id)).filter(Boolean);

    if ('IntersectionObserver' in window && sections.length) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          const id = '#' + entry.target.id;
          const link = document.querySelector('.main-nav a[href="' + id + '"]');
          if (entry.isIntersecting) {
            // marca activo
            document.querySelectorAll('.main-nav a.is-active').forEach(a => a.classList.remove('is-active'));
            if (link) link.classList.add('is-active');
          } else {
            if (link) link.classList.remove('is-active');
          }
        });
      }, { root: null, threshold: 0.35 });

      sections.forEach(s => observer.observe(s));
    }

    // Añadir clase .sticky si el header se "pega"
    if (header) {
      let lastScroll = 0;
      window.addEventListener('scroll', () => {
        const st = window.scrollY || window.pageYOffset;
        if (st > 60) header.classList.add('scrolled'); else header.classList.remove('scrolled');
        lastScroll = st;
      }, { passive: true });
    }
  });
})();
