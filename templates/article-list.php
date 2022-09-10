<?php  if ( ! defined( 'ABSPATH' ) ) { exit; }  
if(io_get_option('article_module')) :
?>
<div class="slice-article mb-4" <?php echo (io_get_option('search_skin') && io_get_option('search_skin')['search_big'] && io_get_option('search_skin')['post_top'] && in_array("home",io_get_option('search_position')))?'style="margin-top:-6rem!important"':''?>> 
<div class="row no-gutters card-group">
    <div class="col-12 col-md-7 col-lg-8 col-xl-6"> 
        <?php  get_template_part( 'templates/slide','blog' ); ?>
    </div>
    <div class="col-12 col-xl-3 d-none d-xl-block pl-0 pl-md-1">
        <div class="my-n1">

        <?php
        if(io_get_option('two_article')){
            $args = array(
                'post__in' => explode(',', io_get_option('two_article')),
                'orderby'        => 'post__in', 
                'posts_per_page' => -1
            );
        }else{
            $args = array( 
                'numberposts' => 2, 
                'post__not_in' => get_option( 'sticky_posts' ),
                'orderby' => 'rand', 
                'post_status' => 'publish' 
            );
        }
            $rand_posts = get_posts( $args );
            foreach( $rand_posts as $post ) : ?>
            <div class="col-lg-12 p-1">
                <div class="list-item">
                    <div class="media article-list rounded">
                        <?php if(io_get_option('lazyload')): ?>
                        <a class="media-content media-title-bg" href="<?php the_permalink(); ?>" <?php echo new_window() ?> data-src="<?php echo  io_theme_get_thumb() ?>">
                        <?php else: ?>
                        <a class="media-content media-title-bg" href="<?php the_permalink(); ?>" <?php echo new_window() ?>  style="background-image: url(<?php echo  io_theme_get_thumb() ?>);">
                        <?php endif ?>
                            <span class="media-title d-none d-md-block overflowClip_1"><?php the_title(); ?></span>
                        </a>                                                       
                    </div>
                        
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>                       
        </div>
    </div>
    <div class="col-12 col-md-5 col-lg-4 col-xl-3 mt-4 mt-md-0 pl-0 pl-md-2 pl-xl-1">
        <div class="card new-news mb-0 hidden-xs">
            <h3 class="h6 news_title"><i class="iconfont icon-category"></i>&nbsp;&nbsp;<?php _e('最新资讯','i_theme') ?></h3>
            <a class="news_all_btn text-xs" href="<?php echo get_permalink(io_get_option('blog_pages')) ?>" <?php echo new_window() ?> title="<?php _e('最新资讯','i_theme') ?>"><?php _e('所有','i_theme') ?></a>
            <ul>
            <?php 
            $args = array(
                'category__not_in' => explode(',', io_get_option('article_not_in')),
                'ignore_sticky_posts' => 1,
            );
            query_posts( $args );
            
            if ( have_posts() ) : while (  have_posts() ) :  the_post();?> 
                <li>
                    <i class="iconfont icon-point"></i> 
                    <a class="text-sm" href="<?php the_permalink(); ?>" <?php echo new_window() ?>><span><?php the_title(); ?></span></a>
                    <div class="d-flex flex-fill text-xs text-muted">
                        <?php 
                        $category = get_the_category();
                        if($category[0]){?>
                        <a class="mr-2" href="<?php echo get_category_link($category[0]->term_id ) ?>" <?php echo new_window() ?>><?php echo $category[0]->cat_name ?></a>
                        <?php } ?>
                        <div class="flex-fill"></div>
                        <?php echo get_the_time('Y-m-d') ?>
                    </div>
                </li> 
            <?php endwhile; endif; wp_reset_query(); ?>
            </ul>
        </div>
    </div>
</div>
</div>
<?php endif; ?>
