/* particles.js — Lightweight wind / vortex particles for high perf
   - Uses a single canvas
   - Colors as requested
   - Configurable count & speed
*/

(function () {
  'use strict';

  // configuración
  const CONFIG = {
    colors: [
      'rgba(0,255,255,0.45)',
      'rgba(173,216,230,0.35)',
      'rgba(0,180,255,0.3)',
      'rgba(255,255,255,0.25)'
    ],
    countBase: 60,      // base # of particles (desktop)
    countMobile: 28,    // mobile count
    speed: 0.9,         // global speed multiplier (aumenta para más rapidez)
    sizeMin: 1.2,
    sizeMax: 3.6,
    windStrength: 0.35, // fuerza de desplazamiento horizontal (viento)
    vortexChance: 0.05  // probabilidad de crear vórtice en partículas
  };

  // detectar móvil
  const isMobile = /Mobi|Android/i.test(navigator.userAgent);

  // canvas global
  const canvas = document.getElementById('global-particles');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  let particles = [];
  let w = 0, h = 0, dpr = Math.max(1, window.devicePixelRatio || 1);
  let rafId = null;

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
    const angle = rand(-0.5, 0.5); // slight initial angle
    const life = rand(8, 20);
    const color = CONFIG.colors[Math.floor(Math.random() * CONFIG.colors.length)];
    const vortex = Math.random() < CONFIG.vortexChance;
    return {
      x, y, vx: 0, vy: rand(0.1, 0.8) * CONFIG.speed,
      angle, size, life, age: 0, color, vortex,
      swirl: vortex ? (rand(0.02, 0.08) * (Math.random() < 0.5 ? -1 : 1)) : 0
    };
  }

  function initParticles() {
    particles = [];
    const count = isMobile ? CONFIG.countMobile : CONFIG.countBase;
    for (let i = 0; i < count; i++) {
      particles.push(createParticle());
    }
  }

  function step() {
    ctx.clearRect(0, 0, w, h);

    // aire / viento base (varía con sin para dar remolinos)
    const t = Date.now() * 0.0005;
    const windBase = Math.sin(t * 0.9) * CONFIG.windStrength * CONFIG.speed;

    for (let i = 0; i < particles.length; i++) {
      const p = particles[i];

      // efecto viento + remolino local
      p.vx += windBase * rand(0.6, 1.4) + (Math.sin(p.y * 0.01 + t * 2) * 0.02);
      // si vortex activo, añadir componente circular
      if (p.vortex) {
        p.vx += Math.cos((p.x + p.y) * 0.01 + t * 3) * p.swirl * 2;
        p.vy += Math.sin((p.x + p.y) * 0.01 + t * 2) * p.swirl * 1.4;
      }

      // mover
      p.x += p.vx * 1.05;
      p.y += p.vy * (1 + CONFIG.speed * 0.25);

      // envejecimiento
      p.age += 0.02 * (1 + CONFIG.speed * 0.2);
      const alpha = Math.max(0, 1 - (p.age / p.life));

      // dibujar
      ctx.beginPath();
      ctx.fillStyle = p.color.replace(/[\d\.]+\)$/,' ' + alpha + ')').replace('rgb','rgba').replace('rgba(','rgba(');
      // draw as ellipse-ish (stretched by vx)
      const stretch = Math.min(1.8, 1 + Math.abs(p.vx) * 0.6);
      ctx.ellipse(p.x, p.y, p.size * stretch, p.size, p.angle, 0, Math.PI * 2);
      ctx.fill();

      // respawn si sale
      if (p.y > h + 30 || p.x < -60 || p.x > w + 60 || alpha <= 0) {
        particles[i] = createParticle();
        particles[i].y = -10 - Math.random() * 60; // reaparecen arriba
      }
      // pequeña fricción para no volar demasiado
      p.vx *= 0.995;
      p.vy *= 0.998;
    }

    rafId = requestAnimationFrame(step);
  }

  // inicialización
  function start() {
    cancelAnimationFrame(rafId);
    resize();
    initParticles();
    rafId = requestAnimationFrame(step);
  }

  // responsive + visibilidad
  window.addEventListener('resize', () => {
    resize();
  });

  // Start after DOM ready, but allow theme scripts to finish first
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }

  // Exponer control por si quieres aumentar velocidad desde consola:
  window.__sempriniParticles = {
    start,
    stop: () => { cancelAnimationFrame(rafId); rafId = null; },
    setSpeed: (v) => { CONFIG.speed = v; },
    setCount: (c) => {
      if (c > 0) {
        CONFIG.countBase = c;
        initParticles();
      }
    }
  };

})();
