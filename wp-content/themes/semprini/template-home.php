<?php
/*
Template Name: Página de Inicio
*/
get_header();

/**
 * Helpers para obtener URLs desde ACF (retorno Array o URL) y assets del tema
 */
function acf_url($field) {
  if (!function_exists('get_field')) return '';
  $v = get_field($field);
  if (is_array($v) && !empty($v['url'])) return $v['url'];
  if (is_string($v)) return $v;
  return '';
}
function asset_url($rel) {
  return trailingslashit(get_template_directory_uri()) . ltrim($rel, '/');
}
function asset_exists($rel) {
  return file_exists(trailingslashit(get_template_directory()) . ltrim($rel, '/'));
}

/** URLs desde ACF o fallbacks /img */
$video_url = acf_url('hero_video_fondo');
if (!$video_url && asset_exists('img/video.mp4')) $video_url = asset_url('img/video.mp4');

$bg_url = acf_url('hero_imagen_fondo'); // sólo si no hubiera video
$logo_url = acf_url('hero_logo');
if (!$logo_url && asset_exists('img/logosemprini.svg')) $logo_url = asset_url('img/logosemprini.svg');
?>

<section id="home" class="hero">
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.1/dist/dotlottie-wc.js" type="module"></script>
    
  <!-- Capa video de fondo -->
  <?php if ($video_url): ?>
    <video class="hero-video" autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url( get_template_directory_uri().'/img/hero-fallback.jpg'); ?>">
      <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
    </video>
  <?php elseif ($bg_url): ?>
    <?php $is_svg = (strtolower(pathinfo(parse_url($bg_url, PHP_URL_PATH), PATHINFO_EXTENSION)) === 'svg'); ?>
    <?php if ($is_svg): ?>
      <img class="hero-bg" src="<?php echo esc_url($bg_url); ?>" alt="Fondo">
    <?php else: ?>
      <div class="hero-bg" style="background-image:url('<?php echo esc_url($bg_url); ?>');"></div>
    <?php endif; ?>
  <?php endif; ?>

  <!--inicio de animacion para celular dotlottie-->
   <div class="dotlottie-bg-mobile">
      <dotlottie-wc 
          src="https://lottie.host/3264f706-c0fb-4200-accf-381feadbff7f/Z34mh6gLiu.lottie" 
          style="width: 100%; height: 100%;" 
          autoplay 
          loop 
          class="lottie-player-el">
      </dotlottie-wc>
  </div>

      <!--Inicio de bloque para 3d spline-->

        <div class="hero-3d-model-container">
   <script type="module" src="https://unpkg.com/@splinetool/viewer@1.10.74/build/spline-viewer.js"></script>
