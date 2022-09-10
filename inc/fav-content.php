<?php  if ( ! defined( 'ABSPATH' ) ) { exit; }
function fav_con($mid,$pname = "") { 
    $taxonomy = $mid->taxonomy;
    $quantity = io_get_option('card_n');
    if($taxonomy == "favorites") {
        $icon = 'icon-tag';
    } elseif($taxonomy == "apps") {
        $icon = 'icon-app';
    } elseif($taxonomy == "books") {
        $icon = 'icon-book';
    } elseif($taxonomy == "category") {
        $icon = 'icon-publish';
    } else{
        $icon = 'icon-tag';
    }
    ?>
        <div class="d-flex flex-fill ">
            <h4 class="text-gray text-lg mb-4">
                <i class="site-tag iconfont <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $mid->term_id; ?>"></i>
                <?php if( $pname != "" && io_get_option("tab_p_n")&& !wp_is_mobile() ){ 
                     echo $pname . '<span style="color:#f1404b"> · </span>';
                } 
                echo $mid->name; ?>
            </h4>
            <div class="flex-fill"></div>
            <?php 
            $site_n = isset($quantity[$taxonomy])?$quantity[$taxonomy]:$quantity['apps'];
            $category_count   = $mid->category_count;
            $count            = $site_n;
            if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
            if($site_n >= 0 && $count < $category_count){
                $link = esc_url( get_term_link( $mid, $taxonomy ) );
                echo "<a class='btn-move text-xs' href='$link'>more+</a>";
            } 
            ?>
        </div>
        <div class="row <?php echo io_get_option('site_card_mode') == 'min'?"row-sm":"" ?>">
        <?php show_card($site_n,$mid->term_id,$taxonomy); ?>
        </div>   
<?php }  
function fav_con_a($mid,$pname = "") { 
    $taxonomy = $mid['object'];
    $quantity = io_get_option('card_n');
    if($taxonomy == "favorites") {
        $icon = 'icon-tag';
    } elseif($taxonomy == "apps") {
        $icon = 'icon-app';
    } elseif($taxonomy == "books") {
        $icon = 'icon-book';
    } elseif($taxonomy == "category") {
        $icon = 'icon-publish';
    } else {
        $icon = 'icon-tag';
    }
    ?>
        <div class="d-flex flex-fill ">
            <h4 class="text-gray text-lg mb-4">
                <i class="site-tag iconfont <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $mid['object_id']; ?>"></i>
                <?php if( $pname != "" && io_get_option("tab_p_n")&& !wp_is_mobile() ){ 
                     echo $pname . '<span style="color:#f1404b"> · </span>';
                } 
                echo $mid['title']; ?>
            </h4>
            <div class="flex-fill"></div>
            <?php 
            $site_n = isset($quantity[$taxonomy])?$quantity[$taxonomy]:$quantity['apps'];
            $category_count   = io_get_category_count($mid['object_id']);//10;//$mid->category_count;
            $count            = $site_n;
            if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
            if($site_n >= 0 && $count < $category_count){
                $link = $mid['url'];//esc_url( get_term_link( $mid, $taxonomy ) );
                echo "<a class='btn-move text-xs' href='$link'>more+</a>";
            } 
            ?>
        </div>
        <div class="row <?php echo io_get_option('site_card_mode') == 'min'?"row-sm":"" ?>">
        
        <?php show_card($site_n,$mid['object_id'],$taxonomy); ?>
        </div>   
<?php } 
function fav_con_tab_ajax($category,$parent_term) { 
    $_mid = '';  
    $quantity = io_get_option('card_n');
    if($parent_term['object'] == "favorites") { 
        $icon = 'icon-tag'; 
    } elseif($parent_term['object'] == "apps") { 
        $icon = 'icon-app'; 
    } elseif($parent_term['object'] == "books") { 
        $icon = 'icon-book'; 
    } elseif($parent_term['object'] == "category") {
        $icon = 'icon-publish';
    } else { 
        $icon = 'icon-tag';
    }
    ?>
        <?php if(io_get_option("tab_p_n") ){ ?>
        <h4 class="text-gray text-lg">
            <i class="site-tag iconfont <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $parent_term['object_id'] ?>"></i>
            <?php echo $parent_term['title']; ?>
        </h4>
        <?php } ?>
        <!-- tab模式菜单 -->
        <div class="d-flex flex-fill flex-tab">
            <div class="overflow-x-auto">
            <div class='slider_menu mini_tab ajax-list-home' sliderTab="sliderTab" data-id="<?php echo  $parent_term['object_id'] ?>">
                <ul class="nav nav-pills menu" role="tablist"> 
                    <?php $i_menu = 0; foreach($category as $mid) { 
                    if($i_menu == 0) $_mid = $mid;
                    $taxonomy = $mid['object'];
                    echo'<li class="pagenumber nav-item"><a id="term-'. $mid['object_id'] .'" class="nav-link '. ($i_menu==0?'active':'') .'" data-action="load_home_tab" data-taxonomy="'. $taxonomy .'" data-id="'. $mid['object_id'] .'" >'. $mid['title'] .'</a></li>';
                    $i_menu++; } ?>
                </ul>
            </div>
            </div> 
            <div class="flex-fill"></div>
            <?php 
            $site_n = isset($quantity[$_mid['object']])?$quantity[$_mid['object']]:$quantity['apps'];
            $category_count   = io_get_category_count($_mid['object_id']);//10;//$_mid->category_count;
            $count            = $site_n;
            if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
            if($site_n >= 0 && $count < $category_count){
                $link = $_mid['url'];//esc_url( get_term_link( $_mid, $taxonomy ) );
                echo "<a class='btn-move tab-move text-xs ml-2' href='$link' style='line-height:34px'>more+</a>";
            }
            elseif($site_n >= 0) {
                echo "<a class='btn-move tab-move text-xs ml-2' href='#' style='line-height:34px;display:none'>more+</a>";
            }
            ?>
        </div>
        <!-- tab模式菜单 end -->
        <div class="row ajax-<?php echo $parent_term['object_id'] ?> <?php echo io_get_option('site_card_mode') == 'min'?"row-sm":"" ?> mt-4" style="position: relative;">
        <?php show_card($site_n,$_mid['object_id'],$_mid['object']); ?>
        </div>
<?php } 

