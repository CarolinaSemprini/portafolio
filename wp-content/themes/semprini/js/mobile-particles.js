/**
 * mobile-particles.js
 * Efecto de viento con remolinos — versión más rápida
 */

(function() {
  if (window.innerWidth > 768) return; // Solo en móviles

  const container = document.getElementById('mobile-particles');
  if (!container) return;

  const numParticles = 80; // Densidad equilibrada
  const colors = [
    'rgba(0,255,255,0.45)',
    'rgba(173,216,230,0.35)',
    'rgba(0,180,255,0.3)',
    'rgba(255,255,255,0.25)'
  ];

  for (let i = 0; i < numParticles; i++) {
    const p = document.createElement('div');
    p.classList.add('particle');

    // Posición inicial aleatoria
    p.style.left = Math.random() * 100 + 'vw';
    p.style.top = Math.random() * 100 + 'vh';

    // Tamaño aleatorio
    const size = 2 + Math.random() * 6;
    p.style.width = `${size}px`;
    p.style.height = `${size}px`;

    // Color aleatorio (mezcla sutil)
    p.style.background = `radial-gradient(circle, ${colors[Math.floor(Math.random() * colors.length)]} 0%, transparent 70%)`;

    // ⏱️ Duración reducida → viento más rápido
    const duration = 5 + Math.random() * 10; // antes 8–18s → ahora 5–15s
    const delay = Math.random() * 10;
    p.style.animationDuration = `${duration}s`;
    p.style.animationDelay = `${delay}s`;

    // Variación vertical (remolinos)
    const offset = Math.random() * 20 - 10; // +10 / -10 px
    p.style.setProperty('--offset', `${offset}px`);

    container.appendChild(p);
  }
})();

document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("global-particles");
    const count = 78; // Cantidad de partículas

    for (let i = 0; i < count; i++) {
        const p = document.createElement("span");
        p.className = "particle";
        p.style.left = Math.random() * 100 + "vw";
        p.style.top = Math.random() * 100 + "vh";
        p.style.animationDuration = (8 + Math.random() * 8) + "s";
        p.style.animationDelay = Math.random() * -10 + "s";
        container.appendChild(p);
    }
});
