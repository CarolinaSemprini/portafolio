(function () {
  function toEmbed(url) {
    if (!url) return "";
    url = String(url).trim();

    // YouTube
    const y1 = /youtube\.com\/watch\?v=([^&]+)/i.exec(url);
    const y2 = /youtu\.be\/([^?&]+)/i.exec(url);
    if (y1) return `https://www.youtube.com/embed/${y1[1]}?rel=0&modestbranding=1&playsinline=1`;
    if (y2) return `https://www.youtube.com/embed/${y2[1]}?rel=0&modestbranding=1&playsinline=1`;

    // Vimeo
    const v1 = /vimeo\.com\/(\d+)/i.exec(url);
    if (v1) return `https://player.vimeo.com/video/${v1[1]}`;

    return url; // deja otros dominios como están
  }

  function openModal(html) {
    const ov = document.createElement("div");
    ov.className = "video-overlay";
    ov.innerHTML = `
      <div class="video-modal" role="dialog" aria-modal="true">
        <button class="vm-close" aria-label="Cerrar">×</button>
        <div class="vm-body">${html}</div>
      </div>`;
    document.body.appendChild(ov);

    const close = () => { ov.classList.add("closing"); setTimeout(()=>ov.remove(),200); };
    ov.querySelector(".vm-close").addEventListener("click", close);
    ov.addEventListener("click", e => { if (e.target === ov) close(); });
  }

  const htmlIframe = (src) => `
    <div class="vm-embed">
      <iframe src="${toEmbed(src)}" title="video" loading="lazy"
              allow="autoplay; encrypted-media; picture-in-picture; fullscreen"
              allowfullscreen></iframe>
    </div>`;

  const htmlVideo = (src, poster) => {
    const p = poster ? ` poster="${poster}"` : "";
    return `<video class="vm-video" controls playsinline${p}>
              <source src="${src}" type="video/mp4">
            </video>`;
  };

  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".js-open-video");
    if (!btn) return;

    const url = btn.getAttribute("data-video") || "";
    const poster = btn.getAttribute("data-poster") || "";

    if (!url) return;
    e.preventDefault();

    if (/\.(mp4|webm|ogg)(\?|$)/i.test(url)) {
      openModal(htmlVideo(url, poster));
    } else {
      openModal(htmlIframe(url));
    }
  });
})();
