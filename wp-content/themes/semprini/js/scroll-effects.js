// Detecta elementos .fade-in y agrega .visible cuando entran en pantalla
/**
* Efectos de scroll:
* - Agrega .visible a los elementos .fade-in cuando entran en viewport
* - Suave y liviano, sin librerías externas
*/
jQuery(document).ready(function ($) {
function revealOnScroll() {
$('.fade-in').each(function () {
var $el = $(this);
var elementTop = $el.offset().top;
var windowBottom = $(window).scrollTop() + $(window).height();
if (windowBottom > elementTop + 50) {
$el.addClass('visible');
}
});
}


// Ejecutar al cargar y al scrollear
revealOnScroll();
$(window).on('scroll', revealOnScroll);


// Scroll suave para anclas internas (si las usás)
$(document).on('click', 'a[href^="#"]', function (e) {
var target = $(this.getAttribute('href'));
if (target.length) {
e.preventDefault();
$('html, body').animate({ scrollTop: target.offset().top - 64 }, 500);
}
});
});