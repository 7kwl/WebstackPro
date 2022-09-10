<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner rounded">
                <?php $query_post = array(
                    'posts_per_page' => io_get_option('article_n'),
                    'post__in'       => get_option('sticky_posts'),
                    'ignore_sticky_posts' => 1
                );
                query_posts($query_post);
                $count = 0;
                ?> 
                <?php while(have_posts()):the_post(); ?>
                    <div class="carousel-item home-item <?php echo( $count==0? "active":"" ) ?>">
                        <?php if(io_get_option('lazyload')): ?>
                        <a class="media-content media-title-bg" href="<?php the_permalink(); ?>" <?php echo new_window() ?> data-bg="url(<?php echo io_theme_get_thumb() ?>)">
                        <?php else: ?>
                        <a class="media-content media-title-bg" href="<?php the_permalink(); ?>" <?php echo new_window() ?>  style="background-image: url(<?php echo io_theme_get_thumb() ?>);">
                        <?php endif ?>
                            <span class="carousel-caption d-none d-md-block"><?php the_title(); ?></span>
                        </a>
                    </div>
                <?php $count++ ?>
                <?php endwhile; wp_reset_query(); ?>
            </div> 
            <ol class="carousel-indicators carousel-blog">
            <?php for ($i=0; $i<($count); $i++) { ?>
                <li data-target="#carousel" data-slide-to="<?php echo $i ?>" class="<?php echo( $i==0? "active":"" ) ?>"></li>
            <?php } ?>
            </ol>
        </div>
