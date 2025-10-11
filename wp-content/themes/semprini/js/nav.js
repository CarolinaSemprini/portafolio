/**
 * NAV:
 * - Toggle del menú móvil
 * - Scroll suave a anclas
 * - Scrollspy: marca .is-active según sección visible
 */
jQuery(function($){
  const $toggle = $('.nav-toggle');
  const $nav = $('.main-nav');
  const $links = $('.main-nav .menu a[href^="#"]');

  // Toggle móvil
  $toggle.on('click', function(){
    const open = $nav.toggleClass('open').hasClass('open');
    $(this).attr('aria-expanded', open ? 'true' : 'false');
    $('body').toggleClass('menu-open', open); // Evita scroll del body cuando el menú está abierto
  });

  // Cerrar menú al hacer clic en un enlace (móvil)
  $links.on('click', function(){
    if (window.innerWidth <= 900) {
      $nav.removeClass('open');
      $toggle.attr('aria-expanded', 'false');
    }
  });

  // Scroll suave con offset del header
  $links.on('click', function(e){
    const target = $(this.getAttribute('href'));
    if (target.length){
      e.preventDefault();
      const headerH = $('.site-header').outerHeight() || 0;
      $('html,body').animate({ scrollTop: target.offset().top - headerH - 8 }, 500);
    }
  });

  // Scrollspy
  const sections = ['home','services','stats','work','contact']
    .map(id => ({ id, $el: $('#'+id) }))
    .filter(s => s.$el.length);

  function onScroll(){
    const scroll = $(window).scrollTop();
    const headerH = $('.site-header').outerHeight() || 0;
    let current = 'home';
    for (const s of sections){
      if (scroll + headerH + 120 >= s.$el.offset().top) current = s.id;
    }
    // Marca activo
    $('.main-nav .menu a').removeClass('is-active');
    $('.main-nav .menu a[href="#'+current+'"]').addClass('is-active');
  }
  onScroll();
  $(window).on('scroll', onScroll);
});

// ==============================================
// 3. IMPLEMENTACIÓN DE SCROLLREVEAL (Animaciones al scroll)
// ==============================================

// Esperamos a que el DOM esté completamente cargado y las librerías enlazadas
document.addEventListener('DOMContentLoaded', function() {
    
    // Verificamos si ScrollReveal existe (debería existir gracias a functions.php)
    if (typeof ScrollReveal !== 'undefined') {
        
        // Configuramos la animación base para todo el sitio
        const sr = ScrollReveal({
            reset: false, 
            duration: 800, 
            delay: 100, 
            distance: '20px', 
            easing: 'cubic-bezier(0.5, 0, 0, 1)' 
        });

        // Aplicamos la animación de aparición a todos tus elementos '.fade-in'
        // Esto funciona perfectamente en el móvil.
        sr.reveal('.fade-in', {
            origin: 'bottom', // Hace que el elemento se mueva desde abajo
            interval: 50      // Retraso entre la aparición de elementos hermanos (como tarjetas)
        });
    }
});