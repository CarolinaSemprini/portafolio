<?php
/**
 * Funciones del tema Semprini
 * - Soportes básicos del tema
 * - Enqueue de estilos y scripts
 * - Desactivar Gutenberg
 * - Habilitar subida de SVG
 * - Incluir archivos INC
 * - Helpers de video global
 */

/* -------------------------------------------------
 * 1) Setup del tema: título, thumbnails y menú
 * ------------------------------------------------- */
function semprini_setup() {
  add_theme_support('title-tag');        // Manejo de <title> por WP
  add_theme_support('post-thumbnails');  // Imágenes destacadas
  register_nav_menus([
    'main_menu' => 'Menú Principal',
  ]);
}
add_action('after_setup_theme', 'semprini_setup');


/* -------------------------------------------------
 * 2) Enqueue de CSS y JS del tema
 * ------------------------------------------------- */
function semprini_assets() {
  // CSS base (style.css)
  wp_enqueue_style('semprini-style', get_stylesheet_uri(), [], null);

  // Nav
  wp_enqueue_style('semprini-nav', get_template_directory_uri().'/css/nav.css', ['semprini-style'], null);
  wp_enqueue_script('semprini-nav', get_template_directory_uri().'/js/nav.js', ['jquery'], null, true);

  // Hero (video/overlay)
  wp_enqueue_style('semprini-hero', get_template_directory_uri().'/css/hero.css', ['semprini-style'], null);

  // Animaciones reutilizables (fade-in)
  wp_enqueue_style('semprini-animations', get_template_directory_uri().'/css/animations.css', ['semprini-style'], null);

  // Página Sobre mí
  wp_enqueue_style('semprini-about', get_template_directory_uri().'/css/about.css', ['semprini-style'], null);

  // JS: efectos de scroll para .fade-in
  wp_enqueue_script('semprini-scroll-effects', get_template_directory_uri().'/js/scroll-effects.js', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'semprini_assets');


/* -------------------------------------------------
 * 3) Desactivar Gutenberg (editor de bloques)
 * ------------------------------------------------- */
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);


/* -------------------------------------------------
 * 4) Habilitar subida de SVG (controlado)
 * ------------------------------------------------- */
// Permitir MIME SVG
function semprini_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'semprini_mime_types');

// Forzar tipo/ext correctos en SVG
function semprini_svg_filetype_check($data, $file, $filename, $mimes) {
  $ext = pathinfo($filename, PATHINFO_EXTENSION);
  if (strtolower($ext) === 'svg') {
    $data['ext']  = 'svg';
    $data['type'] = 'image/svg+xml';
  }
  return $data;
}
add_filter('wp_check_filetype_and_ext', 'semprini_svg_filetype_check', 10, 4);

// Mejorar vista de SVG en librería
function semprini_svg_admin_css() {
  echo '<style>.attachment .thumbnail img[src$=".svg"]{width:100%!important;height:auto!important;}</style>';
}
add_action('admin_head', 'semprini_svg_admin_css');


/* -------------------------------------------------
 * 5) Incluir archivos INC opcionales
 * ------------------------------------------------- */
$inc_cpt = get_template_directory() . '/inc/custom-post-types.php';
if ( file_exists($inc_cpt) ) {
  require_once $inc_cpt; // Registrar CPT "proyecto" si lo estás usando
}

// ACF Home fields
$acf_home = get_template_directory() . '/inc/acf-home-fields.php';
if ( file_exists($acf_home) ) {
  require_once $acf_home;
}

// ACF About fields
$acf_about = get_template_directory() . '/inc/acf-about-fields.php';
if ( file_exists($acf_about) ) {
  require_once $acf_about;
}


/* -------------------------------------------------
 * 6) Helpers: video de fondo GLOBAL
 * ------------------------------------------------- */
// Prioriza: campo ACF de la página actual -> ACF de la Home -> archivo /img/video.mp4 del tema
function semprini_get_bg_video_url() {
  if ( function_exists('get_field') ) {
    // 1) Campo de la página actual
    $v = get_field('hero_video_fondo'); // puede ser array/ID/URL
    if (is_array($v) && !empty($v['url'])) return $v['url'];
    if (is_numeric($v)) {
      $u = wp_get_attachment_url( (int) $v );
      if ($u) return $u;
    }
    if (is_string($v) && $v !== '') return $v;

    // 2) Campo de la página HOME (Ajustes -> Lectura)
    $home_id = (int) get_option('page_on_front');
    if ($home_id) {
      $vh = get_field('hero_video_fondo', $home_id);
      if (is_array($vh) && !empty($vh['url'])) return $vh['url'];
      if (is_numeric($vh)) {
        $uh = wp_get_attachment_url( (int) $vh );
        if ($uh) return $uh;
      }
      if (is_string($vh) && $vh !== '') return $vh;
    }
  }
  // 3) Fallback: archivo dentro del tema
  return get_template_directory_uri() . '/img/video.mp4';
}

// Poster opcional (imagen mostrada mientras carga el mp4)
function semprini_get_bg_poster_url() {
  return get_template_directory_uri() . '/img/hero-fallback.jpg';
}

/* No cierres con "?>" para evitar problemas de espacios/BOM */
