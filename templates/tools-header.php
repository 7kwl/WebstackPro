<?php if ( ! defined( 'ABSPATH' ) ) { exit; } 
$search_big = io_get_option('search_skin') ? io_get_option('search_skin') : false;
get_template_part( 'templates/header','banner' );
// content 内容
if(is_category() || is_tag()){
    $content = '<div id="content" class="container is_category">';
} elseif(is_tax("sitetag") || is_tax("favorites")) {
    if(get_term_children(get_queried_object_id(), 'favorites')){
        $content = '<div id="content" class="content-site customize-site">';
    } else {
        $content = '<div id="content" class="container container-lg customize-site">';
    }
} elseif (is_tax("apptag") || is_tax("apps") || is_tax("books") || is_tax("booktag") || is_tax("series")) {
    $content = '<div id="content" class="container container-lg">';
} else {
    $content = '<div id="content" class="content-site customize-site">';
}

if( in_array("home",io_get_option('search_position')) && $search_big && $search_big['search_big'] ){
    // padding-bottom
    $padding = '';
    //if(!is_home() || !is_front_page())
    //    $padding = 'no-padding-bottom ';
    if( (is_home() || is_front_page()) && io_get_option('article_module') && io_get_option('search_skin')['post_top'] )
        $padding .= 'post-top ';
        
    // gradual
    $gradual = '';
    if($search_big['big_skin']!="no-bg" && $search_big['bg_gradual'])
        $gradual = 'bg-gradual ';

    $style='';
    if($search_big['big_skin']=="css-color"){
        $style = 'style="background-image: linear-gradient(45deg, '.$search_big['search_color']['color-1'].' 0%, '.$search_big['search_color']['color-2'].' 50%, '.$search_big['search_color']['color-3'].' 100%);"';
    }
    if($search_big['big_skin']=="css-img"){
        $style = 'style="background-image: url('.$search_big['search_img']['url'].')"';
    }
    if($search_big['big_skin']=="css-bing")
    {
        $result = file_get_contents('https://cn.bing.com/HPImageArchive.aspx?format=js&idx='.rand(-1,7).'&n=1&mkt=zh-CN');
        $data =  json_decode($result);  
        $imgurl = "https://cn.bing.com".$data->{"images"}[0]->{"url"};
        $style = 'style="background-image: url('.$imgurl.')"';
    }
    

    echo '<div class="header-big  '. $padding . $gradual . $search_big['big_skin'] .' mb-4" '. $style .'>';

    if($search_big['big_skin']=="canvas-fx") echo '<iframe class="canvas-bg" scrolling="no" sandbox="allow-scripts allow-same-origin" src="'.get_theme_file_uri('/fx/io-fx0'.($search_big['canvas_id']==0?rand(1,7):$search_big['canvas_id']).'.html').'"></iframe>';
    // 加载搜索模块 
    if(io_get_option('search_position') && in_array("home",io_get_option('search_position')) ){
        get_template_part( 'templates/search/big' );
    } else {
        echo '<div class="no-search my-2 p-1"></div>';
    }
    // 加载公告模块
    if(is_home() || is_front_page()){
        echo '<div class="bulletin-big mx-3 mx-md-0">';
        get_template_part( 'templates/bulletin' );  
        echo '</div>';
    }

    
    echo '</div>'.$content;

} else { 

    echo $content;

    // 加载公告模块
    if(is_home() || is_front_page())
        get_template_part( 'templates/bulletin' );  

    // 加载搜索模块 
    if(io_get_option('search_position') && in_array("home",io_get_option('search_position')) ){
        get_template_part( 'templates/search/default' );
    } else {
        echo '<div class="no-search my-2 p-1"></div>';
    }

    // 加载广告模块
    get_template_part( 'templates/ads','hometop' );
}
