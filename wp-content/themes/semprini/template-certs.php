<?php
/*
Template Name: Certificaciones
*/
get_header();

function af($k,$d=''){ if(!function_exists('get_field')) return $d; $v=get_field($k); return ($v===null||$v==='')?$d:$v; }
?>

<section class="certs-hero">
  <div class="about-wrap">
    <div class="certs-card fade-in">
      <header class="certs-head">
        <h1><?php echo esc_html( get_the_title() ?: 'Certificaciones' ); ?></h1>
        <?php if (has_excerpt()) : ?>
          <p class="sub"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
        <?php endif; ?>
      </header>

      <?php
      // Query de certificados (orden manual + título)
      $q = new WP_Query([
        'post_type'      => 'certificado',
        'posts_per_page' => -1,
        'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
      ]);
      if ($q->have_posts()): ?>
        <div class="certs-grid">
          <?php while($q->have_posts()): $q->the_post();
            $emisor = function_exists('get_field') ? get_field('cert_emisor') : '';
            $anio   = function_exists('get_field') ? get_field('cert_anio')   : '';
            $url    = function_exists('get_field') ? get_field('cert_url')    : '';
            $thumb  = get_the_post_thumbnail_url(get_the_ID(), 'large');
          ?>
            <article class="cert-card">
  <?php if ($thumb): ?>
    <!-- La imagen ocupa TODO el card; clic abre lightbox -->
    <a href="<?php echo esc_url($thumb); ?>"
       data-lightbox="certs"
       data-title="<?php echo esc_attr(get_the_title()); ?>"
       class="cert-media">
      <img class="cert-img" src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
    </a>
  <?php endif; ?>

  <!-- Banda inferior con datos ACF -->
  <div class="cert-info">
    <h3 class="cert-title"><?php the_title(); ?></h3>
    <div class="cert-meta">
      <?php if($emisor): ?><span class="cert-issuer"><?php echo esc_html($emisor); ?></span><?php endif; ?>
      <?php if($anio): ?><span class="cert-year"> · <?php echo esc_html($anio); ?></span><?php endif; ?>
    </div>
    <?php if($url): ?>
      <a class="btn-3d btn-glow btn-cert" target="_blank" rel="noopener" href="<?php echo esc_url($url); ?>">
        <span class="glow-layer" aria-hidden="true"></span>Ver certificado
      </a>
    <?php endif; ?>
  </div>
</article>

          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else: ?>
        <p style="color:#cfefff">Aún no has cargado certificados.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
