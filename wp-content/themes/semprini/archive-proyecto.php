<?php
/**
 * Archivo del CPT Proyecto (Portafolio completo)
 * - Mismas tarjetas que Home, con botones: Demo, Repo, Case y Video (modal)
 * - Paginación
 */
get_header();

// Helpers ACF seguros
function af($k,$d=''){ if(!function_exists('get_field')) return $d; $v=get_field($k); return ($v===null||$v==='')?$d:$v; }

$ppp = 9; // proyectos por página
$paged = max(1, get_query_var('paged'));
$q = new WP_Query([
    'post_type'              => 'proyecto',
    'posts_per_page'         => 9,
    'paged'                  => $paged,
    'orderby'                => ['date' => 'DESC', 'title' => 'ASC'],
    'no_found_rows'          => false, // porque usás paginación
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
]);

?>

<main class="portfolio-archive">
    <div class="wrap" style="max-width:1200px;margin:0 auto;padding:clamp(24px,5vw,56px) 16px;">
        
        <div class="portfolio-header-card fade-in">
            
            <header class="hw-head">
                <h1>Portafolio</h1>
                <p class="sub">Todos mis proyectos publicados. Usa los accesos a demo, repositorio, case y video.</p>
            </header>
            
            <div class="portfolio-grid"> 
                <?php if ($q->have_posts()): while($q->have_posts()): $q->the_post();
                    // Campos
                    $live   = function_exists('get_field') ? get_field('proj_live_url') : '';
                    $repo   = function_exists('get_field') ? get_field('proj_repo_url') : '';
                    $case   = function_exists('get_field') ? get_field('proj_case_url') : '';

                    $oembed_url = function_exists('get_field') ? get_field('proj_video_oembed', false, false) : '';
                    $file       = function_exists('get_field') ? get_field('proj_video_file') : '';
                    $poster     = function_exists('get_field') ? get_field('proj_video_poster') : '';
                    $poster_url = (is_array($poster) && !empty($poster['url'])) ? $poster['url'] : '';

                    $video_url = '';
                    if (!empty($oembed_url)) {
                        $video_url = esc_url($oembed_url);
                    } elseif (is_array($file) && !empty($file['url'])) {
                        $video_url = esc_url($file['url']);
                    }

                    $thumb = get_the_post_thumbnail_url(get_the_ID(),'large');
                ?>
                    <article class="portfolio-card fade-in"> 
                        
                        <a href="<?php the_permalink(); ?>" class="card-thumb" aria-label="<?php the_title_attribute(); ?>">
                            <?php if ($thumb): ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>"><?php endif; ?>
                        </a>
                        
                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                        <div class="card-actions">
                            <?php if ($live): ?>
                                <a class="btn-3d btn-3d--pill btn-glow" target="_blank" rel="noopener" href="<?php echo esc_url($live); ?>">
                                    <span class="glow-layer" aria-hidden="true"></span>Demo 
                                </a>
                            <?php endif; ?>
                            <?php if ($repo): ?>
                                <a class="btn-3d btn-3d--pill btn-glow" target="_blank" rel="noopener" href="<?php echo esc_url($repo); ?>">
                                    <span class="glow-layer" aria-hidden="true"></span>Repo 
                                </a>
                            <?php endif; ?>
                            <?php if ($case): ?>
                                <a class="btn-3d btn-3d--pill btn-glow" target="_blank" rel="noopener" href="<?php echo esc_url($case); ?>">
                                    <span class="glow-layer" aria-hidden="true"></span>Case 
                                </a>
                            <?php endif; ?>

                            <?php if ($video_url): ?>
                                <button class="btn-3d btn-3d--pill btn-glow js-open-video"
                                        data-video="<?php echo $video_url; ?>"
                                        data-poster="<?php echo esc_url($poster_url); ?>">
                                    <span class="glow-layer" aria-hidden="true"></span>Video 
                                </button>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; else: ?>
                    <p style="color:#cfefff; grid-column:1/-1; text-align:center;">Aún no cargaste proyectos.</p>
                <?php endif; wp_reset_postdata(); ?>
            </div>
            
            <?php
            $big = 999999999; 
            $links = paginate_links([
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => $q->max_num_pages,
                'prev_text' => '← Anterior',
                'next_text' => 'Siguiente →',
                'type'      => 'list'
            ]);
            if ($links): ?>
                <nav class="portfolio-pagination" aria-label="Paginación" style="margin-top:22px;">
                    <?php echo $links; ?>
                </nav>
            <?php endif; ?>
        
        </div> 

    </div>
</main>

<?php get_footer(); ?>