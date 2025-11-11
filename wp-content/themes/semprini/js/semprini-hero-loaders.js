// Carga las animaciones SOLO cuando sus Web Components estén definidos y listos.
document.addEventListener("DOMContentLoaded", () => {
    
    // --- 1. ACTIVAR DOTLOTTIE ---
    const lottieContainer = document.querySelector(".dotlottie-bg-mobile");
    if (lottieContainer && !lottieContainer.querySelector("dotlottie-player")) {
        const src = lottieContainer.dataset.dotlottieSrc;
        
        const createLottiePlayer = () => {
          const player = document.createElement("dotlottie-player");
          player.setAttribute("src", src);
          player.setAttribute("autoplay", "");
          player.setAttribute("loop", "");
          player.setAttribute("background", "transparent");
          player.style.cssText = "position:absolute;width:100%;height:100%;top:0;left:0;";
          lottieContainer.appendChild(player);
        };
        
        // Espera a que el navegador defina el elemento
        customElements.whenDefined('dotlottie-player')
            .then(createLottiePlayer)
            .catch(err => console.error("Error al crear DotLottie.", err));
    }

    // --- 2. ACTIVAR SPLINE VIEWER ---
    const splineContainer = document.querySelector(".hero-3d-model-container");
    if (splineContainer && !splineContainer.querySelector("spline-viewer")) {
        const src = splineContainer.dataset.splineUrl;
        
        const createSplineViewer = () => {
            const viewer = document.createElement("spline-viewer");
            viewer.setAttribute("loading-anim", "true");
            viewer.setAttribute("url", src);
            viewer.style.cssText = "width:100%;height:100%;";
            splineContainer.appendChild(viewer);
        };

        // Espera a que el navegador defina el elemento
        customElements.whenDefined('spline-viewer')
            .then(createSplineViewer)
            .catch(err => console.error("Error al crear Spline Viewer.", err));
    }
});