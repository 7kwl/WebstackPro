<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
# 注册侧边栏
# --------------------------------------------------------------------
if (function_exists('register_sidebar')){

	register_sidebar( array(
		'name'          => __('博客布局侧边栏','io_setting' ),
		'id'            => 'sidebar-h',
		'description'   => __('显示在首页博客布局侧边栏','io_setting' ),
		'before_widget' => '<div id="%1$s" class="card %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="card-header widget-header"><span>',
		'after_title'   => '</span></div>',
	) );
	register_sidebar( array(
		'name'          => __('正文侧边栏','io_setting' ),
		'id'            => 'sidebar-s',
		'description'   => __('显示在文章正文及页面侧边栏','io_setting' ),
		'before_widget' => '<div id="%1$s" class="card %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="card-header widget-header"><span>',
		'after_title'   => '</span></div>',
	) );

	register_sidebar( array(
		'name'          => __('分类归档侧边栏','io_setting' ),
		'id'            => 'sidebar-a',
		'description'   => __('显示在文章归档页、搜索、404页侧边栏 ','io_setting' ),
		'before_widget' => '<div id="%1$s" class="card %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="card-header widget-header"><span>',
		'after_title'   => '</span></div>',
	) );
	register_sidebar( array(
		'name'          => __('公告归档页侧边栏','io_setting' ),
		'id'            => 'sidebar-bull',
		'description'   => __('显示在公告归档页侧边栏','io_setting' ),
		'before_widget' => '<div id="%1$s" class="card %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="card-header widget-header"><span>',
		'after_title'   => '</span></div>',
	) );

}
# 注册菜单
# --------------------------------------------------------------------
register_nav_menus( array(
	'nav_menu'    => __( '侧栏主菜单' , 'io_setting' ),
	'nav_main'    => __( '侧栏底部菜单' , 'io_setting' ),
    'main_menu'   => __( '顶部菜单' , 'io_setting' ),
    'search_menu' => __( '搜索推荐' , 'io_setting' ),
));
# 添加菜单
# --------------------------------------------------------------------
function wp_menu($location){
    if ( function_exists( 'wp_nav_menu' ) && has_nav_menu($location) ) {
        wp_nav_menu( array( 'container' => false, 'items_wrap' => '%3$s', 'theme_location' => $location ) );
    } else {
        if($location == 'search_menu')
            echo '<li><a href="'.get_option('siteurl').'/wp-admin/nav-menus.php">'.__('请到[后台->外观->菜单]中添加“搜索推荐”菜单。','i_theme').'</a></li>';
        else
            echo '<li><a href="'.get_option('siteurl').'/wp-admin/nav-menus.php">'.__('请到[后台->外观->菜单]中设置菜单。','i_theme').'</a></li>';
    }
}
# 激活友情链接模块
# --------------------------------------------------------------------
if(io_get_option('show_friendlink'))add_filter( 'pre_option_link_manager_enabled', '__return_true' );
# 引用功能
# --------------------------------------------------------------------
require_once get_theme_file_path('/inc/framework/framework.php');
require_once get_theme_file_path('/inc/theme-settings.php');
require_once get_theme_file_path('/inc/wp-optimization.php');
require_once get_theme_file_path('/inc/register.php');
require_once get_theme_file_path('/inc/post-type.php');
require_once get_theme_file_path('/inc/fav-content.php');
require_once get_theme_file_path('/inc/ajax.php');
require_once get_theme_file_path('/inc/clipimage.php');
require_once get_theme_file_path('/inc/widgets.php');
require_once get_theme_file_path('/inc/shortcode.php');
require_once get_theme_file_path('/inc/custom-css.php');
require_once get_theme_file_path('/inc/meta-boxs.php');
require_once get_theme_file_path('/inc/meta-taxonomy.php');
require_once get_theme_file_path('/inc/meta-shortcoder.php');
require_once get_theme_file_path('/inc/hot-search.php');
require_once get_theme_file_path('/inc/email-notify.php');
require_once get_theme_file_path('/inc/baidu-submit.php');
if(io_get_option('save_image')) require_once get_theme_file_path('/inc/save-image.php');
if(io_get_option('post_views')) require_once get_theme_file_path('/inc/postviews/postviews.php');
# 获取CSF框架设置（兼容1.0）
# --------------------------------------------------------------------
function io_get_option($option, $default = null){ 
    $options = get_option('io_get_option');
    return ( isset( $options[$option] ) ) ? $options[$option] : $default;
}
# 获取CSF框架图片
# --------------------------------------------------------------------
function get_post_meta_img($post_id, $key, $single){
    $metas = get_post_meta($post_id, $key, $single);
    if(is_array($metas)){
        return $metas['url'];
    } else {
        return $metas;
    }
}
# 网站块类型（兼容1.0）
# --------------------------------------------------------------------
function before_class($post_id){
    $metas      = get_post_meta_img($post_id, '_wechat_qr', true);
    $sites_type = get_post_meta($post_id, '_sites_type', true);
    if($metas != '' || $sites_type == "wechat"){
        return 'wechat';
    } elseif($sites_type == "down") {
        return 'down';
    } else {
        return '';
    }
}
/**
 * 显示置顶标签
 * ******************************************************************************************************
 */
function show_sticky_tag($isSticky){
    $span = '';
    if($isSticky && io_get_option('sticky_tag')['switcher'])
        $span = '<span class="badge badge-danger text-ss mr-1" title="'.__('置顶','i_theme').'">'.io_get_option('sticky_tag')['name'].'</span>';
    echo $span;
}
/**
 * 显示 NEW 标签
 * ******************************************************************************************************
 */
function show_new_tag($post_date){
    $span = '';
    if(io_get_option('new_tag')['switcher']){
	    $t2=date("Y-m-d H:i:s");
	    $t3=io_get_option('new_tag')['date']*24;
	    $diff=(strtotime($t2)-strtotime($post_date))/3600;
        if($diff<$t3){ 
            $span = '<span class="badge badge-danger text-ss mr-1" title="'.__('新','i_theme').'">'.io_get_option('new_tag')['name'].'</span>'; 
        }
    }
    echo $span;
}
/**
 * 编辑器增强
 * ******************************************************************************************************
 */
