<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
   
    		<div class="card-post list-item">
                <div class="media media-4x3 p-0 rounded">
                    <?php if(io_get_option('lazyload')): ?>
                    <a class="media-content" href="<?php the_permalink(); ?>" <?php echo new_window() ?> data-src="<?php echo  io_theme_get_thumb() ?>"></a>
                    <?php else: ?>
                    <a class="media-content" href="<?php the_permalink(); ?>" <?php echo new_window() ?>  style="background-image: url(<?php echo  io_theme_get_thumb() ?>);"></a>
                    <?php endif ?>
                </div>
                <div class="list-content">
                    <div class="list-body">
                        <a href="<?php the_permalink(); ?>" target="_blank" class="list-title text-md overflowClip_2">
                        <?php show_sticky_tag( is_sticky() ) . show_new_tag(get_the_time('Y-m-d H:i:s')) ?><?php the_title(); ?>
                        </a>
                    </div>
                    <div class="list-footer">
                        <div class="d-flex flex-fill align-items-center">
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" class="flex-avatar mr-1">
                            <?php echo get_avatar( get_the_author_meta('email'), '20' ); ?>               
                            </a>
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" class="d-none d-md-inline-block text-xs" target="_blank"><?php echo get_the_author() ?></a>
                            <div class="flex-fill"></div>
                            <div class="text-muted text-xs">
                                <?php 
    				            if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views mr-1"><i class="iconfont icon-chakan mr-1"></i>','</span>' ); }
                                ?>
                                <a href="<?php the_permalink(); ?>"><i class="iconfont icon-heart mr-1"></i><?php echo get_like(get_the_ID()) ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>								
      
