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
$logo_url = acf_url('hero_logo');
if (!$logo_url && asset_exists('img/logosemprini.svg')) {
  $logo_url = asset_url('img/logosemprini.svg');
}

?>

<!-- HERO -->
<section id="home" class="hero" aria-label="Hero">
  <!-- Video global + partículas están en header.php -->
  <div class="hero-container">
    <div class="hero-grid">

      <!-- IZQUIERDA: LOGO -->
      <div class="logo-card fade-in">
        <?php if ($logo_url): ?>
          <img class="logo-img" src="<?php echo esc_url($logo_url); ?>" alt="Semprini.dev">
        <?php else: ?>
          <div class="logo-placeholder">Semprini.dev</div>
        <?php endif; ?>
      </div>

      <!-- DERECHA: TEXTO -->
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

        <?php
        $portfolio_url = get_post_type_archive_link('proyecto');
        if (!$portfolio_url) {
          $portfolio_url = home_url('/portafolio');
        }
        ?>
        <a href="<?php echo esc_url($portfolio_url); ?>" class="btn-3d btn-3d--primary btn-3d--lg btn-glow fade-in" aria-label="Ver mi trabajo">
          <span class="glow-layer" aria-hidden="true"></span>
          Ver mi trabajo
        </a>

        <!-- REDES -->
        <?php
          $url_linkedin = function_exists('get_field') ? ( get_field('social_linkedin') ?: 'https://www.linkedin.com/in/semprinicarolina' ) : '#';
          $url_github   = function_exists('get_field') ? ( get_field('social_github')   ?: 'https://github.com/' ) : '#';

          $email_addr   = function_exists('get_field') ? ( get_field('social_email')    ?: 'carolinasemprini@gmail.com' ) : '';
          $url_email    = $email_addr ? 'mailto:' . antispambot( $email_addr ) : '#';

          $wa_number    = function_exists('get_field') ? ( preg_replace('/\D+/', '', (string) get_field('social_whatsapp')) ) : '';
          $url_whatsapp = $wa_number ? ('https://wa.me/' . $wa_number) : '';
        ?>

        <div class="social-inline fade-in" aria-label="Redes sociales">
          <?php if ($url_linkedin): ?>
            <a href="<?php echo esc_url($url_linkedin); ?>" target="_blank" rel="noopener" class="btn-3d btn-3d--pill btn-glow">
              <span class="glow-layer" aria-hidden="true"></span>LinkedIn
            </a>
          <?php endif; ?>
          <?php if ($email_addr): ?>
            <a href="<?php echo esc_attr($url_email); ?>" class="btn-3d btn-3d--pill btn-glow">
              <span class="glow-layer" aria-hidden="true"></span>Email
            </a>
          <?php endif; ?>
          <?php if ($url_github): ?>
            <a href="<?php echo esc_url($url_github); ?>" target="_blank" rel="noopener" class="btn-3d btn-3d--pill btn-glow">
              <span class="glow-layer" aria-hidden="true"></span>GitHub
            </a>
          <?php endif; ?>
          <?php if ($url_whatsapp): ?>
            <a href="<?php echo esc_url($url_whatsapp); ?>" target="_blank" rel="noopener" class="btn-3d btn-3d--pill btn-glow">
              <span class="glow-layer" aria-hidden="true"></span>WhatsApp
            </a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</section>


<!-- SERVICES / HIGHLIGHTS -->
<section id="services" class="home-services">
  <div class="home-wrap">
    <div class="svc-grid">

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


<!-- STATS -->
<section id="stats" class="home-stats">
  <div class="home-wrap stats-row fade-in">
    <div><strong>+<?php echo esc_html( get_field('stat_proyectos') ?: '20' ); ?></strong><span>Proyectos</span></div>
    <div><strong><?php echo esc_html( get_field('stat_anios') ?: '5+' ); ?></strong><span>Años de experiencia</span></div>
    <div><strong><?php echo esc_html( get_field('stat_stack') ?: '10+' ); ?></strong><span>Tecnologías</span></div>
  </div>
</section>


<!-- WORK TEASER -->
<?php
  $portfolio_url = get_post_type_archive_link('proyecto');
  if (!$portfolio_url) {
    $portfolio_url = home_url('/portafolio');
  }

  $work_heading = get_field('work_heading') ?: 'Trabajo Destacado';
  $work_lead    = get_field('work_lead')    ?: 'Una selección de proyectos representativos.';
  $work_btn_txt = get_field('work_btn_text') ?: 'Ir a Portafolio';
?>
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
