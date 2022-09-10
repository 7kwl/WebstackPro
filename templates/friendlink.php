<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

        <?php if( io_get_option('show_friendlink') && io_get_option('links')) : ?>
        <h4 class="text-gray text-lg mb-4">
            <i class="iconfont icon-book-mark-line icon-lg mr-2" id="friendlink"></i><?php _e('友情链接','i_theme') ?>
        </h4>
        <div class="friendlink text-xs card">
            <div class="card-body"> 
                <?php 
                $args = array(
                    'orderby'          => 'rating',
                    'order'            => 'DESC', 
                    'category'         =>  io_get_option('home_links')?implode(',',io_get_option('home_links')):'', 
                    'categorize'       => 0,
                    'title_li'         => '', 
                    'before'           => '',
                    'after'            => '',
                    'show_images'      => 0
                );
                wp_list_bookmarks( $args ); ?>
                <a href="<?php echo get_permalink(io_get_option('links_pages')) ?>" target="_blank" title="<?php _e('更多链接','i_swallow') ?>"><?php _e('更多链接','i_swallow') ?></a>
            </div> 
        </div> 
        <?php endif; ?> 
        