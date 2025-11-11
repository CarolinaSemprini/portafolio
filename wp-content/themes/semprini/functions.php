<?php
/**
 * FUNCTIONS.PHP — Tema "Semprini" (versión optimizada para particles + perf)
 *
 * - Setup del tema
 * - Enqueue optimizado de CSS/JS
 * - Helpers (ACF, video fondo)
 * - Partículas en canvas para todas las páginas (movil+desktop)
 * - Carga diferida del video hero (data-src -> src)
 *
 * IMPORTANTE: Este archivo reemplaza al anterior completo. Haz backup antes.
 */

if (!defined('ABSPATH')) exit;

/* -------------------------------------------------
 * 1) SETUP DEL TEMA
 * ------------------------------------------------- */
function semprini_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form', 'gallery', 'caption']);

  register_nav_menus([
    'main_menu' => 'Menú Principal',
  ]);
}
add_action('after_setup_theme', 'semprini_setup');


/* -------------------------------------------------
 * 2) ENQUEUE DE ESTILOS Y SCRIPTS OPTIMIZADO
 * ------------------------------------------------- */
function semprini_assets() {
  $theme_dir  = get_template_directory_uri();
  $theme_path = get_template_directory();

  // CSS base
  wp_enqueue_style('semprini-style', get_stylesheet_uri(), [], filemtime($theme_path . '/style.css'));

  // Particles CSS (global, ligero)
  wp_enqueue_style('semprini-particles', $theme_dir . '/css/particles.css', [], filemtime($theme_path . '/css/particles.css'));

  // Secciones / páginas específicas (mantener orden original)
  wp_enqueue_style('semprini-nav',    $theme_dir . '/css/nav.css',  ['semprini-style'], filemtime($theme_path . '/css/nav.css'));
  wp_enqueue_style('semprini-hero',   $theme_dir . '/css/hero.css', ['semprini-style'], filemtime($theme_path . '/css/hero.css'));
  wp_enqueue_style('semprini-work',   $theme_dir . '/css/work.css', ['semprini-style'], filemtime($theme_path . '/css/work.css'));
  wp_enqueue_style('semprini-about',  $theme_dir . '/css/about.css',['semprini-style'], filemtime($theme_path . '/css/about.css'));
  wp_enqueue_style('semprini-anim',   $theme_dir . '/css/animations.css',['semprini-style'], filemtime($theme_path . '/css/animations.css'));
  wp_enqueue_style('semprini-services', $theme_dir . '/css/services.css', ['semprini-style'], filemtime($theme_path . '/css/services.css'));
  wp_enqueue_style('semprini-blog',     $theme_dir . '/css/blog.css', ['semprini-style', 'semprini-services'], filemtime($theme_path . '/css/blog.css'));

  // Scripts (footer)
  wp_enqueue_script('semprini-nav', $theme_dir . '/js/nav.js', [], filemtime($theme_path . '/js/nav.js'), true);
  wp_enqueue_script('semprini-scroll-effects', $theme_dir . '/js/scroll-effects.js', [], filemtime($theme_path . '/js/scroll-effects.js'), true);
  wp_enqueue_script('semprini-scroll-to', $theme_dir . '/js/scroll-to.js', [], filemtime($theme_path . '/js/scroll-to.js'), true);

  // Modal video
  if (file_exists($theme_path . '/js/modal-video.js')) {
    wp_enqueue_script('semprini-modal-video', $theme_dir . '/js/modal-video.js', ['jquery'], filemtime($theme_path . '/js/modal-video.js'), true);
  }

  // Lightbox (externo)
  wp_enqueue_style('lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css', [], '2.11.4');
  wp_enqueue_script('lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', ['jquery'], '2.11.4', true);

  // ScrollReveal (solo si lo necesitas más adelante)
  wp_enqueue_script('scrollreveal', 'https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js', [], '4.0.9', true);

  // --- Particles (JS ligero, global para todas las páginas) ---
  // particles.js: canvas + wind effect (viene en /js/particles.js)
  wp_enqueue_script('semprini-particles', $theme_dir . '/js/particles.js', [], filemtime($theme_path . '/js/particles.js'), true);

  // Hero init: carga diferida del video héroe (carga el src desde data-src cuando corresponde)
  wp_enqueue_script('semprini-hero-init', $theme_dir . '/js/hero-init.js', ['semprini-particles'], filemtime($theme_path . '/js/hero-init.js'), true);

  // Font Awesome
  wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', [], '6.5.2');

}
add_action('wp_enqueue_scripts', 'semprini_assets');


