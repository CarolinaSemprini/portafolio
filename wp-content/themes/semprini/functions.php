<?php
/**
 * FUNCTIONS.PHP — Tema "Semprini" (optimizado + robusto)
 *
 * Cambios mínimos:
 * - Helpers para enqueue con file_exists + filemtime (evita warnings/roturas)
 * - Carga condicional de librerías externas (Lightbox / ScrollReveal)
 * - modal-video.js sin dependencia de jQuery (es vanilla)
 * - From/Reply-To robusto para SMTP (mejor entregabilidad)
 */

if (!defined('ABSPATH')) exit;

/* -------------------------------------------------
 * 0) HELPERS: enqueue seguro con file_exists + filemtime
 * ------------------------------------------------- */
function semprini_enqueue_style_if_exists($handle, $relative_path, $deps = []) {
  $theme_dir  = get_template_directory_uri();
  $theme_path = get_template_directory();

  $abs = $theme_path . $relative_path;
  if (file_exists($abs)) {
    wp_enqueue_style($handle, $theme_dir . $relative_path, $deps, filemtime($abs));
  }
}

function semprini_enqueue_script_if_exists($handle, $relative_path, $deps = [], $in_footer = true) {
  $theme_dir  = get_template_directory_uri();
  $theme_path = get_template_directory();

  $abs = $theme_path . $relative_path;
  if (file_exists($abs)) {
    wp_enqueue_script($handle, $theme_dir . $relative_path, $deps, filemtime($abs), $in_footer);
  }
}

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
 * 2) ENQUEUE DE ESTILOS Y SCRIPTS (robusto + optimizado)
 * ------------------------------------------------- */
function semprini_assets() {
  // Airbag: wp_enqueue_scripts no corre en admin normalmente, pero no molesta.
  if (is_admin()) return;

  // CSS base
  // style.css siempre existe en un tema, pero lo dejamos robusto igual:
  $theme_path = get_template_directory();
  $style_abs  = $theme_path . '/style.css';
  wp_enqueue_style('semprini-style', get_stylesheet_uri(), [], file_exists($style_abs) ? filemtime($style_abs) : null);

  // Particles CSS (global, ligero)
  semprini_enqueue_style_if_exists('semprini-particles', '/css/particles.css', []);

  // Secciones / páginas específicas (mismo orden que tenías)
  semprini_enqueue_style_if_exists('semprini-nav',      '/css/nav.css',        ['semprini-style']);
  semprini_enqueue_style_if_exists('semprini-hero',     '/css/hero.css',       ['semprini-style']);
  semprini_enqueue_style_if_exists('semprini-work',     '/css/work.css',       ['semprini-style']);
  semprini_enqueue_style_if_exists('semprini-about',    '/css/about.css',      ['semprini-style']);
  semprini_enqueue_style_if_exists('semprini-anim',     '/css/animations.css', ['semprini-style']);
  semprini_enqueue_style_if_exists('semprini-services', '/css/services.css',   ['semprini-style']);
  semprini_enqueue_style_if_exists('semprini-blog',     '/css/blog.css',       ['semprini-style', 'semprini-services']);

  // Scripts propios (footer)
  semprini_enqueue_script_if_exists('semprini-nav',            '/js/nav.js',           [], true);
  semprini_enqueue_script_if_exists('semprini-scroll-effects', '/js/scroll-effects.js',[], true);
  semprini_enqueue_script_if_exists('semprini-scroll-to',      '/js/scroll-to.js',     [], true);

  // Modal video (tu script es vanilla → sin jQuery)
  semprini_enqueue_script_if_exists('semprini-modal-video', '/js/modal-video.js', [], true);

  // --- CARGA CONDICIONAL DE EXTERNOS (optimización real) ---

  // Lightbox: solo en Certificados (ajustá si lo usás en otra página)
  if (is_page_template('template-certs.php')) {
    wp_enqueue_style('lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css', [], '2.11.4');
    wp_enqueue_script('lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', ['jquery'], '2.11.4', true);
  }

  // ScrollReveal: solo en home (si de verdad lo usás)
  if (is_front_page()) {
    wp_enqueue_script('scrollreveal', 'https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js', [], '4.0.9', true);
  }

  // Particles JS (global)
  semprini_enqueue_script_if_exists('semprini-particles', '/js/particles.js', [], true);

  // Hero init: depende de particles (si hero-init.js existe)
  semprini_enqueue_script_if_exists('semprini-hero-init', '/js/hero-init.js', ['semprini-particles'], true);

  // Font Awesome (externo)
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
  if (is_admin()) return;
  ?>
  <canvas id="global-particles" aria-hidden="true"></canvas>

  <script>
    const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    const heroVideo = document.querySelector('.hero-video');
    const particlesCanvas = document.getElementById('global-particles');

    if (isMobile) {
      if (heroVideo) heroVideo.style.display = 'none';
      if (particlesCanvas) particlesCanvas.style.display = 'block';
    } else {
      if (heroVideo) heroVideo.style.display = 'block';
      if (particlesCanvas) particlesCanvas.style.display = 'none';
    }
  </script>
  <?php
});

