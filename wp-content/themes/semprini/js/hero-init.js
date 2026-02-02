/* hero-init.js
   - Coloca el src del video héroe desde data-src cuando estemos en desktop
   - Evita descargar video en móviles (ahorrar datos)
   - Intenta play (autoplay muted)
*/

(function () {
  'use strict';

  function isMobileUA() {
    return /Mobi|Android/i.test(navigator.userAgent);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('global-hero-video');
    if (!video) return;

    // Solo cargar el video en desktop (no en móviles) para ahorrar datos y CPU:
    if (!isMobileUA()) {
      const src = video.getAttribute('data-src');
      if (src && !video.getAttribute('src')) {
        // create source element for fallback and set src attribute
        const source = document.createElement('source');
        source.src = src;
        source.type = 'video/mp4';
        video.appendChild(source);
        video.load();
        // Try to play muted (autoplay)
        const p = video.play();
        if (p && typeof p.then === 'function') {
          p.catch(() => {
            // autoplay blocked: leave muted and let user interact
            video.muted = true;
          });
        }
      }
    } else {
      // Mobile: do not load heavy video; keep poster and rely on canvas particles
      // Optionally you could load a 300kb mp4 poster-video if you want mobile video
    }
  });
})();
