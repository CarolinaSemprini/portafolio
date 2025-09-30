<?php
/*
Template Name: Contacto
*/
get_header();
?>

<section class="hero contact-hero">
  <div class="hero-overlay"></div>
  <div class="hero-container">

    <div class="contact-head fade-in">
      <h1 class="hero-title">Contacto</h1>
      <p class="hero-subtitle">Hablemos de tu próximo proyecto. Completa el formulario o usa los datos de contacto.</p>
    </div>

    <div class="contact-card">
      <div class="contact-split-container">

        <!-- FORMULARIO -->
        <div class="contact-form-column">
        
          <h2 class="form-title-h2">Contáctame</h2>
            
            <div id="form-message-container" class="form-alert-container"></div>
            
            <form class="semprini-form fade-in" id="contact-form" method="post" action="">
                <input type="text" name="nombre" placeholder="Tu nombre" required>
                <input type="email" name="email" placeholder="Tu correo electrónico" required>
                <input type="text" name="asunto" placeholder="Asunto" required>
                <textarea name="mensaje" placeholder="Escribe tu mensaje" rows="5" required></textarea>

                <input type="text" name="form_pot" value="" style="display:none">

                <?php if (function_exists('wp_nonce_field')) {
                    wp_nonce_field('semprini_form_nonce', 'semprini_nonce');
                } ?>  
                
                <input type="hidden" name="action" value="semprini_send_email">

                <button type="submit" name="semprini_submit" class="btn-3d btn-glow btn-3d--primary">
                    Enviar Mensaje
                    <span class="btn-3d__halo"></span>
                    <span class="btn-3d__gloss"></span>
                    <span class="glow-layer"></span>
                </button>
            </form>
        </div>

        <!-- INFORMACION -->
        <div class="contact-info-column fade-in">
          <h2 class="info-title-h2">Información</h2>

          <div class="info-item">
            <i class="fa-solid fa-location-dot icon"></i>
            <strong>Ubicación:</strong><br>
            Ciudad, País
          </div>

          <div class="info-item">
            <i class="fa-solid fa-envelope icon"></i>
            <strong>Email:</strong><br>
            <a href="mailto:tuemail@example.com">tuemail@example.com</a>
          </div>

          <div class="info-item">
            <i class="fa-solid fa-mobile-screen icon"></i>
            <strong>Teléfono:</strong><br>
            +123 456 789
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- BONUS: ANIMACIÓN AL CARGAR FORMULARIO -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contact-form");
    if (form) form.classList.add("visible");
  });
</script>

<?php get_footer(); ?>