function fav_con_tab($category,$parent_term) { 
    $_link = '';  
    $quantity = io_get_option('card_n');
    if($parent_term['object'] == "favorites") { 
        $icon = 'icon-tag'; 
    } elseif($parent_term['object'] == "apps") { 
        $icon = 'icon-app'; 
    } elseif($parent_term['object'] == "books") { 
        $icon = 'icon-book'; 
    } elseif($parent_term['object'] == "category") {
        $icon = 'icon-publish';
    } else { 
        $icon = 'icon-tag';
    }
    ?>
        <?php if(io_get_option("tab_p_n") ){ ?>
        <h4 class="text-gray text-lg">
            <i class="site-tag iconfont <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $parent_term['object_id'] ?>"></i>
            <?php echo $parent_term['title']; ?>
        </h4>
        <?php } ?>
        <!-- tab模式菜单 -->
        <div class="d-flex flex-fill flex-tab">
            <div class="overflow-x-auto">
            <div class='slider_menu mini_tab ajax-list-home' sliderTab="sliderTab" data-id="<?php echo  $parent_term['object_id'] ?>">
                <ul class="nav nav-pills menu" role="tablist"> 
                    <?php $i_menu = 0; foreach($category as $mid) {
                    $taxonomy = $mid['object']; 
                    $site_n = isset($quantity[$mid['object']])?$quantity[$mid['object']]:$quantity['apps'];
                    $category_count   = io_get_category_count($mid['object_id']);
                    $count            = $site_n;
                    if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
                    $link = '';
                    if($site_n >= 0 && $count < $category_count){
                        $link = $mid['url'];
                    }
                    if($i_menu == 0) $_link = $link;
                    echo '<li class="pagenumber nav-item"><a id="term-'. $mid['object_id'] .'" class="nav-link tab-noajax '. ($i_menu==0?'active':'') .'" data-toggle="pill" href="#tab-'. $mid['object_id'].'" data-link="'.$link.'">'. $mid['title'].'</a></li>';
                    $i_menu++; } ?>
                </ul>
            </div>
            </div> 
            <div class="flex-fill"></div>
            <?php  
            if($_link != ''){
                echo "<a class='btn-move tab-move text-xs ml-2' href='$_link' style='line-height:34px'>more+</a>";
            } else {
                echo "<a class='btn-move tab-move text-xs ml-2' href='#' style='line-height:34px;display:none'>more+</a>";
            }
            ?>
        </div>
        <!-- tab模式菜单 end -->
        <div class="tab-content mt-4">
            <?php  for($i = 0; $i<count($category); $i++) { ?>
            <div id="tab-<?php echo $category[$i]['object_id']; ?>" class="tab-pane  <?php echo $i==0?'active':'' ?>">  
                <div class="row <?php echo io_get_option('site_card_mode') == 'min'?"row-sm":"" ?> mt-4" style="position: relative;">
                <?php show_card($site_n,$category[$i]['object_id'],$category[$i]['object']); ?>
                </div>
            </div>
            <?php } ?>
        </div> 
<?php } 