// ACF Local JSON: guardar field groups dentro del theme
add_filter('acf/settings/save_json', function($path) {
  return get_stylesheet_directory() . '/acf-json';
});

// ACF Local JSON: cargar desde la carpeta del theme
add_filter('acf/settings/load_json', function($paths) {
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
});

/* -------------------------------------------------
 * 7) ANIMACIONES (opcional)
 * ------------------------------------------------- */
function semprini_animation_assets() {
  // reservado
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
 * 9) script_loader_tag (reservado)
 * ------------------------------------------------- */
function semprini_add_module_type_to_scripts($tag, $handle, $src) {
  return $tag;
}
add_filter('script_loader_tag', 'semprini_add_module_type_to_scripts', 10, 3);


/* -------------------------------------------------
 * 10) CONTACT FORM AJAX (robusto para producción)
 * ------------------------------------------------- */

// Enqueue solo en template-contact.php
function semprini_enqueue_contact_scripts() {
  if ( is_page_template( 'template-contact.php' ) ) {
    wp_enqueue_script(
      'semprini-contact-form',
      get_template_directory_uri() . '/js/contact-form.js',
      ['jquery'],
      '1.0',
      true
    );

    wp_localize_script('semprini-contact-form', 'semprini_ajax', [
      'ajax_url' => admin_url('admin-ajax.php')
    ]);
  }
}
add_action('wp_enqueue_scripts', 'semprini_enqueue_contact_scripts');


/**
 * Devuelve un email From seguro basado en el dominio actual.
 * En local quedará tipo: no-reply@semprini.local
 * En producción: no-reply@tudominio.com
 */
function semprini_get_default_from_email() {
  $host = wp_parse_url(home_url(), PHP_URL_HOST);
  if (!$host) return 'no-reply@localhost';
  $host = preg_replace('/^www\./', '', $host);
  return 'no-reply@' . $host;
}

function semprini_send_email_handler() {
  // 1) Seguridad
  if ( ! isset( $_POST['semprini_nonce'] ) || ! wp_verify_nonce( $_POST['semprini_nonce'], 'semprini_form_nonce' ) ) {
    wp_send_json_error( 'Error de seguridad. Recarga la página.' );
    wp_die();
  }

  // 2) Honeypot
  if (!empty($_POST['form_pot'])) {
    wp_send_json_success( '¡Mensaje enviado con éxito!' );
    wp_die();
  }

  // 3) Datos
  $nombre  = sanitize_text_field( $_POST['nombre'] ?? '' );
  $email   = sanitize_email( $_POST['email'] ?? '' );
  $asunto  = sanitize_text_field( $_POST['asunto'] ?? '' );
  $mensaje = sanitize_textarea_field( $_POST['mensaje'] ?? '' );

  if ( empty($nombre) || empty($email) || empty($asunto) || empty($mensaje) ) {
    wp_send_json_error( '🚨 Por favor completa todos los campos requeridos.' );
    wp_die();
  }

  if ( !is_email($email) ) {
    wp_send_json_error( '❌ Dirección de correo inválida. Por favor, revísala.' );
    wp_die();
  }

  // 4) Email
  $para    = 'carolinasemprini@gmail.com';
  $subject = 'Mensaje de ' . $nombre . ' - ' . $asunto;

  $contenido = "<strong>Nombre:</strong> {$nombre}<br>
                <strong>Email:</strong> {$email}<br>
                <strong>Asunto:</strong> {$asunto}<br>
                <strong>Mensaje:</strong><br>
                <p>{$mensaje}</p>";

  // From seguro para SMTP + Reply-To del usuario
  $from_email = semprini_get_default_from_email();
  $from_name  = 'Semprini Carolina';

  $cabeceras = [
    'Content-Type: text/html; charset=UTF-8',
    'Reply-To: ' . $email,
    'From: ' . $from_name . ' <' . $from_email . '>',
  ];

  if ( wp_mail( $para, $subject, $contenido, $cabeceras ) ) {
    $custom_success = '✅ ¡Mensaje enviado con éxito! Gracias por comunicarte con Carolina Semprini, te responderé a la brevedad. 😊';
    wp_send_json_success( $custom_success );
  } else {
    wp_send_json_error( '❌ Ha ocurrido un error. No se ha podido enviar el mensaje. Intenta nuevamente.' );
  }

  wp_die();
}
add_action( 'wp_ajax_semprini_send_email', 'semprini_send_email_handler' );
add_action( 'wp_ajax_nopriv_semprini_send_email', 'semprini_send_email_handler' );