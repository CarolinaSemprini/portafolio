<?php
/**
 * Header del tema (optimizado)
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
  // Obtenemos URLs desde helpers (ACF)
  $bg_video = function_exists('semprini_get_bg_video_url') ? semprini_get_bg_video_url() : get_template_directory_uri().'/img/video.mp4';
  $bg_poster = function_exists('semprini_get_bg_poster_url') ? semprini_get_bg_poster_url() : '';
  // Nota: no ponemos <source> directo para evitar descarga inmediata.
?>
<!-- Contenedor global de video (carga diferida via hero-init.js) -->
<video id="global-hero-video"
       class="hero-video"
       autoplay
       muted
       loop
       playsinline
       preload="none"
       poster="<?php echo esc_attr($bg_poster); ?>"
       data-src="<?php echo esc_url($bg_video); ?>"
       aria-hidden="true"
       ></video>

<!-- Overlay oscuro (sigue existiendo) -->
<div class="hero-overlay" aria-hidden="true"></div>

<!-- Canvas global de partículas (se pintará encima del overlay) -->
<canvas id="global-particles" aria-hidden="true"></canvas>

<header class="site-header" role="banner">
  <div class="nav-wrap">
    <a href="<?php echo esc_url(home_url('/')); ?>#home" class="brand">
      <?php $logo_path = get_template_directory_uri().'/img/logosemprini.svg'; ?>
      <img src="<?php echo esc_url($logo_path); ?>" alt="Semprini.dev" class="brand-logo" />
      <span class="brand-text">Semprini.dev</span>
    </a>

    <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="main-nav" role="navigation">
      <?php
      wp_nav_menu([
        'theme_location' => 'main_menu',
        'container'      => false,
        'menu_class'     => 'menu',
        'fallback_cb'    => function() {
          echo '<ul class="menu">'
              .'<li><a href="#home">Inicio</a></li>'
              .'<li><a href="#services">Servicios</a></li>'
              .'<li><a href="#stats">Stats</a></li>'
              .'<li><a href="#work">Work</a></li>'
              .'<li><a href="#contact">Contacto</a></li>'
              .'</ul>';
        }
      ]);
      ?>
    </nav>
  </div>
</header>
