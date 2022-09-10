<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

//date_default_timezone_set('Asia/Shanghai');

require get_theme_file_path('/inc/inc.php'); 

add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup(){
    load_theme_textdomain( 'i_theme', get_template_directory() . '/languages' );
    load_theme_textdomain( 'io_setting', get_template_directory() . '/languages' );
}

//登录页面的LOGO链接为首页链接
add_filter('login_headerurl',function() {return get_bloginfo('url');});
//登陆界面logo的title为博客副标题
add_filter('login_headertext',function() {return get_bloginfo( 'description' );});

/**
 * 启用主题后进仪表盘 
 */
add_action('load-themes.php', 'Init_theme');
function Init_theme(){
    global $pagenow;
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
        update_option( 'thumbnail_size_w',0 );
        update_option( 'thumbnail_size_h', 0 );
        update_option( 'thumbnail_crop', 0 );
        update_option( 'medium_size_w',0 );
        update_option( 'medium_size_h', 0 );
        update_option( 'large_size_w',0 );
        update_option( 'large_size_h', 0 );
        wp_redirect( admin_url( '/index.php' ) );
        exit;
    }
}


/**
 * 禁止自动生成 768px 缩略图
 */
function shapeSpace_customize_image_sizes($sizes) {
    unset($sizes['medium_large']);
    return $sizes;
  }
add_filter('intermediate_image_sizes_advanced', 'shapeSpace_customize_image_sizes');
/**
 * wordpress禁用图片属性srcset和sizes
 */
add_filter( 'add_image_size', function(){return 1;} );
add_filter( 'wp_calculate_image_srcset_meta', '__return_false' );
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * 禁止WordPress自动生成缩略图
 */
function ztmao_remove_image_size($sizes) {
    unset( $sizes['small'] );
    unset( $sizes['medium'] );
    unset( $sizes['large'] );
    return $sizes;
}
add_filter('image_size_names_choose', 'ztmao_remove_image_size');


# 支持自定义功能
# ------------------------------------------------------------------------------
//add_action( 'admin_notices', 'webstacks_init_check' );
function webstacks_init_check(){
    $html = '<div id="notice-warning-tgmpa" class="notice notice-warning is-dismissible">
                <p>
                    <b>提示：</b> 启用主题或者更新主题后请保存主题设置，不然可能会报错，
                    <a href="'.get_option('siteurl').'/wp-admin/admin.php?page=theme_settings#tab=1">立即前往保存</a>
                </p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
            </div>';
    echo $html;
}
//add_action( 'after_switch_theme', 'active_webstacks_notice');
function active_webstacks_notice() {
    $notice = '<div id="setting-error-tgmpa" class="notice notice-info is-dismissible"> 
				<p>
					<b>通知：</b> WebStacks PRO 主题已激活，鉴于之前很多用户使用时都遇到了问题，请您先去 
     				<a href="'.get_option('siteurl').'/wp-admin/index.php">仪表盘</a>仔细阅读使用说明，谢谢！ 
     			</p> 
     			<button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button> 
     		</div>';
    echo $notice;
}
# 说明
# ------------------------------------------------------------------------------
add_action('wp_dashboard_setup', 'example_add_dashboard_widgets' );
function custom_dashboard_help() {
    echo '<li style="font-size:18px;color: red">'.__('先保存一遍主题设置选项，否则会报错','i_theme').'</li>
        <p>首次安装检查如下设置：</p>
		<ul style="list-style:decimal;padding-left:15px">
            <li>404问题请检查服务器伪静态规则和wp固定链接格式，推荐“/%post_id%.html”。</li>
            <li>首次启用主题必须保存一遍主题选项才能打开首页，否则可能会报错。</li>
            <li style="color: red">启用主题前请禁用所有插件，以免插件冲突。</li>
		</ul>
		<p>主题使用注意事项：</p>
		<ul style="list-style:decimal;padding-left:15px">
			<li>请先查看：<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank">主题使用说明</a></li>
            <li>菜单图标设置请查看主题使用说明和群公共。</li>
            <li>先创建网址分类，然后这添加网址。</li>
            <li>分类最多两级，且第一级不要添加内容。</li>
            <li style="color: red">更新主题后请重新保存主题设置。</li>
            <li>文章缩略图报错或者不显示，请添加图床地址url到主题根目录的timthumb.php文件的第130行下面，按示例格式添加。</li>
			<li>投搞、博客等页面请新建页面然后选择对应的页面模板。</li>
            <li>阿里图标 Iconfont：<a href="https://www.iowen.cn/webstack-pro-navigation-theme-iconfont/" target="_blank">使用方法</a></li>
            <li>侧栏菜单设置方法：<a href="https://www.iowen.cn/webstack-pro-theme-main-menu-setting-description/" target="_blank" style="color: red">必看</a></li>
        </ul>
        <p>推荐插件：</p>
		<ul style="list-style:decimal;padding-left:15px">
            <li>自动将文章、分类、标签的地址转化为拼音，<a href="https://wordpress.org/plugins/so-pinyin-slugs/" target="_blank">获取插件</a></li>
            <li>对象缓存插件 Memcached， <a href="https://www.baidu.com/s?wd=wordpress%20Memcached" target="_blank">使用方法</a></li>
            <li>XML Sitemap插件，<a href="https://wordpress.org/plugins/xml-sitemap-feed/" target="_blank">获取插件</a></li>
            <li style="color: red">如果不会操作，可以都不用哦 --. 不影响使用</li>
		</ul>
        <br>
        <p>---> 下载 <a href="https://www.iowen.cn/webstack-pro-theme-presentation-data-import-instructions/" target="_blank">演示数据</a> <---</p>
    ';
}
function example_add_dashboard_widgets() {
    wp_add_dashboard_widget('custom_help_widget', __('WebStacks PRO 主题使用说明','i_theme'), 'custom_dashboard_help');
}

function exclude_category_home( $query ) {
    if(is_home() ||  is_tax() )
        $query->set( 'post__not_in', get_option( 'sticky_posts' ) ); 
    return $query;
}
//add_filter( 'pre_get_posts', 'exclude_category_home' );


//兼容性修改
//更改自定义类型
//通过图片地址获取图片id 

function change_sites_meta(){ 
    $args = array(
        'post_type' => array('sites'), 
        'post_status' => 'publish',
        'meta_key' => '_visible',  
        'posts_per_page'      => -1,  
    );
    $invalid_items = new WP_Query( $args ); 
    if ($invalid_items->have_posts()) : while ( $invalid_items->have_posts() ) : $invalid_items->the_post();
        if($formal_url = get_post_meta(get_the_ID(), '_visible', true)){
            //echo "-id/".get_the_ID(); 
            wp_private_post(get_the_ID()) ;
        }
    endwhile;endif; 
    wp_reset_postdata();
    update_option('io_change_visible', 1 );
}
if( is_admin() && get_option( 'io_change_visible',0 )!=1){
  
    change_sites_meta();//修改网址下载截图到新字段，2.0218以后的测试版下载资源如果添加了截图，需运行一次

}
function wp_private_post( $post ) {
    global $wpdb;

    if ( ! $post = get_post( $post ) ) {
        return false;
    }

    if ( 'private' == $post->post_status ) {
        return false;
    }

    $wpdb->update( $wpdb->posts, [ 'post_status' => 'private' ], [ 'ID' => $post->ID ] );

    clean_post_cache( $post->ID );

    $old_status        = $post->post_status;
    $post->post_status = 'private';
    wp_transition_post_status( 'private', $old_status, $post );

    return true;
}