function enable_more_buttons($buttons) { 
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect'; 
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page'; 
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter( "mce_buttons_2", "enable_more_buttons" );
/**
 * 主题切换
 * ******************************************************************************************************
 */
function theme_mode(){
    $default_c = io_get_option('theme_mode');
    if($default_c == 'io-black-mode')
        $default_c = '';
    if ($_COOKIE['night_mode'] != '') {
        return(trim($_COOKIE['night_mode']) == '0' ? 'io-black-mode' : $default_c); 
    } elseif (io_get_option('theme_mode')) {
        return io_get_option('theme_mode');
    } else { 
        return(trim($_COOKIE['night_mode']) == '0' ? 'io-black-mode' : $default_c); 
    }
}
/**
 * 获取自定义菜单列表
 * ******************************************************************************************************
 */
function get_menu_list( $theme_location ) {
    
    $io_menu_list = array();
    if (has_nav_menu($theme_location) && ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) {
        $menu = get_term( $locations[$theme_location], 'nav_menu' );
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        foreach( $menu_items as $menu_item ) {
            if( $menu_item->menu_item_parent == 0 ) {
                $parent = $menu_item->ID;
                $my_parent = array();
                foreach($menu_item as $k=>$v)
                    $my_parent[$k] = $v ;
                $menu_array = array();
                $bool = false;
                foreach( $menu_items as $submenu ) {
                    if( $submenu->menu_item_parent == $parent ) {
                        $bool = true;
                        $my_submenu = array();
                        foreach($submenu as $k=>$v)
                            $my_submenu[$k] = $v ;
                        $menu_array[] = $my_submenu;
                    }
                }
                if( $bool == true && count( $menu_array ) > 0 ) {
                    $my_parent['submenu'] = $menu_array;
                } else { 
                    $my_parent['submenu'] = array();
                }
                $io_menu_list[] = $my_parent;
            } 
        }
    }  
    return $io_menu_list;
}
/**
 * 新窗口访问
 * ******************************************************************************************************
 */
function new_window(){
    if(io_get_option('new_window'))
        return 'target="_blank"';
    else
        return '';
}
/**
 * 网址块添加 nofollow
 * ******************************************************************************************************
 */
function nofollow($url, $details = false){
    if($details)
        return '';
    else{
        if(io_get_option('is_nofollow'))
            if(is_go_exclude($url))
                return '';
            else
                return 'rel="nofollow"';
        else
            return '';
    }
}
/**
 * 网址块 go 跳转
 * ******************************************************************************************************
 */
function go_to($url){
    if(io_get_option('is_go')){
        if(is_go_exclude($url))
            return $url;
        else
            return home_url().'/go/?url='.urlencode(base64_encode($url)) ;
    }
    else
        return $url;
}
/**
 * 添加go跳转，排除白名单
 * ******************************************************************************************************
 */
function is_go_exclude($url){ 
    $exclude_links = array();
    $site = get_option('home');
    if (!$site)
        $site = get_option('siteurl');
    $site = str_replace(array("http://", "https://"), '', $site);
    $p = strpos($site, '/');
    if ($p !== FALSE)
        $site = substr($site, 0, $p);/*网站根目录被排除在屏蔽之外，不仅仅是博客网址*/
    $exclude_links[] = "http://" . $site;
    $exclude_links[] = "https://" . $site;
    $exclude_links[] = 'javascript';
    $exclude_links[] = 'mailto';
    $exclude_links[] = 'skype';
    $exclude_links[] = '/';/* 有关相对链接*/
    $exclude_links[] = '#';/*用于内部链接*/

    $a = explode(PHP_EOL , io_get_option('exclude_links'));
    $exclude_links = array_merge($exclude_links, $a);
    foreach ($exclude_links as $val){
        if (stripos(trim($url), trim($val)) === 0) {
            return true;
        }
    }
    return false;
}
/**
 * 验证网址状态
 * ******************************************************************************************************
 */
function security_check($url){
    if(io_get_option('show_speed'))
        echo'<img class="security_check d-none" data-ip="'.gethostbyname(format_url($url,true)).'" src="//'. format_url($url,true) .'/'.mt_rand().'.png' . '" width=1 height=1  onerror=check("'. $url .'")>';
}
/**
 * 文章分页
 * ******************************************************************************************************
 */
function io_link_pages() {  
    global $wp_query, $numpages; 
    if(isset( $wp_query->query_vars[ 'view' ] ) && $wp_query->query_vars[ 'view' ] === 'all'){ 
        echo '<div class="page-nav text-center my-3"><a href="' . get_permalink() . '"><span class="all">分页阅读</span></a></div>';
    } elseif ( 1 < $numpages ) {
        wp_link_pages(array('before' => '<div class="page-nav text-center my-3">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="iconfont icon-arrow-l"></i></span>', 'nextpagelink' => "")); 
        wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); 
        wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => ' <span><i class="iconfont icon-arrow-r"></i></span>')); 
        echo ' <a href="' . get_pagenum_link( 1 ) . ( preg_match( '/\?/', get_pagenum_link( 1 ) ) ? '&' : '?' ) . 'view=all' . '"><span class="all">阅读全文</span></a></div>';
    }
}
add_filter( 'query_vars',  'wp_link_pages_all_parameter_queryvars'  );
add_action( 'the_post',  'wp_link_pages_all_the_post'  , 0 );
function wp_link_pages_all_parameter_queryvars( $queryvars ) {
    $queryvars[] = 'view';
    return( $queryvars );
}
function wp_link_pages_all_the_post( $post ) {
    global $pages, $multipage, $wp_query;
    if ( isset( $wp_query->query_vars[ 'view' ] ) && ( 'all' === $wp_query->query_vars[ 'view' ] ) ) {
        $multipage = true;
        $post->post_content = str_replace( '<!--nextpage-->', '', $post->post_content );
        $pages = array( $post->post_content );
    }
}
# 后台检测网址状态
# --------------------------------------------------------------------
add_action('admin_bar_menu', 'invalid_prompt_menu', 1000);
function invalid_prompt_menu() {
    if( ! is_admin() ) { return; }
    $n =io_get_option('failure_valve');
    if($n != 0){
        global $wp_admin_bar;
        $menu_id = 'invalid';
        $args = array(
            'post_type' => 'sites',// 文章类型
            'post_status' => 'publish',
            'meta_key' => 'invalid', 
            'meta_type' => 'NUMERIC', 
            'meta_value' => $n,
            'meta_compare' => '>'
        );
        $invalid_items = new WP_Query( $args ); 
        if ($invalid_items->have_posts()) : 
            $wp_admin_bar->add_menu(array(
                'id' => $menu_id,  
                'title' => '<span class="update-plugins count-2" style="display: inline-block;background-color: #d54e21;color: #fff;font-size: 9px;font-weight: 600;border-radius: 10px;z-index: 26;height: 18px;margin-right: 5px;"><span class="update-count" style="display: block;padding: 0 6px;line-height: 17px;">'.$invalid_items->found_posts.'</span></span>个网址可能失效', 
                'href' => get_option('siteurl')."/wp-admin/index.php"
            ));
        endif; 
        wp_reset_postdata();
    }
}
# 网址状态面板
# --------------------------------------------------------------------
if( is_admin() && io_get_option('failure_valve')!=0 ){
    add_action('wp_dashboard_setup', 'example_add_invalid_widgets' ); 
    function example_add_invalid_widgets() {
        wp_add_dashboard_widget('custom_invalid_help', __('网址状态','io_setting'), 'custom_invalid_help');
    }
    function custom_invalid_help() {
        echo '<p><i class="dashicons-before dashicons-admin-site"></i> <span>'.__('网站收录了','io_setting').' '.wp_count_posts('sites')->publish.' '.__('个网址','io_setting').'</span></p>';
    	global $post;
        $n =io_get_option('failure_valve');
        $args = array(
            'post_type' => 'sites',// 文章类型
            'post_status' => 'publish',
            'meta_key' => 'invalid', 
            'meta_type' => 'NUMERIC', 
            'meta_value' => $n,
            'meta_compare' => '>'
        );
        $invalid_items = new WP_Query( $args ); 
        if ($invalid_items->have_posts()) : 
            echo '<p><i class="dashicons-before dashicons-admin-site"></i> '.__('有','io_setting').' '.$invalid_items->found_posts.' '.__('个网址可能失效了(死链)','io_setting').'</p>';
            echo '<ul style="padding-left:20px">';
            echo '<span>'.__('失效列表：','io_setting').'</span><br>';
            while ( $invalid_items->have_posts() ) : $invalid_items->the_post();
                echo '<li style="display:inline-block;margin-right:10px"><a href="'.get_edit_post_link().'">'.get_the_title().'</a></li>';
            endwhile;
            echo '<br><span>'.__('请手动检测一下，如果没有问题，请点击对应网址进入编辑，然后修改自定义字段“invalid”的值为0','io_setting').'</span>';
            echo '</ul>';
        else:
            echo '<p><i class="dashicons-before dashicons-admin-site"></i> '.__('所有网址都可以正常访问','io_setting').'</p>';
        endif; 
        wp_reset_postdata();
    }
}
# 后台检测投稿状态
# --------------------------------------------------------------------
add_action('admin_bar_menu', 'pending_prompt_menu', 2000);
function pending_prompt_menu() {
    if( ! is_admin() ) { return; }
    global $wp_admin_bar;
    $menu_id = 'pending';
    $args = array(
        'post_type' => 'sites',// 文章类型
        'post_status' => 'pending',
    );
    $pending_items = new WP_Query( $args ); 
    if ($pending_items->have_posts()) : 
        $wp_admin_bar->add_menu(array(
            'id' => $menu_id,  
            'title' => '<span class="update-plugins count-2" style="display: inline-block;background-color: #d54e21;color: #fff;font-size: 9px;font-weight: 600;border-radius: 10px;z-index: 26;height: 18px;margin-right: 5px;"><span class="update-count" style="display: block;padding: 0 6px;line-height: 17px;">'.$pending_items->found_posts.'</span></span>个网址待审核', 
            'href' => get_option('siteurl')."/wp-admin/edit.php?post_status=pending&post_type=sites"
        ));	 
    endif; 
    wp_reset_postdata();
}
# 格式化 url
# --------------------------------------------------------------------
function format_url($url,$is_format=false){
    if($url == '')
    return;
    $url = rtrim($url,"/");
    if(io_get_option('ico-source')['url_format'] || $is_format){
        $pattern = '@^(?:https?://)?([^/]+)@i';
        $result = preg_match($pattern, $url, $matches);
        return $matches[1];
    }
    else{
        return $url;
    }
} 
# 格式化数字 $precision int 精度
# --------------------------------------------------------------------
function format_number($n, $precision = 2)
{
   return $n;
   if ($n < 1e+3) {
       $out = number_format($n);
   } else {
       $out = number_format($n / 1e+3, $precision) . 'k';
   }
   return $out;
}
# 获取点赞数
# --------------------------------------------------------------------
function get_like($id){
    if ( !$like_count = get_post_meta( $id, '_like_count', true ) ) {
        if(io_get_option('views_n')>0){
            $like_count = mt_rand(0, 10)*io_get_option('like_n');
            update_post_meta( $id, '_like_count', $like_count );
        }
        else
            $like_count = 0;
    }
    return format_number($like_count);
} 
# 浏览总数
# --------------------------------------------------------------------
function author_posts_views($author_id = 'all',$display = true){
    global $wpdb;
    if($author_id == 'all')
        $sql = "SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key='views'";
    else
        $sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'views' AND post_author =$author_id";    
    $comment_views = intval($wpdb->get_var($sql));
    if($display) {
        echo number_format_i18n($comment_views);
    } else {
        return $comment_views;
    }
}
# 获取分类下文章数量
# --------------------------------------------------------------------
function io_get_category_count($input = '') {
    global $wpdb;

    if($input == '') {
        $category = get_the_category();
        return $category[0]->category_count;
    }
    elseif(is_numeric($input)) {
        $SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->term_taxonomy.term_id=$input";
        return $wpdb->get_var($SQL);
    }
    else {
        $SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->terms.slug='$input'";
        return $wpdb->get_var($SQL);
    }
}
# 网址块样式
# --------------------------------------------------------------------
function get_columns($display = true){
	$class = '';
	switch(io_get_option('columns')) {
		case 2: 
			$class = ' col-sm-6 ';
			break;
		case 3: 
			$class = ' col-sm-6 col-md-4 ';
			break;
		case 4: 
			$class = ' col-sm-6 col-md-4 col-xl-3 ';
			break;
		case 6: 
			$class = ' col-sm-6 col-md-4 col-xl-5a col-xxl-6a '; 
			break;
        case 10: 
            $class = ' col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-10a ';
            break;
		default: 
			$class = ' col-sm-6 col-md-4 col-lg-3 ';
	} 
	if($display)
		echo $class;
	else
		return $class;
}
# 时间格式转化
# --------------------------------------------------------------------
function timeago( $ptime ) {
    date_default_timezone_set('PRC');
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return __('刚刚', 'i_theme');
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  __('年前', 'i_theme').' ('.date('Y', $ptime).')',
        30 * 24 * 60 * 60       =>  __('个月前', 'i_theme'),
        7 * 24 * 60 * 60        =>  __('周前', 'i_theme'),
        24 * 60 * 60            =>  __('天前', 'i_theme'),
        60 * 60                 =>  __('小时前', 'i_theme'),
        60                      =>  __('分钟前', 'i_theme'),
        1                       =>  __('秒前', 'i_theme')
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}
# 评论高亮作者
# --------------------------------------------------------------------
function is_master($email = '') {
    if( empty($email) ) return;
    $handsome = array( '1' => ' ', );
    $adminEmail = get_option( 'admin_email' );
    if( $email == $adminEmail ||  in_array( $email, $handsome )  )
    echo '<span class="is-author"  data-toggle="tooltip" data-placement="right" title="'.__('博主','i_theme').'"><i class="iconfont icon-user icon-fw"></i></span>';
}
# 头衔
# --------------------------------------------------------------------
function site_rank( $comment_author_email, $user_id ) {
    //$comment_rank = io_get_option( 'comment_rank' );
	//if ( ! $comment_rank ) return false;

	$v1 = 'Vip1';
	$v2 = 'Vip2';
	$v3 = 'Vip3';
	$v4 = 'Vip4';
	$v5 = 'Vip5';
	$v6 = 'Vip6';

	global $wpdb;
	$adminEmail = get_option( 'admin_email' );
	$num = count( $wpdb->get_results( "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' " ) );

	if ( $num > 0 && $num < 6 ) {
		$rank = $v1;
	}
	elseif ( $num > 5 && $num < 11 ) {
		$rank = $v2;
	}
	elseif ( $num > 10 && $num < 16 ) {
		$rank = $v3;
	}
	elseif ($num > 15 && $num < 21) {
		$rank = $v4;
	}
	elseif ( $num > 20 && $num < 26 ) {
		$rank = $v5;
	}
	elseif ( $num > 25 ) {
		$rank = $v6;
	}

	if( $comment_author_email != $adminEmail )
		return $rank = '<span class="rank" data-toggle="tooltip" data-placement="right" title="'.__('头衔：','i_theme') . $rank .'，'.__('累计评论：','i_theme') . $num .'">'. $rank .'</span>';
}
# 评论格式
# --------------------------------------------------------------------
if(!function_exists('my_comment_format')){
	function my_comment_format($comment, $args, $depth){
		$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class('comment'); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment_body d-flex flex-fill">	
				<div class="profile mr-2 mr-md-3"> 
                    <?php 
                    echo  get_avatar( $comment, 96, '', get_comment_author() );
                    ?>
				</div>					
				<section class="comment-text d-flex flex-fill flex-column">
					<div class="comment-info d-flex align-items-center mb-1">
						<div class="comment-author text-sm"><?php comment_author_link(); ?>
						<?php is_master( $comment->comment_author_email ); echo site_rank( $comment->comment_author_email, $comment->user_id ); ?>
						</div>										
					</div>
					<div class="comment-content d-inline-block text-sm">
						<?php comment_text(); ?> 
			    	    <?php
    		    	    if ($comment->comment_approved == '0'){
      		    	    echo '<span class="cl-approved">('.__('您的评论需要审核后才能显示！','i_theme').')</span><br />';
    		    	    } 
			    	    ?>
					</div>
					<div class="d-flex flex-fill text-xs text-muted pt-2">
						<div class="comment-meta">
							<div class="info"><time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' );?>"><?php echo timeago(get_comment_date('Y-m-d G:i:s'));?></time></div>
						</div>
						<div class="flex-fill"></div>
						<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
					</div>
				</section>
			</div>
	 	
		<?php
	}
}
/**
 * 禁止冒充管理员评论
 * ******************************************************************************************************
 */
