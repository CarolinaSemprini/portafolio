<?php
/*
Template Name: Sobre mí
*/
get_header();

/* ============================================================
   Helpers ACF robustos y utilidades
   ============================================================ */

/**
 * af: obtiene un campo de ACF o devuelve un valor por defecto.
 */
function af($k, $def = '') {
  if (!function_exists('get_field')) return $def;
  $v = get_field($k);
  return ($v === null || $v === '') ? $def : $v;
}

/**
 * af_media_url: obtiene la URL de un campo de ACF que puede ser:
 * - Array (return_format = array),
 * - ID numérico (return_format = id),
 * - String URL (return_format = url).
 */
function af_media_url($k){
  if (!function_exists('get_field')) return '';
  $v = get_field($k);
  if (is_array($v) && !empty($v['url'])) return $v['url'];
  if (is_numeric($v)) {
    $u = wp_get_attachment_url((int)$v);
    if ($u) return $u;
  }
  if (is_string($v) && $v !== '') return $v;
  return '';
}

/* ============================================================
   Contenidos ACF / WP
   ============================================================ */

// Foto principal (fallback a imagen del hero si no hay)
$foto_url  = af_media_url('about_foto');
if (!$foto_url) $foto_url = af_media_url('hero_imagen_fondo');

// Título / subtítulo: si ACF vacío, usamos los de WP
$titulo    = af('about_titulo', get_the_title());
$subtitulo = af('about_subtitulo', 'Desarrollo Full-Stack & Ciencia de Datos');

// Contenido del editor de WP (con shortcodes y formato)
$raw_content   = get_post_field('post_content', get_the_ID());
$content_html  = apply_filters('the_content', $raw_content);
$content_plain = trim( wp_strip_all_tags( $raw_content ) );

// Bio ACF como fallback si el editor está vacío
$bio_acf = af('about_bio', '');

// Skills (para futuros usos / destacados por defecto)
$skills_raw  = af('about_skills', "JavaScript\nReact\nNode.js\nPython\nPandas\nSQL\nWordPress\nACF\nDocker");
$skills_list = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $skills_raw)));

// Experiencia (hasta 5 filas)
$exp = [];
for ($i=1; $i<=5; $i++){
  $e = [
    'anio'    => af("exp{$i}_anio"),
    'rol'     => af("exp{$i}_rol"),
    'empresa' => af("exp{$i}_empresa"),
    'desc'    => af("exp{$i}_desc"),
  ];
  if ($e['anio'] || $e['rol'] || $e['empresa'] || $e['desc']) $exp[] = $e;
}

// Botones principales
$cv_url       = af_media_url('about_cv_pdf');
$linkedin_url = af('social_linkedin', 'https://www.linkedin.com/in/semprinicarolina');
$github_url   = af('social_github',   'https://github.com/');
$email_addr   = af('social_email',    'carolinasemprini@gmail.com');
$mailto       = 'mailto:' . antispambot($email_addr);

// Tecnologías destacadas (textarea con "Etiqueta | URL" por línea)
$feat_raw   = af('about_featured_stack', '');
$feat_title = af('about_featured_title', 'Tecnologías destacadas');
$feat_desc  = af('about_featured_desc',  'Stack con el que diseño, construyo y escalo productos.');

$feat_items = [];
if ($feat_raw) {
  foreach (preg_split('/\r\n|\r|\n/', $feat_raw) as $line) {
    $line = trim($line);
    if (!$line) continue;
    $parts = array_map('trim', explode('|', $line, 2)); // Etiqueta | URL (opcional)
    $label = $parts[0] ?? '';
    $url   = $parts[1] ?? '';
    if ($label !== '') $feat_items[] = ['label'=>$label, 'url'=>$url];
  }
}
// Fallback: si no cargaste nada en “destacados”, usamos los primeros 8 skills sin URL
if (empty($feat_items) && !empty($skills_list)) {
  foreach (array_slice($skills_list, 0, 8) as $s) {
    $feat_items[] = ['label'=>$s, 'url'=>''];
  }
}
?>

<!-- =========================
     SOBRE MÍ — HERO (foto + copy)
     ========================= -->