/* -------------------------------------------------
 * 3) DESACTIVAR GUTENBERG
 * ------------------------------------------------- */
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);


/* -------------------------------------------------
 * 4) HABILITAR SUBIDA DE SVG (controlado)
 * ------------------------------------------------- */
function semprini_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'semprini_mime_types');

function semprini_svg_filetype_check($data, $file, $filename, $mimes) {
  $ext = pathinfo($filename, PATHINFO_EXTENSION);
  if (strtolower($ext) === 'svg') {
    $data['ext']  = 'svg';
    $data['type'] = 'image/svg+xml';
  }
  return $data;
}
add_filter('wp_check_filetype_and_ext', 'semprini_svg_filetype_check', 10, 4);

add_action('admin_head', function() {
  echo '<style>.attachment .thumbnail img[src$=".svg"]{width:100%!important;height:auto!important;}</style>';
});


/* -------------------------------------------------
 * 5) INCLUDES (ACF / CPT)
 * ------------------------------------------------- */
$inc_files = [
  '/inc/custom-post-types.php',
  '/inc/acf-home-fields.php',
  '/inc/acf-about-fields.php',
  '/inc/acf-certs-fields.php',
  '/inc/acf-project-fields.php'
];
foreach ($inc_files as $inc) {
  $path = get_template_directory() . $inc;
  if (file_exists($path)) require_once $path;
}


/* -------------------------------------------------
 * 6) HELPERS: VIDEO DE FONDO GLOBAL (mantener ACF)
 * ------------------------------------------------- */
function semprini_get_bg_video_url() {
  if (function_exists('get_field')) {
    $v = get_field('hero_video_fondo');
    if (is_array($v) && !empty($v['url'])) return $v['url'];
    if (is_numeric($v)) {
      $u = wp_get_attachment_url((int) $v);
      if ($u) return $u;
    }
    if (is_string($v) && $v !== '') return $v;

    $home_id = (int) get_option('page_on_front');
    if ($home_id) {
      $vh = get_field('hero_video_fondo', $home_id);
      if (is_array($vh) && !empty($vh['url'])) return $vh['url'];
      if (is_numeric($vh)) {
        $uh = wp_get_attachment_url((int) $vh);
        if ($uh) return $uh;
      }
      if (is_string($vh) && $vh !== '') return $vh;
    }
  }
  return get_template_directory_uri() . '/img/video.mp4';
}

function semprini_get_bg_poster_url() {
  return get_template_directory_uri() . '/img/hero-fallback.jpg';
}


/* --- Canvas global de partículas y control de video --- */
add_action('wp_footer', function() {
  ?>
  <!-- Canvas de partículas (solo uno en todo el sitio) -->
  <canvas id="global-particles" aria-hidden="true"></canvas>

  <script>
    // Detectar si es móvil (puede ajustarse a tu gusto)
    const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    const heroVideo = document.querySelector('.hero-video');
    const particlesCanvas = document.getElementById('global-particles');

    if (isMobile) {
      // En móvil: ocultar video y mostrar partículas
      if (heroVideo) heroVideo.style.display = 'none';
      if (particlesCanvas) particlesCanvas.style.display = 'block';
    } else {
      // En escritorio/tablet: mostrar video y ocultar partículas (opcional)
      if (heroVideo) heroVideo.style.display = 'block';
      if (particlesCanvas) particlesCanvas.style.display = 'none';
    }
  </script>

  <?php
});


/* -------------------------------------------------
 * 7) ANIMACIONES (opcional)
 * ------------------------------------------------- */
function semprini_animation_assets() {
  // DotLottie y Spline se han retirado deliberateamente (sacrifice) por perf/stability
  // Dejar el loader para Lottie si en el futuro se reintroduce.
}
add_action('wp_enqueue_scripts', 'semprini_animation_assets');


/* -------------------------------------------------
 * 8) PERFORMANCE: lazy images / iframes
 * ------------------------------------------------- */
add_filter('wp_get_attachment_image_attributes', function($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
});
add_filter('the_content', function($content) {
    return str_replace('<iframe', '<iframe loading="lazy"', $content);
});