function usercheck($incoming_comment) {
	$isSpam = false;
	if (trim($incoming_comment['comment_author']) == io_get_option('io_administrator')['admin_name'] )
	    $isSpam = true;
	if (trim($incoming_comment['comment_author_email']) ==  io_get_option('io_administrator')['admin_email'] )
	    $isSpam = true;
	if(!$isSpam)
	    return $incoming_comment;
    error('{"status":3,"msg":"'.__('请勿冒充管理员发表评论！' , 'i_theme' ).'"}', true);
}
if (!is_user_logged_in()) { add_filter('preprocess_comment', 'usercheck'); }
/**
 * 过滤纯英文、日文和一些其他内容
 * ******************************************************************************************************
 */
function io_refused_spam_comments($comment_data) {
    $pattern = '/[一-龥]/u';
    $jpattern = '/[ぁ-ん]+|[ァ-ヴ]+/u';
    $links = '/http:\/\/|https:\/\/|www\./u';
    if (io_get_option('io_comment_set')['no_url'] && (preg_match($links, $comment_data['comment_author']) || preg_match($links, $comment_data['comment_content']))) {
        error('{"status":3,"msg":"'.__('别啊，昵称和评论里面添加链接会怀孕的哟！！' , 'i_theme').'"}', true);
    }
    if(io_get_option('io_comment_set')['no_chinese']){
        if (!preg_match($pattern, $comment_data['comment_content'])) {
            error('{"status":3,"msg":"'.__('评论必须含中文！' , 'i_theme' ).'"}', true);
        }
        if (preg_match($jpattern, $comment_data['comment_content'])) {
            error('{"status":3,"msg":"'.__('评论必须含中文！' , 'i_theme' ).'"}', true);
        }
    }
    if (wp_blacklist_check($comment_data['comment_author'], $comment_data['comment_author_email'], $comment_data['comment_author_url'], $comment_data['comment_content'], isset($comment_data['comment_author_IP']), isset($comment_data['comment_agent']))) {
        header("Content-type: text/html; charset=utf-8");
        error('{"status":3,"msg":"'.sprintf(__('不好意思，您的评论违反了%s博客评论规则' , 'i_theme'), get_option('blogname')).'"}', true);
    }
    return ($comment_data);
}
if (!is_user_logged_in()) add_filter('preprocess_comment', 'io_refused_spam_comments');
/**
 * 禁止评论自动超链接
 * ******************************************************************************************************
 */