if(!function_exists('show_card')){
/**
 * 显示
 * @param  String $site_n 需显示的数量
 * @param  String $terms 分类id
 * @param  String $taxonomy 分类名
 * @param  String $ajax  
 */
function show_card($site_n,$terms,$taxonomy,$ajax=''){
    if ( !in_array( $taxonomy,array('favorites','apps','category',"books") ) ){
        echo "<div class='card py-3 px-4'><p style='color:#f00'>不是分类，请到菜单重新添加</p></div>";
        return;
    }
    $exclude = '';
    if( io_get_option('show_sticky') && get_option('sticky_posts') ){
        $exclude = get_option( 'sticky_posts' );
        $myposts = new WP_Query(array( 
            "post__in"  => get_option("sticky_posts"), 
            'showposts' => $site_n,
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,       
                    'field'    => 'id',            
                    'terms'    => $terms,    
                )
            ),
        ));
        if($site_n > 0){
            $site_n = $site_n - $myposts->found_posts ;
            if($site_n < 1)
                $site_n = -5;
        } 
        if($myposts->found_posts>0)
            show_post($myposts,$taxonomy,$ajax);
        wp_reset_query(); 
        if($site_n == -5)
            return;
    }
    if(io_get_option('home_sort')[$taxonomy] == 'views'){
        $args = array(      
            'meta_key' => 'views',
            'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
        );
    } elseif(io_get_option('home_sort')[$taxonomy] == 'sites_order') { 
        $args = array(      
            'meta_key' => '_sites_order',
            'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
        );
    } elseif(io_get_option('home_sort')[$taxonomy] == 'down_count') { 
        $args = array(      
            'meta_key' => '_down_count',
            'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
        );
    } else {
        $args = array(      
            'orderby' => io_get_option('home_sort')[$taxonomy],
            'order'   => 'DESC',
        );
    } 
    $args2 = array(   
        //'post_type'           => to_post_type($taxonomy),
        'ignore_sticky_posts' => 1,              
        'posts_per_page'      => $site_n,    
        'post__not_in'        => $exclude,
		'post_status'         => array( 'publish', 'private' ),//'publish',
		'perm'                => 'readable',
        'tax_query'           => array(
            array(
                'taxonomy' => $taxonomy,       
                'field'    => 'id',            
                'terms'    => $terms,    
            )
        ),
    );
    $myposts = new WP_Query( array_merge($args,$args2) );
    show_post($myposts,$taxonomy,$ajax);
    wp_reset_query();
}
}
if(!function_exists('show_post')){
function show_post($myposts,$taxonomy,$ajax){
    global $post;  
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有内容','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
    
    if($taxonomy == "favorites"){
        //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)){
            $link_url = get_post_meta($post->ID, '_sites_link', true); 
            $default_ico = get_theme_file_uri('/images/favicon.png');
            if(io_get_option('site_card_mode') == 'max'){ ?>
                <div class="url-card <?php get_columns() ?> <?php echo before_class($post->ID) ?> <?php echo $ajax ?>">
                <?php include( get_theme_file_path('/templates/card-sitemax.php') ); ?>
                </div>
            <?php }elseif(io_get_option('site_card_mode') == 'min'){ ?>
                <div class="url-card col-6 <?php get_columns() ?> <?php echo before_class($post->ID) ?> <?php echo $ajax ?>">
                <?php include( get_theme_file_path('/templates/card-sitemini.php') ); ?>
                </div>
            <?php }else{ ?>
                <div class="url-card <?php echo io_get_option('two_columns')?"col-6":"" ?> <?php get_columns() ?> <?php echo before_class($post->ID) ?> <?php echo $ajax ?>">
                <?php include( get_theme_file_path('/templates/card-site.php') ); ?>
                </div>
            <?php }
        //}
    } elseif($taxonomy == "apps") {
        if(io_get_option('app_card_mode') == 'card'){
            echo'<div class="col-12 col-md-6 col-lg-4 col-xxl-5a '.$ajax.'">';
            include( get_theme_file_path('/templates/card-appcard.php') ); 
            echo'</div>';
        }else{
            echo'<div class="col-4 col-md-3 col-lg-2 col-xl-8a col-xxl-10a pb-1 '.$ajax.'">';
            include( get_theme_file_path('/templates/card-app.php') ); 
            echo'</div>';
        }
    } elseif($taxonomy == "books") { 
            echo'<div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xxl-8a '.$ajax.'">';
            include( get_theme_file_path('/templates/card-book.php') ); 
            echo'</div>'; 
    } elseif($taxonomy == "category") {
        if(io_get_option('post_card_mode')=="card"){
            echo '<div class="col-12 col-sm-6 col-lg-4 col-xxl-3 '.$ajax.'">';
            get_template_part( 'templates/card','postmin' );
            echo '</div>';
        }elseif(io_get_option('post_card_mode')=="default"){
            echo '<div class="col-6 col-md-4 col-xl-3 col-xxl-6a py-2 py-md-3 '.$ajax.'">';
            get_template_part( 'templates/card','post' );
            echo '</div>';
        } 
    }

    endwhile; endif;
}
}

function to_post_type($taxonomy){
    if( $taxonomy=="favorites"||$taxonomy=="sitetag" )
        return 'sites';
    if( $taxonomy=="apps"||$taxonomy=="apptag" )
        return 'app';
    if( $taxonomy=="books"||$taxonomy=="booktag" )
        return 'book';
}
function to_post_tag($post){
    if( $post=="sites" )
        return 'sitetag';
    if( $post=="app" )
        return 'apptag';
    if( $post=="book" )
        return 'booktag';
    return 'post_tag';
}
