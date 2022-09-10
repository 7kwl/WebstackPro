<?php  if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
 * 添加网址小卡片
 * [site_card ids=1][/site_card]
 * [site_card ids=1,2,3][/site_card]
 */
add_shortcode("site_card", "add_site_card");
function add_site_card( $atts, $content = null ){
    if(is_home() || is_front_page()){
        return '';
    }
    extract( shortcode_atts( array(
    'ids' => ''
    ),
    $atts ) );
    global $post;
    $content = '';
    $postids = explode(',', $ids);
    $inset_posts = get_posts(array(
        'post__in'       => $postids,
        'post_type'      => 'sites',
        'orderby'        => 'post__in',  
        'posts_per_page' => -1
    ));
    $intx=0;
    foreach ($inset_posts as $key => $post) {
        setup_postdata( $post );
        $category = get_the_category();
        $link_url = get_post_meta($post->ID, '_sites_link', true); 
        $default_ico = get_theme_file_uri('/images/favicon.png');
        //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)){
            $content .='<div class="url-card shortcode-url site_' . $intx . ' mx-auto '.get_columns(false).' '. before_class($post->ID).'" style="max-width:320px">';
            ob_start();
            include( get_theme_file_path('/templates/card-site.php') ); 
            $content .= ob_get_contents();
            ob_end_clean();
            $content .='</div>';
        //} 
        $intx++;
    }
    wp_reset_postdata(); 
    return $content;
}

/**
 * 添加文章小卡片
 * [post_card ids=1][/post_card]
 * [post_card ids=1,2,3][/post_card]
 */
add_shortcode("post_card", "add_post_card");
function add_post_card( $atts, $content = null ){
    if(is_home() || is_front_page()){
        return '';
    }
    extract( shortcode_atts( array(
    'ids' => ''
    ),
    $atts ) );
    global $post;
    $content = '';
    $postids = explode(',', $ids);
    $inset_posts = get_posts(array(
        'post__in'       => $postids,
        'post_type'      => 'post',
        'orderby'        => 'post__in', 
        'posts_per_page' => -1
    ));
    $intx=0;
    foreach ($inset_posts as $key => $post) {
        setup_postdata( $post );
         
            $content .='<div class="url-card shortcode-url site_' . $intx . ' mx-auto '. before_class($post->ID).'" style="max-width:420px">';
            ob_start();
            include( get_theme_file_path('/templates/card-postmin.php') ); 
            $content .= ob_get_contents();
            ob_end_clean();
            $content .='</div>';
         
        $intx++;
    }
    wp_reset_postdata(); 
    return $content;
}

/**
 * 添加文章小卡片
 * [app_card ids=1][/app_card]
 * [app_card ids=1,2,3][/app_card]
 */
add_shortcode("app_card", "add_app_card");
function add_app_card( $atts, $content = null ){
    if(is_home() || is_front_page()){
        return '';
    }
    extract( shortcode_atts( array(
    'ids' => ''
    ),
    $atts ) );
    global $post;
    $content = '';
    $postids = explode(',', $ids);
    $inset_posts = get_posts(array(
        'post__in'       => $postids,
        'post_type'      => 'app',
        'orderby'        => 'post__in', 
        'posts_per_page' => -1
    ));
    $intx=0;
    foreach ($inset_posts as $key => $post) {
        setup_postdata( $post );
        $category = get_the_category();
        $link_url = get_post_meta($post->ID, '_sites_link', true); 
        $default_ico = get_theme_file_uri('/images/favicon.png');
        //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)){
            $content .='<div class="url-card shortcode-url site_' . $intx . ' mx-auto '. before_class($post->ID).'" style="max-width:320px">';
            ob_start();
            include( get_theme_file_path('/templates/card-appmin.php') ); 
            $content .= ob_get_contents();
            ob_end_clean();
            $content .='</div>';
        //} 
        $intx++;
    }
    wp_reset_postdata(); 
    return $content;
}
/**
 * 短代码广告
 * [ad]
 */
add_shortcode("ad", "post_ad");
function post_ad(){
    return '<div class="post-apd my-3">'.stripslashes( io_get_option('ad_po') ).'</div>';
}


