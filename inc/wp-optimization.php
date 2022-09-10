<?php if ( ! defined( 'ABSPATH' )  ) { die; }
/*
 * WordPress 优化
 */
 
# --------------------------------------------------------------------
# 屏蔽文章修订
# --------------------------------------------------------------------
if(io_get_option('diable_revision')){ 
    remove_action('pre_post_update', 'wp_save_post_revision' ); 
    /** 禁用自动保存 */
    add_action('wp_print_scripts', 'io_not_autosave');
    function io_not_autosave() {
        wp_deregister_script('autosave'); 
    } 
    // 禁用版本修订
    add_filter( 'wp_revisions_to_keep', 'io_disable_wp_revisions_to_keep', 10, 2 );
    function io_disable_wp_revisions_to_keep( $num, $post ) {
    	return 0;
    }
}
# --------------------------------------------------------------------
# 移除admin bar
# --------------------------------------------------------------------
if(io_get_option('remove_admin_bar')){
    add_filter('show_admin_bar', '__return_false');
}
# --------------------------------------------------------------------
# 屏蔽字符转码
# --------------------------------------------------------------------
if(io_get_option('disable_texturize')){
	add_filter('run_wptexturize', '__return_false');
}
# --------------------------------------------------------------------
# 禁用古腾堡
# --------------------------------------------------------------------
if(io_get_option('disable_gutenberg')){
    add_filter('use_block_editor_for_post_type', '__return_false');
}
# --------------------------------------------------------------------
# 屏蔽站点Feed
# --------------------------------------------------------------------
if( io_get_option('disable_feed') ) {
    function io_disable_feed() {
        wp_die('<h1>'.sprintf(__('Feed已经关闭, 请访问网站<a href="%s">首页' , 'i_theme'), get_bloginfo('url')).'</a>!</h1>');
    }
    add_action('do_feed',      'io_disable_feed', 1);
    add_action('do_feed_rdf',  'io_disable_feed', 1);
    add_action('do_feed_rss',  'io_disable_feed', 1);
    add_action('do_feed_rss2', 'io_disable_feed', 1);
    add_action('do_feed_atom', 'io_disable_feed', 1);
}
# --------------------------------------------------------------------
# 禁用 XML-RPC 接口
# --------------------------------------------------------------------
if(io_get_option('disable_xml_rpc')){
    if(io_get_option('disable_gutenberg')){
        add_filter( 'xmlrpc_enabled', '__return_false' );
        remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
    }
}
# --------------------------------------------------------------------
# ----------
# --------------------------------------------------------------------
add_filter('register_taxonomy_args', function($args){
	// 屏蔽 REST API
	if(io_get_option('disable_rest_api')){
		$args['show_in_rest']	= false;
	}

	return $args;
});
add_filter('register_post_type_args', function($args){
	// 屏蔽 REST API
	if(io_get_option('disable_rest_api')){
		//$args['show_in_rest']	= false;//影响古腾堡？
	}

	if(!empty($args['supports']) && is_array($args['supports'])){
		// 屏蔽 Trackback
		if(io_get_option('disable_trackbacks')){
			$args['supports']	= array_diff($args['supports'], ['trackbacks']);
		}

		//禁用日志修订功能
		if(io_get_option('diable_revision')){
			$args['supports']	= array_diff($args['supports'], ['revisions']);
		}
	}

	return $args;
});
# --------------------------------------------------------------------
# 屏蔽Trackbacks
# --------------------------------------------------------------------
if(io_get_option('disable_trackbacks')){
    if(io_get_option('disable_gutenberg') && io_get_option('disable_xml_rpc')){
        //彻底关闭 pingback
        add_filter('xmlrpc_methods',function($methods){
            $methods['pingback.ping'] = '__return_false';
            $methods['pingback.extensions.getPingbacks'] = '__return_false';
            return $methods;
        });
    }

    //禁用 pingbacks, enclosures, trackbacks
    remove_action( 'do_pings', 'do_all_pings', 10 );

    //去掉 _encloseme 和 do_ping 操作。
    remove_action( 'publish_post','_publish_post_hook',5 );

     
	add_action('post_comment_status_meta_box-options', function($post){
		echo "<style type='text/css'>label[for='ping_status']{display:none}</style>";
	}); 
}
# --------------------------------------------------------------------
# 屏蔽 REST API
# --------------------------------------------------------------------
if(io_get_option('disable_rest_api')){
	remove_action('init',			'rest_api_init' );
	remove_action('rest_api_init',	'rest_api_default_filters', 10 );
	remove_action('parse_request',	'rest_api_loaded' );

	add_filter('rest_enabled',		'__return_false');
	add_filter('rest_jsonp_enabled','__return_false');

	// 移除头部 wp-json 标签和 HTTP header 中的 link 
	remove_action('wp_head',			'rest_output_link_wp_head', 10 );
	remove_action('template_redirect',	'rest_output_link_header', 11);

	remove_action('xmlrpc_rsd_apis',	'rest_output_rsd');

	remove_action('auth_cookie_malformed',		'rest_cookie_collect_status');
	remove_action('auth_cookie_expired',		'rest_cookie_collect_status');
	remove_action('auth_cookie_bad_username',	'rest_cookie_collect_status');
	remove_action('auth_cookie_bad_hash',		'rest_cookie_collect_status');
	remove_action('auth_cookie_valid',			'rest_cookie_collect_status');
	remove_filter('rest_authentication_errors',	'rest_cookie_check_errors', 100 );
}
# --------------------------------------------------------------------
# 移除 WP_Head 无关紧要的代码
# --------------------------------------------------------------------
if(io_get_option('remove_head_links')){
    remove_action( 'wp_head', 'wp_generator');					//删除 head 中的 WP 版本号
    foreach (['rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head'] as $action) {
        remove_action( $action, 'the_generator' );
    }
    remove_action( 'wp_head', 'rsd_link' );						//删除 head 中的 RSD LINK
    remove_action( 'wp_head', 'wlwmanifest_link' );				//删除 head 中的 Windows Live Writer 的适配器？
    remove_action( 'wp_head', 'feed_links_extra', 3 );		  	//删除 head 中的 Feed 相关的link
    remove_action( 'wp_head', 'index_rel_link' );				//删除 head 中首页，上级，开始，相连的日志链接
    remove_action( 'wp_head', 'parent_post_rel_link', 10);
    remove_action( 'wp_head', 'start_post_rel_link', 10);
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );	//删除 head 中的 shortlink
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10);	// 删除头部输出 WP RSET API 地址
    remove_action( 'template_redirect',	'wp_shortlink_header', 11);		//禁止短链接 Header 标签。
    remove_action( 'template_redirect',	'rest_output_link_header', 11);	// 禁止输出 Header Link 标签。
} 
# --------------------------------------------------------------------
# 屏蔽后台隐私
# --------------------------------------------------------------------
if(io_get_option('disable_privacy')){

    add_action('admin_menu', function (){
        global $menu, $submenu;

        // 移除设置菜单下的隐私子菜单。
        unset($submenu['options-general.php'][45]);

        // 移除工具彩带下的相关页面
        remove_action( 'admin_menu', '_wp_privacy_hook_requests_page' );

        remove_filter( 'wp_privacy_personal_data_erasure_page', 'wp_privacy_process_personal_data_erasure_page', 10, 5 );
        remove_filter( 'wp_privacy_personal_data_export_page', 'wp_privacy_process_personal_data_export_page', 10, 7 );
        remove_filter( 'wp_privacy_personal_data_export_file', 'wp_privacy_generate_personal_data_export_file', 10 );
        remove_filter( 'wp_privacy_personal_data_erased', '_wp_privacy_send_erasure_fulfillment_notification', 10 );

        // Privacy policy text changes check.
        remove_action( 'admin_init', array( 'WP_Privacy_Policy_Content', 'text_change_check' ), 100 );

        // Show a "postbox" with the text suggestions for a privacy policy.
        remove_action( 'edit_form_after_title', array( 'WP_Privacy_Policy_Content', 'notice' ) );

        // Add the suggested policy text from WordPress.
        remove_action( 'admin_init', array( 'WP_Privacy_Policy_Content', 'add_suggested_content' ), 1 );

        // Update the cached policy info when the policy page is updated.
        remove_action( 'post_updated', array( 'WP_Privacy_Policy_Content', '_policy_page_updated' ) );
    },9);
}
# --------------------------------------------------------------------
# 屏蔽 Emoji
# --------------------------------------------------------------------
if(io_get_option('emoji_switcher')){
	remove_action('admin_print_scripts','print_emoji_detection_script');
	remove_action('admin_print_styles',	'print_emoji_styles');

	remove_action('wp_head',			'print_emoji_detection_script',	7);
	remove_action('wp_print_styles',	'print_emoji_styles');

	remove_action('embed_head',			'print_emoji_detection_script');//

	remove_filter('the_content_feed',	'wp_staticize_emoji');
	remove_filter('comment_text_rss',	'wp_staticize_emoji');
	remove_filter('wp_mail',			'wp_staticize_emoji_for_email');

	add_filter('emoji_svg_url',		'__return_false');//
	add_filter('tiny_mce_plugins',	function($plugins){ //
		return array_diff($plugins, ['wpemoji']); 
	});
}
# --------------------------------------------------------------------
# 屏蔽文章Embed
# --------------------------------------------------------------------
if(io_get_option('disable_post_embed')){  
	
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );

	add_filter( 'embed_oembed_discover', '__return_false' );

	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_filter( 'oembed_response_data',   'get_oembed_response_data_rich',  10, 4 );

	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	add_filter( 'tiny_mce_plugins', function ($plugins){
		return array_diff( $plugins, array( 'wpembed' ) );
	});

	add_filter('query_vars', function ($public_query_vars) {
		return array_diff($public_query_vars, array('embed'));
	});
}
# --------------------------------------------------------------------
# 去掉URL中category
# --------------------------------------------------------------------
if( io_get_option('ioc_category') ) {
    add_action( 'load-themes.php',  'no_category_base_refresh_rules');
    add_action('created_category', 'no_category_base_refresh_rules');
    add_action('edited_category', 'no_category_base_refresh_rules');
    add_action('delete_category', 'no_category_base_refresh_rules');
    function no_category_base_refresh_rules() {
        global $wp_rewrite;
        $wp_rewrite -> flush_rules();
    }
    // 删除类别库
    add_action('init', 'no_category_base_permastruct');
    function no_category_base_permastruct() {
        global $wp_rewrite, $wp_version;
        if (version_compare($wp_version, '3.4', '<')) {
            // For pre-3.4 support
            $wp_rewrite->extra_permastructs['category'][0] = '%category%';
        } else {
            $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
        }
    }
    // 添加自定义类别重写规则
    add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
    function no_category_base_rewrite_rules($category_rewrite) {
        //var_dump($category_rewrite); // 用于调试
        $category_rewrite = array();
        $categories = get_categories(array('hide_empty' => false));
        foreach ($categories as $category) {
            $category_nicename = $category -> slug;
            if ($category -> parent == $category -> cat_ID)// recursive recursion
                $category -> parent = 0;
            elseif ($category -> parent != 0)
                $category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
            $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
            $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
            $category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
        }
        // 重定向来自Old Category Base的支持
        global $wp_rewrite;
        $old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
        $old_category_base = trim($old_category_base, '/');
        $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
        //var_dump($category_rewrite); // 用于调试
        return $category_rewrite;
    }
    // 添加'category_redirect'查询变量
    add_filter('query_vars', 'no_category_base_query_vars');
    function no_category_base_query_vars($public_query_vars) {
        $public_query_vars[] = 'category_redirect';
        return $public_query_vars;
    }
    // 如果设置了'category_redirect'，则重定向
    add_filter('request', 'no_category_base_request');
    function no_category_base_request($query_vars) {
        //print_r($query_vars); // 用于调试
        if (isset($query_vars['category_redirect'])) {
            $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
            status_header(301);
            header("Location: $catlink");
            exit();
        }
        return $query_vars;
    }
}
# --------------------------------------------------------------------
# 禁用 Auto OEmbed
# --------------------------------------------------------------------
if(io_get_option('disable_autoembed')){ 
	remove_filter('the_content',			[$GLOBALS['wp_embed'], 'run_shortcode'], 8);
	remove_filter('widget_text_content',	[$GLOBALS['wp_embed'], 'run_shortcode'], 8);

	remove_filter('the_content',			[$GLOBALS['wp_embed'], 'autoembed'], 8);
	remove_filter('widget_text_content',	[$GLOBALS['wp_embed'], 'autoembed'], 8);

	remove_action('edit_form_advanced',		[$GLOBALS['wp_embed'], 'maybe_run_ajax_cache']);
	remove_action('edit_page_form',			[$GLOBALS['wp_embed'], 'maybe_run_ajax_cache']);
}
# --------------------------------------------------------------------
# Gravatar加速
# --------------------------------------------------------------------
add_filter('pre_get_avatar_data', function($args, $id_or_email){
	$email_hash	= '';
	$user		= $email = false;
	
	if(is_object($id_or_email) && isset($id_or_email->comment_ID)){
		$id_or_email	= get_comment($id_or_email);
	}

	if(is_numeric($id_or_email)){
		$user	= get_user_by('id', absint($id_or_email));
	}elseif($id_or_email instanceof WP_User){	// User Object
		$user	= $id_or_email;
	}elseif($id_or_email instanceof WP_Post){	// Post Object
		$user	= get_user_by('id', intval($id_or_email->post_author));
	}elseif($id_or_email instanceof WP_Comment){	// Comment Object
		if(!empty($id_or_email->user_id)){
			$user	= get_user_by('id', intval($id_or_email->user_id));
		}elseif(!empty($id_or_email->comment_author_email)){
			$email	= $id_or_email->comment_author_email;
		}
	}elseif(is_string($id_or_email)){
		if(strpos($id_or_email, '@md5.gravatar.com')){
			list($email_hash)	= explode('@', $id_or_email);
		} else {
			$email	= $id_or_email;
		}
	}

	if($user){
		$args	= apply_filters('io_default_avatar_data', $args, $user->ID);
		if($args['found_avatar']){
			return $args;
		}else{
			$email = $user->user_email;
		}
	}
	
	if(!$email_hash){
		if($email){
			$email_hash = md5(strtolower(trim($email)));
		}
	}

	if($email_hash){
		$args['found_avatar']	= true;
	}

	if(io_get_option('gravatar') == 'v2ex'){
		$url	= 'http://cdn.v2ex.com/gravatar/'.$email_hash;
	}else{
		$url	= 'http://cn.gravatar.com/avatar/'.$email_hash;
	}

	$url_args	= array_filter([
		's'	=> $args['size'],
		'd'	=> $args['default'],
		'f'	=> $args['force_default'] ? 'y' : false,
		'r'	=> $args['rating'],
	]);

	$url			= add_query_arg(rawurlencode_deep($url_args), set_url_scheme($url, $args['scheme']));
	$args['url']	= apply_filters('get_avatar_url', $url, $id_or_email, $args);

	return $args;

}, 10, 2);
# --------------------------------------------------------------------
# 前台不加载语言包
# --------------------------------------------------------------------
//if(io_get_option('locale')){
//	add_filter('locale', function($locale){ 
//		if(is_admin()){
//			return $locale;
//		}
//		
//		global $io_locale;
//
//		if(!isset($io_locale)){
//			$io_locale	= $locale;	
//		}
//
//		if(in_array('get_language_attributes', wp_list_pluck(debug_backtrace(), 'function'))){
//			return $io_locale;
//		}else{
//			return 'en_US';
//		}
//	});
//}
# --------------------------------------------------------------------
# 移除后台界面右上角的帮助
# --------------------------------------------------------------------
if(io_get_option('remove_help_tabs')){  
	add_action('in_admin_header', function(){
		global $current_screen;
		$current_screen->remove_help_tabs();
	});
}
# --------------------------------------------------------------------
# 移除后台界面右上角的选项
# --------------------------------------------------------------------
if(io_get_option('remove_screen_options')){  
	add_filter('screen_options_show_screen', '__return_false');
	add_filter('hidden_columns', '__return_empty_array');
}
# --------------------------------------------------------------------
# 禁止使用 admin 用户名尝试登录
# --------------------------------------------------------------------
if(io_get_option('no_admin')){
	add_filter( 'wp_authenticate',  function ($user){
		if($user == 'admin') exit;
	});

	add_filter('sanitize_user', function ($username, $raw_username, $strict){
		if($raw_username == 'admin' || $username == 'admin'){
			exit;
		}
		return $username;
	}, 10, 3);
}
# --------------------------------------------------------------------
# 压缩网站源码
# --------------------------------------------------------------------
if(io_get_option('compress_html')){
    add_action('get_header', 'wp_compress_html');
    function wp_compress_html(){
        function wp_compress_html_main ($buffer){
            $initial=strlen($buffer);
            $buffer=explode("<!--wp-compress-html-->", $buffer);
            $count=count ($buffer);
            for ($i = 0; $i <= $count; $i++){
                if (stristr($buffer[$i], '<!--wp-compress-html no compression-->')) {
                    $buffer[$i]=(str_replace("<!--wp-compress-html no compression-->", " ", $buffer[$i]));
                } else {
                    $buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
                    $buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
                    $buffer[$i]=(str_replace("\n", "", $buffer[$i]));
                    $buffer[$i]=(str_replace("\r", "", $buffer[$i]));
                    while (stristr($buffer[$i], '  ')) {
                        $buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
                    }
                }
                $buffer_out.=$buffer[$i];
            }
            $final=strlen($buffer_out);   
            $savings=($initial-$final)/$initial*100;   
            $savings=round($savings, 2);   
            $buffer_out.="\n<!--压缩前的大小: $initial bytes; 压缩后的大小: $final bytes; 节约：$savings% -->";   
        	return $buffer_out;
    	}
    	ob_start("wp_compress_html_main");
    }
    // 代码高亮不启用压缩（不需要可删除）
    add_filter( "the_content", "unCompress");
    function unCompress($content) {
        if(preg_match_all('/(crayon-|<\/pre>)/i', $content, $matches)) {
            $content = '<!--wp-compress-html--><!--wp-compress-html no compression-->'.$content;
            $content.= '<!--wp-compress-html no compression--><!--wp-compress-html-->';
        }
        return $content;
    }
}
# --------------------------------------------------------------------
# 屏蔽默认小工具
# --------------------------------------------------------------------
add_action( 'widgets_init', 'my_unregister_widgets' );   
function my_unregister_widgets() {   
    unregister_widget( 'WP_Widget_Archives' );   
    unregister_widget( 'WP_Widget_Calendar' );   
    unregister_widget( 'WP_Widget_Categories' );   
    unregister_widget( 'WP_Widget_Links' );   
    unregister_widget( 'WP_Widget_Meta' );   
    unregister_widget( 'WP_Widget_Pages' );   
    unregister_widget( 'WP_Widget_Recent_Comments' );     
    unregister_widget( 'WP_Widget_Recent_Posts' );   
    unregister_widget( 'WP_Widget_RSS' );   
    //unregister_widget( 'WP_Widget_Search' );   
    unregister_widget( 'WP_Widget_Tag_Cloud' );   
    unregister_widget( 'WP_Widget_Text' );   
    unregister_widget( 'WP_Nav_Menu_Widget' ); 
	unregister_widget( 'WP_Widget_Media_Audio' );
	unregister_widget( 'WP_Widget_Media_Image' );
	unregister_widget( 'WP_Widget_Media_Gallery' );
	unregister_widget( 'WP_Widget_Media_Video' );  
	//unregister_widget( 'WP_Widget_Custom_HTML' );
}  
# --------------------------------------------------------------------
# 429
# --------------------------------------------------------------------
if($vpc=io_get_option('vpc_ip')){
    $vpc = explode(':',$vpc);
    if(!defined('WP_PROXY_HOST') && !defined('WP_PROXY_PORT')){
        define('WP_PROXY_HOST',$vpc[0]);
        define('WP_PROXY_PORT',$vpc[1]);
    }
}