<spline-viewer url="https://prod.spline.design/0vAf8XPKYitoe-7P/scene.splinecode"></spline-viewer>
<div class="spline-watermark-hider"></div>
      </div>

  
  <!--Fin de bloque para 3d spline-->

  <!-- Oscurecedor para contraste -->
  <div class="hero-overlay" aria-hidden="true"></div>

  <!-- Contenedor grid -->
  <div class="hero-container">
    <div class="hero-grid">
      <!-- Columna izquierda: CARD del logo -->
      <div class="logo-card fade-in">
        <?php if ($logo_url): ?>
          <img class="logo-img" src="<?php echo esc_url($logo_url); ?>" alt="Semprini.dev">
        <?php else: ?>
          <div class="logo-placeholder">Semprini.dev</div>
        <?php endif; ?>
      </div>

      <!-- Columna derecha: texto de presentación -->
      <div class="hero-content">
        <h1 class="hero-title fade-in">
          <?php echo esc_html(function_exists('get_field') ? (get_field('hero_titulo') ?: '') : ''); ?>
        </h1>

        <p class="hero-subtitle fade-in">
          <?php echo esc_html(function_exists('get_field') ? (get_field('hero_subtitulo') ?: '') : ''); ?>
        </p>

        <div class="hero-location fade-in">
          <?php echo esc_html(function_exists('get_field') ? (get_field('ubicacion') ?: '') : ''); ?>
        </div>

         <!-- URL del portafolio (archivo del CPT "proyecto") para que el botón "Ver mi trabajo" lleve ahí -->
        <?php 
        $portfolio_url = get_post_type_archive_link('proyecto');
        if (!$portfolio_url) {
        $portfolio_url = home_url('/portafolio'); // fallback si no existe
        }
        ?>
        <a href="<?php echo esc_url($portfolio_url); ?>" class="btn-3d btn-3d--primary btn-3d--lg btn-glow fade-in fade-in" aria-label="Ver mi trabajo">
           <span class="glow-layer" aria-hidden="true"></span>
            Ver mi trabajo
        </a>


        <!-- Redes -->
         <?php
    // Lee redes desde ACF con fallbacks
    $url_linkedin = function_exists('get_field') ? ( get_field('social_linkedin') ?: 'https://www.linkedin.com/in/semprinicarolina' ) : '#';
    $url_github   = function_exists('get_field') ? ( get_field('social_github')   ?: 'https://github.com/' ) : '#';

    // Email: guardado como dirección pura → creamos mailto
    $email_addr   = function_exists('get_field') ? ( get_field('social_email')    ?: 'carolinasemprini@gmail.com' ) : '';
    $url_email    = $email_addr ? 'mailto:' . antispambot( $email_addr ) : '#';

    // WhatsApp: si hay número (solo dígitos), armamos wa.me
    $wa_number    = function_exists('get_field') ? ( preg_replace('/\D+/', '', (string) get_field('social_whatsapp')) ) : '';
    $url_whatsapp = $wa_number ? ('https://wa.me/' . $wa_number) : '';
  ?>

  <!-- Redes (pill) con 3D + Glow -->
  <div class="social-inline fade-in" aria-label="Redes sociales">
    <?php if ($url_linkedin): ?>
      <a href="<?php echo esc_url($url_linkedin); ?>" target="_blank" rel="noopener"
         class="btn-3d btn-3d--pill btn-glow">
        <span class="glow-layer" aria-hidden="true"></span>LinkedIn
      </a>
    <?php endif; ?>

    <?php if ($email_addr): ?>
      <a href="<?php echo esc_attr($url_email); ?>" class="btn-3d btn-3d--pill btn-glow">
        <span class="glow-layer" aria-hidden="true"></span>Email
      </a>
    <?php endif; ?>

    <?php if ($url_github): ?>
      <a href="<?php echo esc_url($url_github); ?>" target="_blank" rel="noopener"
         class="btn-3d btn-3d--pill btn-glow">
        <span class="glow-layer" aria-hidden="true"></span>GitHub
      </a>
    <?php endif; ?>
   <!-- Secundario / link -->
<a class="btn-3d btn-3d--secondary btn-3d--pill btn-glow"><span class="glow-layer"></span>Contacto</a>
    
  </div>
    <!-- Fin redes -->
     

    </div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES / HIGHLIGHTS -->
<section id="services" class="home-services">
  <div class="home-wrap">
    <div class="svc-grid">
      <?php 
      // URL del portafolio (archivo del CPT "proyecto")
      $portfolio_url = get_post_type_archive_link('proyecto');
      if (!$portfolio_url) {
      $portfolio_url = home_url('/portafolio'); // fallback si no existe
      }
      ?>
      <article class="svc-card fade-in">
        <h3>Aplicaciones Web</h3>
        <p>Front-end moderno, performance y SEO técnico. Experiencias fluidas y accesibles.</p>
        <a href="<?php echo esc_url($portfolio_url); ?>" class="btn-3d btn-3d--link btn-glow">
          <span class="glow-layer"></span>Ver casos
        </a>
      </article>

    <article class="svc-card fade-in">
      <h3>Datos & Automatización</h3>
      <p>Dashboards, ETL, integraciones y procesos automatizados para tu negocio.</p>
      <a href="<?php echo esc_url($portfolio_url); ?>" class="btn-3d btn-3d--link btn-glow">
        <span class="glow-layer"></span>Proyectos
      </a>
