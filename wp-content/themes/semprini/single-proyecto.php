<?php get_header(); ?>
<main class="single-project">
  <div class="wrap">
    <?php if (have_posts()): while(have_posts()): the_post();
      $live = get_field('proj_live_url'); $repo = get_field('proj_repo_url'); $case = get_field('proj_case_url');
      $vid  = get_field('proj_video_oembed'); $file = get_field('proj_video_file'); $poster = get_field('proj_video_poster');
      $poster_url = (is_array($poster) && !empty($poster['url'])) ? $poster['url'] : '';
    ?>
      <header class="sp-head">
        <h1><?php the_title(); ?></h1>
        <?php if ($sub = get_field('proj_subtitle')): ?><p class="sp-sub"><?php echo esc_html($sub); ?></p><?php endif; ?>
        <div class="sp-actions">
          <?php if ($live): ?><a class="btn-3d btn-3d--pill" target="_blank" rel="noopener" href="<?php echo esc_url($live); ?>">Demo</a><?php endif; ?>
          <?php if ($repo): ?><a class="btn-3d btn-3d--pill" target="_blank" rel="noopener" href="<?php echo esc_url($repo); ?>">Repo</a><?php endif; ?>
          <?php if ($case): ?><a class="btn-3d btn-3d--pill" target="_blank" rel="noopener" href="<?php echo esc_url($case); ?>">Case</a><?php endif; ?>
        </div>
      </header>

      <section class="sp-media">
        <?php if (!empty($vid)): ?>
          <div class="sp-embed"><?php echo $vid; ?></div>
        <?php elseif (is_array($file) && !empty($file['url'])): ?>
          <video class="sp-video" controls playsinline preload="metadata" <?php if($poster_url) echo 'poster="'.esc_url($poster_url).'"'; ?>>
            <source src="<?php echo esc_url($file['url']); ?>" type="video/mp4">
          </video>
        <?php elseif (has_post_thumbnail()): the_post_thumbnail('large'); ?>
        <?php endif; ?>
      </section>

      <article class="sp-content">
        <?php the_content(); ?>
        <?php
        $tech = trim((string) get_field('proj_tech'));
        if ($tech) {
          echo '<ul class="sp-tech">';
          foreach (preg_split('/\r\n|\r|\n/',$tech) as $t) {
            $t = trim($t); if(!$t) continue;
            echo '<li>'.esc_html($t).'</li>';
          }
          echo '</ul>';
        }
        ?>
      </article>
    <?php endwhile; endif; ?>
  </div>
</main>
<?php get_footer(); ?>
