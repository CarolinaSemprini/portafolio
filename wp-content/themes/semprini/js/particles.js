/* =========================================================
   particles.js — Versión optimizada para todo el sitio
   - Paleta brillante y animación en remolino
   - Partículas persistentes, suaves y fluidas
   - Totalmente responsive (móvil y escritorio)
   ========================================================= */

(function () {
  'use strict';

  const CONFIG = {
    colors: [
      'rgba(0, 255, 255, 0.47)',   // cian brillante
      'rgba(173, 216, 230, 0.35)', // celeste suave
      'rgba(0, 179, 255, 0.4)',    // azul eléctrico
      'rgba(255, 255, 255, 0.31)',  // blanco luminoso
      'rgba(61, 139, 241, 0.27)', // blush rosado (detalle)
      'rgba(13, 247, 255, 0.17)'  // rosa intenso
    ],
    countBase: 120,    // cantidad en escritorio
    countMobile: 30,   // cantidad en móvil
    speed: 0.2,        // velocidad general
    sizeMin: 1.5,
    sizeMax: 3.8,
    swirlFactor: 0.001, // fuerza del remolino
    alphaDecay: 0.001,  // menor = partículas más persistentes
    glow: true
  };

  const isMobile = window.innerWidth <= 768;
  const canvas = document.getElementById('global-particles');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  let particles = [];
  let w = 0, h = 0, dpr = Math.max(1, window.devicePixelRatio || 1);
  let rafId = null;
  let tick = 0;

  function resize() {
    w = window.innerWidth;
    h = window.innerHeight;
    canvas.width = Math.round(w * dpr);
    canvas.height = Math.round(h * dpr);
    canvas.style.width = w + 'px';
    canvas.style.height = h + 'px';
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function rand(min, max) {
    return Math.random() * (max - min) + min;
  }

  function createParticle() {
    const size = rand(CONFIG.sizeMin, CONFIG.sizeMax);
    const x = Math.random() * w;
    const y = Math.random() * h;
    const color = CONFIG.colors[Math.floor(Math.random() * CONFIG.colors.length)];
    const angle = Math.random() * Math.PI * 2;
    return { x, y, vx: Math.cos(angle)*0.3, vy: Math.sin(angle)*0.3, size, color, age: 0 };
  }

  function initParticles() {
    particles = [];
    const count = isMobile ? CONFIG.countMobile : CONFIG.countBase;
    for (let i = 0; i < count; i++) particles.push(createParticle());
  }

  function step() {
    ctx.clearRect(0, 0, w, h);
    tick += 0.01;

    for (let i = 0; i < particles.length; i++) {
      const p = particles[i];

      // Movimiento tipo remolino con viento sutil
      const angle = Math.sin(tick + p.y * CONFIG.swirlFactor) * Math.PI * 2;
      p.vx += Math.cos(angle) * 0.05;
      p.vy += Math.sin(angle) * 0.05;

      // Movimiento base + ligera flotación
      p.x += p.vx * CONFIG.speed;
      p.y += p.vy * CONFIG.speed;

      // Rebote en bordes (nunca desaparecen)
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;

      // Dibujar
      const g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.size * 3);
      g.addColorStop(0, p.color);
      g.addColorStop(1, 'transparent');
      ctx.fillStyle = g;

      if (CONFIG.glow) {
        ctx.shadowColor = p.color;
        ctx.shadowBlur = 12;
      } else {
        ctx.shadowBlur = 0;
      }

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.size * 1.5, 0, Math.PI * 2);
      ctx.fill();

      p.age += CONFIG.alphaDecay;
    }

    rafId = requestAnimationFrame(step);
  }

  function start() {
    cancelAnimationFrame(rafId);
    resize();
    initParticles();
    rafId = requestAnimationFrame(step);
  }

  window.addEventListener('resize', resize);
  document.readyState === 'loading'
    ? document.addEventListener('DOMContentLoaded', start)
    : start();

  window.__sempriniParticles = { start, stop: () => cancelAnimationFrame(rafId) };
})();