</article>
      <article class="svc-card fade-in">
        <h3>GobTech</h3>
        <p>Tecnología aplicada al sector público: eficiencia, transparencia y servicios digitales.</p>
        <a href="/contact" class="btn-3d btn-3d--link btn-glow"><span class="glow-layer"></span>Hablemos</a>
      </article>
    </div>
  </div>
</section>

<!-- STATS / BAND -->
<section id="stats" class="home-stats">
  <div class="home-wrap stats-row fade-in">
    <div><strong>+<?php echo esc_html( get_field('stat_proyectos') ?: '20' ); ?></strong><span>Proyectos</span></div>
    <div><strong><?php echo esc_html( get_field('stat_anios') ?: '5+' ); ?></strong><span>Años de experiencia</span></div>
    <div><strong><?php echo esc_html( get_field('stat_stack') ?: '10+' ); ?></strong><span>Tecnologías</span></div>
  </div>
</section>

<!-- TEASER WORK -->
<?php
  // URL por defecto: archivo del CPT 'proyecto' (ej: /portafolio/)
  $portfolio_url = get_post_type_archive_link('proyecto');

  // Si no hay archivo (has_archive=false) usamos el campo ACF; si tampoco, /work
  $acf_url = function_exists('get_field') ? get_field('work_btn_url') : '';
  if (!$portfolio_url) {
    $portfolio_url = $acf_url ?: home_url('/work');
  }

  $work_heading = function_exists('get_field') ? (get_field('work_heading') ?: 'Trabajo Destacado') : 'Trabajo Destacado';
  $work_lead    = function_exists('get_field') ? (get_field('work_lead')    ?: 'Una selección de proyectos representativos.') : 'Una selección de proyectos representativos.';
  $work_btn_txt = function_exists('get_field') ? (get_field('work_btn_text') ?: 'Ir a Portafolio') : 'Ir a Portafolio';

  // Obtener la URL “cruda” del campo oEmbed (no el HTML)
$oembed_url = function_exists('get_field') ? get_field('proj_video_oembed', false, false) : '';
$file       = function_exists('get_field') ? get_field('proj_video_file') : '';
$poster     = function_exists('get_field') ? get_field('proj_video_poster') : '';
$poster_url = (is_array($poster) && !empty($poster['url'])) ? $poster['url'] : '';

$video_url = '';
if (!empty($oembed_url)) {
  $video_url = esc_url($oembed_url);          // YouTube/Vimeo (watch URL)
} elseif (is_array($file) && !empty($file['url'])) {
  $video_url = esc_url($file['url']);         // MP4 subido
}
?>
<!-- TEASER WORK -->
<section id="work" class="home-work">
  <div class="home-wrap fade-in">
    <h2><?php echo esc_html($work_heading); ?></h2>
    <p class="lead"><?php echo esc_html($work_lead); ?></p>

    <a href="<?php echo esc_url($portfolio_url); ?>" class="btn-3d btn-3d--primary btn-glow">
      <span class="glow-layer" aria-hidden="true"></span><?php echo esc_html($work_btn_txt); ?>
    </a>
  </div>
</section>



<!-- CONTACT CTA -->
<section id="contact" class="home-cta">
  <div class="home-wrap cta-row fade-in">
    <h3><?php echo esc_html( get_field('cta_text') ?: '¿Listos para crear algo increíble?' ); ?></h3>
    <?php
      $ctext = get_field('cta_button_text') ?: 'Contactar';
      $curl  = get_field('cta_button_url') ?: '/contact';
    ?>
    <a href="<?php echo esc_url($curl); ?>" class="btn-3d btn-3d--primary btn-3d--lg btn-glow">
      <span class="glow-layer" aria-hidden="true"></span><?php echo esc_html($ctext); ?>
    </a>
  </div>
</section>

<?php get_footer(); ?>
