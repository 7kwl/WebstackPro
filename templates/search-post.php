<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="cat_list">
    <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post();?>


    <div class="list-grid list-grid-padding">
        <div class="list-item card">
            <div class="media media-3x2 rounded col-4 col-md-4">
                <?php if(io_get_option('lazyload')): ?>
                <a class="media-content" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" data-src="<?php echo io_theme_get_thumb() ?>"></a>
                <?php else: ?>
                <a class="media-content" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" style="background-image: url(<?php echo io_theme_get_thumb() ?>);"></a>
                <?php endif ?>
            </div>
            <div class="list-content">
                <div class="list-body">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="list-title text-lg overflowClip_2"><?php the_title(); ?></a>
                    <div class="list-desc d-none d-md-block text-sm text-secondary my-3">
                        <div class="overflowClip_2 "><?php echo preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",get_the_excerpt($post->ID)) ?></div>
                    </div>
                </div>
                <div class="list-footer">
                    <div class="d-flex flex-fill align-items-center text-muted text-xs">
                        <?php
                        $category = get_the_category();
                        if($category[0]){   ?>
                        <span><i class="iconfont icon-classification"></i>
                            <a href="<?php echo get_category_link($category[0]->term_id ) ?>"><?php echo $category[0]->cat_name ?></a>
                        </span>
                        <?php } ?>
                     
                        <div class="flex-fill"></div>
                        <div>
                            <time class="mx-1"><?php echo timeago( get_the_time('Y-m-d G:i:s') ) ?></time>
                        </div>
                    </div>        
                </div>
            </div>
        </div>							
    </div> 
    <?php endwhile; endif;?>
</div>
