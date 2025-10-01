<?php 
/**
 * Plantilla de Post Individual (Corregida con Estilo Card)
 */
get_header(); 
?>
<main class="page-single">
    <div class="about-wrap">
        <?php while ( have_posts() ) : the_post(); ?>
            
            <div class="single-post-card fade-in">
                
                <article <?php post_class(); ?>>
                    
                    <h1 class="single-title"><?php the_title(); ?></h1> 
                    
                    <span class="meta single-meta"><?php the_time('F j, Y'); ?></span>
                    
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="single-featured-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="back-to-blog btn-3d btn-blog-effect">
                        <span class="glow-layer" aria-hidden="true"></span>
                        ← Volver al Blog
                    </a>

                </article>
            </div>
            <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>