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

  //work-filters 

  wp_enqueue_script('semprini-work-filters', get_template_directory_uri().'/js/work-filters.js', [], null, true);
  wp_enqueue_style('semprini-work', get_template_directory_uri().'/css/work.css', ['semprini-style'], null);

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

  // Lightbox (certificados)
  // Lightbox2 (para ampliar certificados)
  wp_enqueue_style('lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css', [], '2.11.4');
  wp_enqueue_script('lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', ['jquery'], '2.11.4', true);

  // Modal video (Home/Portafolio)
wp_enqueue_script('semprini-modal-video', get_template_directory_uri().'/js/modal-video.js', [], null, true);

  // Smooth scroll para enlaces internos (opcional)
wp_enqueue_script(
  'semprini-scroll-to',
  get_template_directory_uri() . '/js/scroll-to.js',
  [],
  null,
  true
);

// Estilos para la página de Servicios (solo se carga en esa página)
if ( is_page_template('template-services.php') ) {
    wp_enqueue_style('semprini-services', get_template_directory_uri().'/css/services.css', ['semprini-hero'], null);
}

//blog
// CSS del BLOG utiliza estilos de services.css
  // Asegúrate de que esta línea esté presente:
wp_enqueue_style('semprini-services', get_template_directory_uri().'/css/services.css', ['semprini-style'], null); 

// Y si tienes un blog.css, que dependa de services.css para aplicar retoques:
wp_enqueue_style('semprini-blog', get_template_directory_uri().'/css/blog.css', ['semprini-style', 'semprini-services'], null); 

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

// ACF Certs fields
$acf_certs = get_template_directory() . '/inc/acf-certs-fields.php';
if (file_exists($acf_certs)) require_once $acf_certs;

// ACF Proyectos
$acf_proj = get_template_directory() . '/inc/acf-project-fields.php';
if (file_exists($acf_proj)) require_once $acf_proj;


// Contacto
// =========================================================================
// INTEGRACIÓN DE FONT AWESOME
// Carga Font Awesome 6 Free (estilos SOLID, REGULAR, BRAND) de forma optimizada.
// =========================================================================
function semprini_enqueue_font_awesome() {
    // URL de la hoja de estilos de Font Awesome Free CDN
    $fa_url = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css';
    
    // El 'handle' es 'font-awesome', la URL, dependencias (ninguna), versión, y media (all)
    wp_enqueue_style( 'font-awesome', $fa_url, array(), '6.5.2', 'all' );
}
add_action( 'wp_enqueue_scripts', 'semprini_enqueue_font_awesome' );


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