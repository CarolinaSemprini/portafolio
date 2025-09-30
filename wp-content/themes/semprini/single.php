<?php 
/**
 * Plantilla de Post Individual
 */
get_header(); 
?>
<main class="page-single">
    <div class="about-wrap">
        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <span class="meta"><?php the_time('F j, Y'); ?></span>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>