remove_filter('comment_text', 'make_clickable', 9);   
/**
 * 屏蔽长链接转垃圾评论
 * ******************************************************************************************************
 */
function lang_url_spamcheck($approved, $commentdata) {
    return (strlen($commentdata['comment_author_url']) > 50) ?
    'spam' : $approved;
}
add_filter('pre_comment_approved', 'lang_url_spamcheck', 99, 2);
# 某作者文章浏览数
# --------------------------------------------------------------------
if(!function_exists('author_posts_views')) {
	function author_posts_views($author_id = 1 ,$display = true) {
		global $wpdb;
		$sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'views' AND post_author =$author_id";
		$comment_views = intval($wpdb->get_var($sql));
		if($display) {
			echo number_format_i18n($comment_views);
		} else {
			return $comment_views;
		}
	}
}
# 获取作者所有文章点赞数
# --------------------------------------------------------------------
if(!function_exists('author_posts_likes')) {
    function author_posts_likes($author_id = 'all' ,$display = true) {
        global $wpdb;
        if($author_id == 'all')
            $sql = "SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key='_like_count'";
        else
            $sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = '_like_count' AND post_author = $author_id ";
            
        $posts_likes = intval($wpdb->get_var($sql));	
        if($display) {
            echo number_format_i18n($posts_likes);
        } else {
            return $posts_likes;
        }
    }
}
# 获取热门文章
# --------------------------------------------------------------------
function get_timespan_most_viewed($mode = '', $limit = 10, $days = 7, $show_thumbs = false, $newWindow='', $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
            $temp .= "<div class='list-item py-2'>";
            if($show_thumbs){
                $temp .= "<div class='media media-3x2 rounded col-4 mr-3'>";
                $thumbnail =  io_theme_get_thumb();
                if(io_get_option('lazyload'))
                    $temp .= '<a class="media-content" href="'.get_permalink().'" '. $newWindow .' title="'.get_the_title().'" data-src="'.$thumbnail.'"></a>';
                else
                    $temp .= '<a class="media-content" href="'.get_permalink().'" '. $newWindow .' title="'.get_the_title().'" style="background-image: url('.$thumbnail.');"></a>';
                $temp .= "</div>"; 
            }
            $temp .= '
                <div class="list-content py-0">
                    <div class="list-body">
                        <a href="'.get_permalink().'" class="list-title overflowClip_2" '. $newWindow .' rel="bookmark">'.get_the_title().'</a>
                    </div>
                    <div class="list-footer">
                        <div class="d-flex flex-fill text-muted text-xs">
                            <time class="d-inline-block">'.timeago(get_the_time('Y-m-d G:i:s')).'</time>
                            <div class="flex-fill"></div>' 
                            .the_views( false, '<span class="views"><i class="iconfont icon-chakan"></i> ','</span>' ).
                        '</div>
                    </div> 
                </div> 
            </div>'; 
		}
	} else {
		$temp = "<div class='list-item py-2'>".__("暂无文章","i_theme")."</div>";
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}
# 获取热门网址
# --------------------------------------------------------------------
function get_sites_most_viewed( $limit = 10, $days = 7, $newWindow='', $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
    $temp = '';
    $where = "post_type = 'sites'";

	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) { 
            $sites_type = get_post_meta($post->ID, '_sites_type', true);
            $link_url = get_post_meta($post->ID, '_sites_link', true); 
            $default_ico = get_theme_file_uri('/images/favicon.png');
            $ico = get_post_meta_img($post->ID, '_thumbnail', true);
            if($ico == ''){
                if( $link_url != '' || ($sites_type == "sites" && $link_url != '') )
                    $ico = (io_get_option('ico-source')['ico_url'] .format_url($link_url) . io_get_option('ico-source')['ico_png']);
                elseif($sites_type == "wechat")
                    $ico = get_theme_file_uri('/images/qr_ico.png');
                elseif($sites_type == "down")
                    $ico = get_theme_file_uri('/images/down_ico.png');
                else
                    $ico = $default_ico;
            }

            //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)){
                $temp .= '<div class="url-card col-6 '. before_class($post->ID) .'">';
                $temp .= '<a href="'.get_permalink().'" '.$newWindow.' class="card post-min mb-2">
                <div class="card-body" style="padding:0.3rem 0.5rem;">
                <div class="url-content d-flex align-items-center">
                    <div class="url-img rounded-circle">';
                        if(io_get_option('lazyload')):
                            $temp .= '<img class="lazy" src="'.$default_ico.'" data-src="'.$ico.'" onerror="javascript:this.src=\''.$default_ico.'\'">';
                        else:
                            $temp .= '<img src="'.$ico.'" onerror="javascript:this.src='.$default_ico.'">';
                        endif;
                        $temp .= '</div>
                    <div class="url-info pl-1 flex-fill">
                    <div class="text-xs overflowClip_1">'.get_the_title().'</div>
                    </div>
                </div>
                </div>
            </a> 
            </div>';
              
            //}

		}
	} else {
		$temp = "<div class='list-item py-2'>".__("暂无文章","i_theme")."</div>";
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}
# 归档页显示数量单独设置
# --------------------------------------------------------------------
add_action('pre_get_posts', 'filter_pre_get_posts');
function filter_pre_get_posts( $query ){
    if ( $query->is_main_query() ){
        $num = '';    
        if ( is_tax('favorites') ){ $num = io_get_option('site_archive_n')?:''; } 
        if ( is_tax('sitetag') ){ $num = io_get_option('site_archive_n')?:''; } 
        if ( is_tax('apps') ){ $num = io_get_option('app_archive_n')?:''; } 
        if ( is_tax('apptag') ){ $num = io_get_option('app_archive_n')?:''; } 
        if ( is_tax('books') ){ $num = io_get_option('book_archive_n')?:''; } 
        if ( is_tax('booktag') ){ $num = io_get_option('book_archive_n')?:''; } 
        if ( is_tax('series') ){ $num = io_get_option('book_archive_n')?:''; } 
        
        if ( '' != $num ){ $query->set( 'posts_per_page', $num ); }
  
    }
    return $query;
}
# 替换用户链接
# --------------------------------------------------------------------
add_filter('author_link', 'author_link', 10, 2);
function author_link( $link, $author_id) {
    global $wp_rewrite;
    $author_id = (int) $author_id;
    $link = $wp_rewrite->get_author_permastruct();
    if ( empty($link) ) {
        $file = home_url( '/' );
        $link = $file . '?author=' . $author_id;
    } else {
        $link = str_replace('%author%', $author_id, $link);
        $link = home_url( user_trailingslashit( $link ) );
    }
    return $link;
}
add_filter('request', 'author_link_request');
function author_link_request( $query_vars ) {
    if ( array_key_exists( 'author_name', $query_vars ) ) {
        global $wpdb;
        $author_id=$query_vars['author_name'];
        if ( $author_id ) {
            $query_vars['author'] = $author_id;
            unset( $query_vars['author_name'] );    
        }
    }
    return $query_vars;
}
# 屏蔽用户名称类
# --------------------------------------------------------------------
add_filter('comment_class','remove_comment_body_author_class');
add_filter('body_class','remove_comment_body_author_class');
function remove_comment_body_author_class( $classes ) {
	foreach( $classes as $key => $class ) {
	if(strstr($class, "comment-author-")||strstr($class, "author-")) {
			unset( $classes[$key] );
		}
	}
	return $classes;
}
# 禁止谷歌字体
# --------------------------------------------------------------------
add_action( 'init', 'remove_open_sans' );
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
# 字体增加
# --------------------------------------------------------------------
add_filter('tiny_mce_before_init', 'custum_fontfamily');
function custum_fontfamily($initArray){  
   $initArray['font_formats'] = "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆';";  
   return $initArray;  
} 
/**
 * 关键词加链接
 * ******************************************************************************************************
 */
