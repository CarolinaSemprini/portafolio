<?php
/**
 * Plantilla del Blog (Listado de Entradas)
 * Estilo Cyberpunk: Arcoíris en la línea del borde, contenido centrado y fecha con efecto vidrio.
 */
get_header();

// Obtener el título y subtítulo para el listado
$blog_title    = get_option('page_for_posts') ? get_the_title(get_option('page_for_posts')) : 'Insights Tecnológicos';
$blog_subtitle = 'Análisis de datos, desarrollo Full Stack y reflexiones sobre la transformación digital en la gestión.';
?>

<main class="page-blog">
    
    <section class="blog-hero">
        <div class="about-wrap">
            <header class="blog-head fade-in">
                <h1><?php echo esc_html($blog_title); ?></h1>
                <p class="sub"><?php echo esc_html($blog_subtitle); ?></p>
            </header>
        </div>
    </section>

    <section class="blog-list-section">
        <div class="about-wrap">
            <div class="blog-list custom-blog-grid"> 
                
                <?php if ( have_posts() ) : ?>
                    
                    <?php while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-card fade-in'); ?>>
                            
                            <div class="blog-date-box glass-effect">
                                <span class="blog-meta-date"><?php the_time('F j, Y'); ?></span>
                                <span class="glass-effect__drop" aria-hidden="true"></span>
                            </div>

                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="blog-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="blog-content blog-content--centered"> 
                                
                                <h2 class="blog-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="blog-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="blog-read-more btn-3d btn-blog-effect">
                                    <span class="glow-layer" aria-hidden="true"></span>
                                    Leer más &rarr;
                                </a>
                            </div>
                        </article>
                    
                    <?php endwhile; ?>
                    
                    <?php the_posts_pagination(); ?>

                <?php else : ?>
                    <p>Aún no hay entradas de blog publicadas. ¡Pronto habrá contenido genial!</p>
                <?php endif; ?>

            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>