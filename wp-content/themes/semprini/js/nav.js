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