/* -------------------------------------------------
 * 9) Añadir atributo "type=module" si en el futuro usas módulos
 * (no obligatorio ahora; definido pero no usado)
 * ------------------------------------------------- */
function semprini_add_module_type_to_scripts($tag, $handle, $src) {
    if ( 'semprini-hero-init' === $handle ) {
        // si necesitáramos cargar algo como module, lo haríamos aquí:
        return $tag;
    }
    return $tag;
}
add_filter('script_loader_tag', 'semprini_add_module_type_to_scripts', 10, 3);


/* -------------------------------------------------
 * 10) CONTACT FORM AJAX (no tocar)
 * ------------------------------------------------- */

// =========================================================================
// LÓGICA DE CONTACTO AJAX
// 1. Cargar el Script de AJAX y pasar la URL de admin-ajax.php
// =========================================================================
function semprini_enqueue_contact_scripts() {
    // Solo cargamos el script en la página de Contacto si usas la plantilla "Contacto"
    if ( is_page_template( 'template-contact.php' ) ) {
        // Aseguramos que se cargue jQuery primero
        wp_enqueue_script( 
            'semprini-contact-form', 
            get_template_directory_uri() . '/js/contact-form.js', 
            array('jquery'), 
            '1.0', 
            true 
        );

        // Pasamos la URL de WordPress AJAX a JavaScript
        wp_localize_script( 'semprini-contact-form', 'semprini_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ) 
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'semprini_enqueue_contact_scripts' );


// =========================================================================
// 2. Controlador de Envío del Formulario (PHP que procesa AJAX)
// =========================================================================
function semprini_send_email_handler() {
    // 1. Verificación de Seguridad (Nonce)
    if ( ! isset( $_POST['semprini_nonce'] ) || ! wp_verify_nonce( $_POST['semprini_nonce'], 'semprini_form_nonce' ) ) {
        wp_send_json_error( 'Error de seguridad. Recarga la página.' );
        wp_die();
    }
    
    // 2. Validación Honeypot (Anti-bot)
    if (!empty($_POST['form_pot'])) {
        // Silencio para los bots
        wp_send_json_success( '¡Mensaje enviado con éxito!' ); 
        wp_die();
    }

    // 3. Validación y Saneamiento de Datos
    $nombre  = sanitize_text_field( $_POST['nombre'] );
    $email   = sanitize_email( $_POST['email'] );
    $asunto  = sanitize_text_field( $_POST['asunto'] );
    $mensaje = sanitize_textarea_field( $_POST['mensaje'] );

    if ( empty($nombre) || empty($email) || empty($asunto) || empty($mensaje) ) {
        wp_send_json_error( '🚨 Por favor completa todos los campos requeridos.' );
        wp_die();
    }
    
    if ( !is_email($email) ) {
        wp_send_json_error( '❌ Dirección de correo inválida. Por favor, revísala.' );
        wp_die();
    }

    // 4. Preparación del Email
    $para    = 'carolinasemprini@gmail.com'; 
    $subject = 'Mensaje de ' . $nombre . ' - ' . $asunto;
    $contenido = "<strong>Nombre:</strong> {$nombre}<br>
                  <strong>Email:</strong> {$email}<br>
                  <strong>Asunto:</strong> {$asunto}<br>
                  <strong>Mensaje:</strong><br>
                  <p>{$mensaje}</p>";

    $cabeceras = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $email,
        'From: ' . $nombre . ' <' . $email . '>', // Opcional: Para ver quién lo envía
    ];

    // 5. Envío del Email
    if ( wp_mail( $para, $subject, $contenido, $cabeceras ) ) {
        // Mensaje de Éxito Personalizado
        $custom_success = '✅ ¡Mensaje enviado con éxito! Gracias por comunicarte con Carolina Semprini, te responderé a la brevedad. 😊';
        wp_send_json_success( $custom_success );
    } else {
        // Mensaje de Error
        wp_send_json_error( '❌ Ha ocurrido un error. No se ha podido enviar el mensaje. Intenta nuevamente.' );
    }

    wp_die(); 
}
// Registramos el handler para usuarios logueados y no logueados
add_action( 'wp_ajax_semprini_send_email', 'semprini_send_email_handler' );
add_action( 'wp_ajax_nopriv_semprini_send_email', 'semprini_send_email_handler' );