<section id="about" class="about-hero">
  <div class="about-wrap">
    <div class="about-grid">

      <!-- Columna izquierda: card con foto -->
      <div class="about-photo-card fade-in">
        <?php if ($foto_url): ?>
          <img class="about-photo" src="<?php echo esc_url($foto_url); ?>" alt="Foto de <?php bloginfo('name'); ?>">
        <?php else: ?>
          <div class="about-photo ph">CS</div>
        <?php endif; ?>
      </div>

      <!-- Columna derecha: contenido -->
      <div class="about-content">
        <h1 class="about-title fade-in"><?php echo esc_html($titulo); ?></h1>
        <p class="about-subtitle fade-in"><?php echo esc_html($subtitulo); ?></p>

        <!-- Acciones arriba (CV + redes) -->
        <div class="about-actions fade-in">
          <?php if ($cv_url): ?>
            <a href="<?php echo esc_url($cv_url); ?>" target="_blank" rel="noopener"
               class="btn-3d btn-3d--primary btn-3d--lg btn-glow">
              <span class="glow-layer" aria-hidden="true"></span>Descargar CV
            </a>
          <?php endif; ?>
          <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener"
             class="btn-3d btn-3d--pill btn-glow"><span class="glow-layer"></span>LinkedIn</a>
          <a href="<?php echo esc_url($github_url); ?>" target="_blank" rel="noopener"
             class="btn-3d btn-3d--pill btn-glow"><span class="glow-layer"></span>GitHub</a>
          <a href="<?php echo esc_attr($mailto); ?>"
             class="btn-3d btn-3d--pill btn-glow"><span class="glow-layer"></span>Email</a>
        </div>

        <!-- Texto (editor de WP o bio ACF como fallback) -->
        <?php if (!empty($content_plain)) : ?>
          <div class="about-wpcontent about-copy fade-in">
            <?php echo $content_html; ?>
          </div>
        <?php elseif (!empty($bio_acf)) : ?>
          <div class="about-bio about-copy fade-in">
            <?php echo wp_kses_post( $bio_acf ); ?>
          </div>
        <?php endif; ?>

        <?php
          // IMPORTANTE: eliminamos cualquier bloque de chips/stack dentro de la columna
          // (los “chips” ahora viven en la sección independiente de “Tecnologías destacadas”).
        ?>
      </div><!-- /.about-content -->

    </div><!-- /.about-grid -->
  </div><!-- /.about-wrap -->
</section>

<!-- =========================
     TECNOLOGÍAS DESTACADAS (sección independiente)
     ========================= -->
<?php if (!empty($feat_items)): ?>
<section class="about-featured fade-in" id="stack">
  <div class="about-wrap">
    <div class="feat-head">
      <h2><?php echo esc_html($feat_title); ?></h2>
      <?php if ($feat_desc): ?><p class="feat-desc"><?php echo esc_html($feat_desc); ?></p><?php endif; ?>
    </div>

    <div class="feat-grid">
      <?php foreach ($feat_items as $it):
        $label = esc_html($it['label']);
        $url   = trim($it['url']);
        $attrs = $url ? 'href="'.esc_url($url).'" target="_blank" rel="noopener"' : 'href="#"';
      ?>
        <a <?php echo $attrs; ?> class="btn-3d btn-3d--pill btn-glow btn-feat">
          <span class="glow-layer" aria-hidden="true"></span><?php echo $label; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- =========================
     TIMELINE DE EXPERIENCIA
     ========================= -->
<?php if (!empty($exp)): ?>
<section class="about-timeline">
  <div class="about-wrap">
    <h2 class="fade-in">Experiencia</h2>
    <div class="tl-list">
      <?php foreach ($exp as $item): ?>
        <article class="tl-item fade-in">
          <div class="tl-dot" aria-hidden="true"></div>
          <div class="tl-card">
            <div class="tl-meta">
              <span class="tl-year"><?php echo esc_html($item['anio']); ?></span>
              <span class="tl-divider">—</span>
              <span class="tl-company"><?php echo esc_html($item['empresa']); ?></span>
            </div>
            <h3 class="tl-role"><?php echo esc_html($item['rol']); ?></h3>
            <?php if (!empty($item['desc'])): ?>
              <p class="tl-desc"><?php echo esc_html($item['desc']); ?></p>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
