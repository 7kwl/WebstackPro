<?php if ( ! defined( 'ABSPATH' ) ) { exit; }?>
<?php get_header();?>


<?php 
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill">
<?php 
    get_template_part( 'templates/tools','header' ); 
 
    // 加载文章模块
    get_template_part( 'templates/article','list' ); 

    // 加载热搜模块
    get_template_part( 'templates/tools','hotsearch' ); 

    // 加载文章模块
    get_template_part( 'templates/tools','post' ); 

    // 加载自定义模块
    get_template_part( 'templates/tools','customize' ); 

    // 加载热门模块
    get_template_part( 'templates/tools','hotcontent' ); 

    // 加载广告模块second
    get_template_part( 'templates/ads','homesecond' );

    // 加载网址模块
    foreach($categories as $category) {
        if($category['menu_item_parent'] == 0){
            if(empty($category['submenu'])){ 
                $terms = apply_filters( 'io_category_list', array('favorites','apps','category','books') );
                if($category['type'] != 'taxonomy') {
                    $url = trim($category['url']);
                    if( strlen($url)>1 && substr( $url, 0, 1 ) != '#') 
                        echo "<div class='card py-3 px-4'><p style='color:#f00'>“{$category['title']}”不是分类，请到菜单重新添加</p></div>";
                    continue;
                } elseif ( $category['type'] == 'taxonomy' && in_array( $category['object'],$terms ) ){
                    fav_con_a($category);
                } else {
                    echo "<div class='card py-3 px-4'><p style='color:#f00'>“{$category['title']}”不是分类，请到菜单重新添加</p></div>";
                }
            }else{
                if(io_get_option("tab_type")) {
                    if(io_get_option("tab_ajax"))
                        fav_con_tab_ajax($category['submenu'],$category);
                    else
                        fav_con_tab($category['submenu'],$category);
                }else{
                    foreach($category['submenu'] as $mid) {
                        fav_con_a($mid,$category['title']);
                    }
                }
            }
        }
    } 
      
    // 加载广告模块link
    get_template_part( 'templates/ads','homelink' );
    // 加载友链模块
    get_template_part( 'templates/friendlink' ); ?>   
    </div> 
<?php
get_footer();
