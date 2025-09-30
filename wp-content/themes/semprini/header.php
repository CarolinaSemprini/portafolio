<?php
/**
 * Header del tema
 * - Nav sticky con blur, glow y menú móvil
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body <?php body_class(); ?>>

<?php
  // Video global de partículas + overlay (fijo)
  $bg_video = function_exists('semprini_get_bg_video_url') ? semprini_get_bg_video_url() : get_template_directory_uri().'/img/video.mp4';
  $bg_poster = function_exists('semprini_get_bg_poster_url') ? semprini_get_bg_poster_url() : '';
?>
<!-- Fondo de video global -->
<video class="hero-video" autoplay muted loop playsinline preload="metadata"
       poster="<?php echo esc_url($bg_poster); ?>">
  <source src="<?php echo esc_url($bg_video); ?>" type="video/mp4">
</video>
<div class="hero-overlay" aria-hidden="true"></div>

<header class="site-header" role="banner">
  <div class="nav-wrap">
    <!-- Marca: usa tu logo si existe, si no texto -->
    <a href="<?php echo esc_url(home_url('/')); ?>#home" class="brand">
      <?php
        $logo_path = get_template_directory_uri().'/img/logosemprini.svg';
      ?>
      <img src="<?php echo esc_url($logo_path); ?>" alt="Semprini.dev" class="brand-logo" />
      <span class="brand-text">Semprini.dev</span>
    </a>

    <!-- Botón hamburguesa (móvil) -->
    <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <!-- Menú principal -->
    <nav class="main-nav" role="navigation">
      <?php
      wp_nav_menu([
        'theme_location' => 'main_menu',
        'container'      => false,
        'menu_class'     => 'menu',
        'fallback_cb'    => function() {
          // Fallback simple si no hay menú creado
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



