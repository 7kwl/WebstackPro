<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
<?php 
if(io_get_option('post_card_list')){
    $categorys = explode(',', io_get_option('post_card_list'));
    foreach ($categorys as $cat_id){
        $args = array(
            'category__and' => $cat_id,
            'ignore_sticky_posts' => 1,
        );
        $posts = new WP_Query( $args );
        if ( $posts->have_posts() ) :
    ?>
    <div class="d-flex flex-fill ">
        <h4 class="text-gray text-lg">
            <i class="site-tag iconfont icon-publish icon-lg mr-1" ></i><?php echo get_cat_name( $cat_id ) ?></h4>
        <div class="flex-fill"></div>
        <a class='btn-move text-xs' href='<?php echo get_category_link( $cat_id );?>'>more+</a>
    </div>
    <div class="list-post row"> 
        <?php 
        while (  $posts->have_posts() ) :  $posts->the_post();
            if(io_get_option('post_card_mode')=="card"){
                echo '<div class="col-12 col-md-6 col-lg-4 col-xxl-3">';
                get_template_part( 'templates/card','postmin' );
                echo '</div>';
            }elseif(io_get_option('post_card_mode')=="default"){
                echo '<div class="col-6 col-md-4 col-xl-3 col-xxl-6a py-2 py-md-3">';
                get_template_part( 'templates/card','post' );
                echo '</div>';
            } 
        endwhile; 
        ?>
    </div>
    <?php endif; wp_reset_postdata(); ?>
<?php }
} ?>