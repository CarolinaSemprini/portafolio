/**
 * modal-video.js — Vanilla JS
 * - Usa dotlottie-player (Lottie) para animación de apertura/cierre
 * - Carga video desde atributo data-video en el trigger (ACF output)
 * - Gestión de focus y bloqueo de scroll
 *
 * Uso HTML (ejemplo en plantilla):
 * <button class="js-open-video" data-video-url="<?php echo esc_url($video_url); ?>" data-poster="<?php echo esc_url($poster); ?>">Ver video</button>
 *
 * Donde $video_url proviene de ACF (campo hero_video_fondo o similar).
 */

(function () {
  'use strict';

  // Guardar el foco para restaurarlo al cerrar
  let lastFocused = null;

  function createModal(videoUrl, posterUrl, options = {}) {
    const overlay = document.createElement('div');
    overlay.className = 'video-overlay';
    overlay.style.cssText = 'position:fixed;inset:0;display:grid;place-items:center;background:rgba(0,0,0,0.8);z-index:99999;';

    // Contenedor central
    const modal = document.createElement('div');
    modal.className = 'video-modal';
    modal.style.cssText = 'width:min(920px,92vw);background:rgba(10,12,14,0.96);border:1px solid rgba(255,255,255,0.06);border-radius:12px;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,0.6);position:relative;';

    // Close button
    const btnClose = document.createElement('button');
    btnClose.className = 'vm-close';
    btnClose.setAttribute('aria-label', 'Cerrar video');
    btnClose.innerHTML = '&times;';
    btnClose.style.cssText = 'position:absolute;right:10px;top:8px;width:40px;height:40px;border-radius:999px;border:0;background:rgba(255,255,255,0.06);color:#fff;font-size:20px;cursor:pointer;z-index:2;';

    // Lottie opening animation (optional) - use a small default animation or data-attribute
    const lottieSrcOpen = options.lottieOpen || 'https://assets8.lottiefiles.com/packages/lf20_touohxv0.json'; // fallback
    const lottieSrcClose = options.lottieClose || null; // optional

    // Container for lottie player (will be removed after animation completes)
    const lottieWrap = document.createElement('div');
    lottieWrap.style.cssText = 'position:absolute;inset:0;pointer-events:none;display:grid;place-items:center;z-index:1;';

    // create dotlottie-player only if available
    if (window.customElements && window.customElements.get && window.customElements.get('dotlottie-player')) {
      const player = document.createElement('dotlottie-player');
      player.setAttribute('src', lottieSrcOpen);
      player.setAttribute('background', 'transparent');
      player.setAttribute('speed', '1');
      player.setAttribute('autoplay', 'true');
      player.setAttribute('style', 'width:20%;max-width:120px;pointer-events:none;');
      lottieWrap.appendChild(player);
    }

    // Video element (not autoplay muted to avoid blocking; we let user play)
    const videoWrap = document.createElement('div');
    videoWrap.className = 'vm-body';
    videoWrap.style.cssText = 'padding:10px;';
    const video = document.createElement('video');
    video.className = 'vm-video';
    video.setAttribute('controls', '');
    video.setAttribute('playsinline', '');
    if (posterUrl) video.setAttribute('poster', posterUrl);
    video.style.cssText = 'width:100%;height:auto;max-height:78vh;background:#000;display:block;';

    const source = document.createElement('source');
    source.src = videoUrl;
    source.type = 'video/mp4';

    video.appendChild(source);
    videoWrap.appendChild(video);

    // Add close button, lottie and video into modal
    modal.appendChild(btnClose);
    modal.appendChild(lottieWrap);
    modal.appendChild(videoWrap);
    overlay.appendChild(modal);

    // Append to body
    document.body.appendChild(overlay);

    // Prevent background scroll
    const prevOverflow = document.documentElement.style.overflow;
    document.documentElement.style.overflow = 'hidden';

    // Focus management
    btnClose.focus();

    // Remove Lottie after 1.2s (opening)
    setTimeout(() => {
      if (lottieWrap && lottieWrap.parentNode) {
        lottieWrap.parentNode.removeChild(lottieWrap);
      }
      // Try to autoplay muted if allowed (unmute user)
      try {
        video.muted = true;
        const p = video.play();
        if (p && typeof p.then === 'function') {
          p.then(() => {
            // auto-play succeeded (muted)
            video.muted = false; // optional: unmute (may be blocked)
          }).catch(() => {
            // autoplay blocked — leave controls for user
            video.pause();
            video.muted = false;
          });
        }
      } catch (err) { /* ignore */ }
    }, 1100);

    // Close handler
    function closeModal() {
      // play closing Lottie if provided
      if (lottieSrcClose && window.customElements && window.customElements.get('dotlottie-player')) {
        const closeContainer = document.createElement('div');
        closeContainer.style.cssText = 'position:absolute;inset:0;display:grid;place-items:center;z-index:2;pointer-events:none;';
        const closePlayer = document.createElement('dotlottie-player');
        closePlayer.setAttribute('src', lottieSrcClose);
        closePlayer.setAttribute('autoplay', 'true');
        closePlayer.setAttribute('background', 'transparent');
        closeContainer.appendChild(closePlayer);
        modal.appendChild(closeContainer);
        // remove after animation (2s)
        setTimeout(() => {
          if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
          document.documentElement.style.overflow = prevOverflow || '';
          if (lastFocused) lastFocused.focus();
        }, 900);
      } else {
        // Normal remove
        if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
        document.documentElement.style.overflow = prevOverflow || '';
        if (lastFocused) lastFocused.focus();
      }
    }

    btnClose.addEventListener('click', (e) => {
      e.stopPropagation();
      closeModal();
    });

    // close on overlay click outside modal
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal();
    });

    // close on ESC
    document.addEventListener('keydown', function escHandler(e) {
      if (e.key === 'Escape') {
        document.removeEventListener('keydown', escHandler);
        closeModal();
      }
    });

    return { overlay, modal, video, closeModal };
  }

  // Init: attach listeners to triggers with class .js-open-video
  document.addEventListener('DOMContentLoaded', () => {
    const triggers = document.querySelectorAll('.js-open-video');
    if (!triggers.length) return;

    triggers.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        lastFocused = document.activeElement;

        const videoUrl =
        btn.dataset.videoUrl ||
        btn.getAttribute('data-video-url') ||
        btn.dataset.video ||
        btn.getAttribute('data-video') ||
        btn.getAttribute('href');
        const poster = btn.dataset.poster || '';

        if (!videoUrl) {
          console.warn('modal-video: no data-video-url found on trigger', btn);
          return;
        }

        createModal(videoUrl, poster, {
          lottieOpen: btn.dataset.lottieOpen || null,
          lottieClose: btn.dataset.lottieClose || null
        });
      });
    });
  });

})();