if (io_get_option('tag_c')['switcher']) {
    add_filter('the_content','tag_link',1);
    function tag_link($content){
        global $post_type;
        $match_num_from = 1;		//配置：一个关键字少于多少不替换  
        $match_num_to = io_get_option('tag_c')['chain_n'];		//配置：一个关键字最多替换，建议不大于2  
        $post_tags = get_terms( to_post_tag($post_type), 'orderby=count&hide_empty=0' );//get_the_tags();
        if ($post_tags) {
            $sort_func = function($a, $b){
                if ( $a->name == $b->name ) return 0;
                return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
            };
            usort($post_tags, $sort_func);//重新排序
            $ex_word = '';
            $case = false ? "i" : ""; //配置：忽略大小写 true是开，false是关  
            foreach($post_tags as $tag) {
                $link = get_tag_link($tag->term_id);
                $keyword = $tag->name;
                $cleankeyword = stripslashes($keyword);
                $url = "<a class=\"external\" href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('查看与 %s 相关的文章', 'i_theme' ))."\"";
                $url .= ' target="_blank"';
                $url .= ">".addcslashes($cleankeyword, '$')."</a>";
                $limit = rand($match_num_from,$match_num_to);
                $ex_word = preg_quote($cleankeyword, '\''); 
                $content = preg_replace( '|(<a[^>]+>)(.*)<pre.*?>('.$ex_word.')(.*)<\/pre>(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);//a标签，免混淆处理  
                $content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);//img标签
                $cleankeyword = preg_quote($cleankeyword,'\'');
                $regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
                $content = preg_replace($regEx,$url,$content,$limit);
                $content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);//免混淆还原处理  
            }
        }
        return $content;
    }
}
# 链接添加图标
# --------------------------------------------------------------------
function autoicon($content) {
    $home = get_option('home');
    $return = str_replace('<a href=', '<a class="external" href=', $content);
    //$return = str_replace('<a class="external" href="'.$home, '<a href="'.$home, $return);
    //$return = str_replace('<a class="external" href="#', '<a href="#', $return);
    return $return;
}
//add_filter('the_content', 'autoicon');   //应用于文章区域
//add_filter('comment_text', 'autoicon');   //应用于评论区域
# 移除 WordPress 文章标题前的“私密/密码保护”提示文字
# --------------------------------------------------------------------
add_filter('private_title_format', 'remove_title_prefix');//私密
add_filter('protected_title_format', 'remove_title_prefix');//密码保护
function remove_title_prefix($content) {
	return '%s';
}
# FancyBox图片灯箱
# --------------------------------------------------------------------
//add_filter('the_content', 'io_fancybox');
function io_fancybox($content){ 
    global $post;
    $title = $post->post_title;
    $pattern = array("/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>(.*?)<\/a>/i","/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i");
    $replacement = array('<a$1href=$2$3.$4$5 data-fancybox="images"$6 data-caption="'.$title.'">$7</a>','<a$1href=$2$3.$4$5 data-fancybox="images" data-caption="'.$title.'"><img$1src=$2$3.$4$5$6></a>');
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
/**
 * 去掉正文图片外围标签p、自动添加 a 标签和 data-original
 * ******************************************************************************************************
 */
function lazyload_fancybox($content) {
    global $post;
    $title = $post->post_title;
	$loadimg_url=get_template_directory_uri().'/images/t.png';
  	//判断是否为文章页或者页面
  	if(!is_single())
		return $content;
	if(!is_feed()||!is_robots()) {
		$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '$1$2$3', $content);
		$content = preg_replace("/<a[^>]*>(<img.*?)<\/a>/is", "$1", $content);//删原有<a>标签
		//$content = preg_replace("/<p[^>]*>(<img.*?)<\/p>/is", "$1", $content);
		
        $pattern = '/<img(.*?)src=[\'|"]([^\'"]+)[\'|"](.*?)>/i';
        //$pattern = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i";
        if(io_get_option('lazyload')){
            $replacement = '<a href="$2" alt="'.$title.'" data-fancybox="images" data-caption="'.$title.'"><img$1data-src="$2" src="$loadimg_url" alt="'.$title.'"$3></a>';
        } else {
            $replacement = '<a href="$2" alt="'.$title.'" data-fancybox="images" data-caption="'.$title.'"><img$1src="$2" alt="'.$title.'"$3></a>';
        }
		$content = preg_replace($pattern,$replacement,$content);
	}
	return $content;
}
add_filter ('the_content', 'lazyload_fancybox',10);
# 正文外链跳转和自动nofollow
# --------------------------------------------------------------------
if (io_get_option('is_go')) {
    add_filter( 'the_content', 'io_the_content_to', 999 );
    function io_the_content_to($content){
        preg_match_all('/href="(http.*?)"/',$content,$matches);
        if($matches){
            foreach($matches[1] as $val){
                if( is_go_exclude($val)===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$val)) {
                    $content=str_replace("href=\"$val\"", "class=\"external\" rel=\"nofollow\" target=\"_blank\" href=\"" .home_url(). "/go/?url=" .base64_encode($val). "\"",$content);
                }
            }
         }
        return $content;
    }
} else {
    add_filter( 'the_content', 'ioc_seo_wl');
    function ioc_seo_wl( $content ) {
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
        if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
            if( !empty($matches) ) {
                $srcUrl = get_option('siteurl');
                for ($i=0; $i < count($matches); $i++)
                {
                    $tag = $matches[$i][0];
                    $tag2 = $matches[$i][0];
                    $url = $matches[$i][0];
       
                    $noFollow = '';
                    $pattern = '/target\s*=\s*"\s*_blank\s*"/';
                    preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                    if( count($match) < 1 ){
                        $noFollow .= ' target="_blank" ';
                    }
                    $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
                    preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                    if( count($match) < 1 ){
                        $noFollow .= ' rel="nofollow" ';
                    }
                    $pos = strpos($url,$srcUrl);
                    if ($pos === false) {
                        $tag = rtrim ($tag,'>');
                        $tag .= $noFollow.'>';
                        $content = str_replace($tag2,$tag,$content);
                    }
                }
            }
        }
        $content = str_replace(']]>', ']]>', $content);
        return $content;
    }
}
# 评论作者链接跳转 or 评论作者链接新窗口打开
# --------------------------------------------------------------------
if (io_get_option('is_go')) {
    add_filter('get_comment_author_link', 'comment_author_link_to');
    function comment_author_link_to() {
        $encodeurl = get_comment_author_url();
        $url = home_url().'/go/?url=' . base64_encode($encodeurl);
        $author = get_comment_author();
        if ( empty( $encodeurl ) || 'http://' == $encodeurl )
            return $author;
        else
            return "<a href='$url' target='_blank' rel='nofollow noopener noreferrer' class='url'>$author</a>";
    }
} else {
    add_filter('get_comment_author_link', 'comment_author_link_blank');
    function comment_author_link_blank() {
        $url    = get_comment_author_url();
        $author = get_comment_author();
        if ( empty( $url ) || 'http://' == $url )
            return $author;
        else
            return "<a target='_blank' href='$url' rel='nofollow noopener noreferrer' class='url'>$author</a>";
    }
}
# 定制CSS
# --------------------------------------------------------------------
add_action('wp_head','modify_css');
function modify_css(){
	if (io_get_option("custom_css")) {
		$css = substr(io_get_option("custom_css"), 0);
		echo "<style>" . $css . "</style>";
	}
}
# 移除系统菜单模块
# -------------------------------------------------------------------- 
if ( is_admin() ) {   
    //add_action('admin_init','remove_submenu');  
    function remove_submenu() {   
        remove_submenu_page( 'themes.php', 'theme-editor.php' );   //移除主题编辑页
    }  
}  
# 登陆页添加验证码
# -------------------------------------------------------------------- 
function add_login_head() {
    echo '<script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>';
    echo '<style type="text/css">.login_button {line-height:38px;border-radius:3px;cursor:pointer;color:#fff;background:#f1404b;border:2px solid #f1404b;font-size:14px;margin-bottom:20px;text-align:center;transition:.5s;}.login_button:hover{color:#fff;background:#111;border-color:#111;}</style>'; 
}
function add_captcha_body(){ ?>
    <input type="hidden" id="wp007_tcaptcha" name="tcaptcha_007" value="" />
    <input type="hidden" id="wp007_ticket" name="syz_ticket" value="" />
    <input type="hidden" id="wp007_randstr" name="syz_randstr" value="" />
    <div id="TencentCaptcha" data-appid="<?php echo io_get_option('io_captcha')['appid_007'] ?>" data-cbfn="callback" class="login_button"><?php _e('验证',"i_theme") ?></div>
    <script>
        window.callback = function(res){
            if(res.ret === 0){
                var but = document.getElementById("TencentCaptcha");
                document.getElementById("wp007_ticket").value = res.ticket;
                document.getElementById("wp007_randstr").value = res.randstr;
                document.getElementById("wp007_tcaptcha").value = 1;
                but.style.cssText = "color:#fff;background:#4fb845;border-color:#4fb845;pointer-events:none";
                but.innerHTML = "<?php  _e('验证成功',"i_theme") ?>";
            }
        }
    </script>
<?php
}
function validate_tcaptcha_login($user) { 
    $slide=$_POST['tcaptcha_007'];
    if($slide == ''){
        return  new WP_Error('broke', __("请先验证！！！","i_theme"));
    }
    else{
        $result = validate_ticket($_POST['syz_ticket'],$_POST['syz_randstr']);
        if ($result['result']) {
            return $user;
        } else{
            return  new WP_Error('broke', $result['message']);
        }
    }
  
}
if(io_get_option('io_captcha')['tcaptcha_007']){
    add_action('login_head', 'add_login_head');
    add_action('login_form','add_captcha_body');
    add_filter('wp_authenticate_user',  'validate_tcaptcha_login',100,1);
}
# 重写规则
# --------------------------------------------------------------------
add_action('generate_rewrite_rules', 'io_rewrite_rules' );  
if ( ! function_exists( 'io_rewrite_rules' ) ){ 
    function io_rewrite_rules( $wp_rewrite ){   
        $new_rules = array(    
            'go/?$'          => 'index.php?custom_page=go',
        ); //添加翻译规则   
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;   
        //php数组相加   
    }   
} 
add_action('query_vars', 'io_add_query_vars');  
if ( ! function_exists( 'testthemes_add_query_vars' ) ){ 
    function io_add_query_vars($public_query_vars){     
        $public_query_vars[] = 'custom_page'; //往数组中添加custom_page   
        return $public_query_vars;     
    }  
} 
add_action("template_redirect", 'io_template_redirect');   //模板载入规则  
if ( ! function_exists( 'io_template_redirect' ) ){ 
    function io_template_redirect(){   
        global $wp;   
        global $wp_query, $wp_rewrite;  
        if( !isset($wp_query->query_vars['custom_page']) )   
            return;    
        $reditect_page =  $wp_query->query_vars['custom_page'];   
        switch ($reditect_page) {
            case 'go':
                include(TEMPLATEPATH.'/go.php');
                die();
        }
    }
} 
# 激活主题更新重写规则
# --------------------------------------------------------------------
add_action( 'load-themes.php', 'io_flush_rewrite_rules' );   
function io_flush_rewrite_rules() {   
    global $pagenow, $wp_rewrite;   
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) )   
        $wp_rewrite->flush_rules();   
}
# 自定义图标
# --------------------------------------------------------------------
class iconfont {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ),1,3 );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
    } 

	function nav_menu_css_class($classes, $item, $args){
        if($args->theme_location == 'nav_main') { //这里的 main 是菜单id
            $classes[] = 'sidebar-item'; //这里的 nav-item 是要添加的class类
        } 

		if( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(fa[b|s]?|io)(-\S+)?$/i', $classes );
			if( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		//if( !in_array( 'fa', $classes ) ){
		//	array_unshift( $classes, 'fa' );
		//}
		$before = true;
        $icon = '
        <i class="' . implode( ' ', $classes ) . ' icon-fw icon-lg mr-2"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if( $before ){
                $item_output .= $icon . '
                <span>' . $matches[2] . '</span>';
			} else {
                $item_output .= '
                <span>' . $matches[2] . '</span>
                ' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(fa[b|s]?|io)(-\S+)?$/i', $item->classes );
			if( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new iconfont();
/**
 * 根据用户设置选择邮件发送方式
 */
function i_switch_mailer($phpmailer){
    $mailer = io_get_option('i_default_mailer');
    if($mailer === 'smtp'){
        $phpmailer->Host = io_get_option('i_smtp_host'); // 邮箱SMTP服务器
        $phpmailer->SMTPAuth = true; // 强制它使用用户名和密码进行身份验证
        $phpmailer->Port = io_get_option('i_smtp_port'); // SMTP端口
        $phpmailer->Username = io_get_option('i_smtp_username'); // 邮箱账户
        $phpmailer->Password = io_get_option('i_smtp_password'); // 此处填写邮箱生成的授权码，不是邮箱登录密码
 
        $phpmailer->SMTPSecure = io_get_option('i_smtp_secure'); // 端口25时 留空，465时 ssl，不需要改
        $phpmailer->FromName = io_get_option('i_smtp_name'); // 发件人昵称
        $phpmailer->From = $phpmailer->Username;  // 邮箱账户同上
        $phpmailer->Sender = $phpmailer->From; 
        $phpmailer->AddReplyTo($phpmailer->From,$phpmailer->FromName); 
        $phpmailer->IsSMTP();
    }else{
        $phpmailer->FromName = io_get_option('i_mail_custom_sender');
        $phpmailer->From = io_get_option('i_mail_custom_address');
    }
}
add_action('phpmailer_init', 'i_switch_mailer');
# 搜索只查询文章和网址。
# --------------------------------------------------------------------
//add_filter('pre_get_posts','searchfilter');
function searchfilter($query) {
    //限定对搜索查询和非后台查询设置
    if ($query->is_search && !is_admin() ) {
        $query->set('post_type',array('sites','post'));
    }
    return $query;
}
# 修改搜索查询的sql代码，将postmeta表左链接进去。
# --------------------------------------------------------------------
add_filter('posts_join', 'cf_search_join' );
function cf_search_join( $join ) {
    if(is_admin())
        return $join;
    global $wpdb;
    if ( is_search() ) {
      $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}
add_filter('posts_where', 'cf_search_where');// 在wordpress查询代码中加入自定义字段值的查询。
function cf_search_where( $where ) {
    if(is_admin())
        return $where; 
    global $pagenow, $wpdb;
    if ( is_search() ) {
        $where = preg_replace("/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
        "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    return $where;
}
add_filter('posts_distinct', 'cf_search_distinct');// 去重
function cf_search_distinct($where) {
    if( is_admin() ) return $where;
    global $wpdb;
    if ( is_search() )  {
      return "DISTINCT";
    }
    return $where;
}

 