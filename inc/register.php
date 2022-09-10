<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'VERSION', esc_attr(wp_get_theme()->get('Version')) );	
function theme_load_scripts() {

	if(!$iointo=io_get_option('ico-source')  ){
		if(!isset($iointo['ico_url'])){
			echo "<h1 style='margin: 100px 0;text-align: center;'>请先保存主题设置。。。。。。。。。。<br><span style='font-size:60%'>并且认真查看后台 <b>仪表盘</b> 的使用说明</span></h1>";
		exit;
		}
	}

	$theme_version = esc_attr(wp_get_theme()->get('Version'));
	if ( io_get_option('fa_cdn')) {
		wp_register_style( 'font-awesome',           '//use.fontawesome.com/releases/v5.13.0/css/all.css', array(), VERSION, 'all'  );
		wp_register_style( 'font-awesome4',          '//use.fontawesome.com/releases/v5.13.0/css/v4-shims.css', array(), VERSION, 'all'  );
	}else{
		wp_register_style( 'font-awesome',           get_theme_file_uri('/css/all.min.css'), array(), VERSION, 'all'  );
		wp_register_style( 'font-awesome4',          get_theme_file_uri('/css/v4-shims.min.css'), array(), VERSION, 'all'  );
	}
	wp_register_style( 'iconfont',          		 get_theme_file_uri('/css/iconfont.css'), array(), VERSION, 'all'  );
	wp_register_style( 'bootstrap',         		 get_theme_file_uri('/css/bootstrap.min.css'), array(), VERSION, 'all'  );
	wp_register_style( 'lightbox',             		 get_theme_file_uri('/css/jquery.fancybox.min.css'), array(), VERSION );
	wp_register_style( 'style',             		 get_theme_file_uri('/css/style.css'), array(), VERSION );

	wp_register_script( 'popper',           		 get_theme_file_uri('/js/popper.min.js'), array('jquery'), VERSION, true );
	wp_register_script( 'bootstrap',        		 get_theme_file_uri('/js/bootstrap.min.js'), array('jquery'), VERSION, true );
	wp_register_script( 'comments-ajax',    		 get_theme_file_uri('/js/comments-ajax.js'), array('jquery'), VERSION, true );
	wp_register_script( 'appjs',            		 get_theme_file_uri('/js/app.js'), array('jquery'), VERSION, true );
	wp_register_script( 'lazyload',         		 get_theme_file_uri('/js/lazyload.min.js'), array('jquery'), VERSION, true );
	wp_register_script( 'sidebar',          		 get_theme_file_uri('/js/theia-sticky-sidebar.js'), array('jquery'), VERSION, true );
	wp_register_script( 'lightbox-js',          	 get_theme_file_uri('/js/jquery.fancybox.min.js'), array('jquery'), VERSION, true );

	if( !is_admin() )
    {
		wp_enqueue_style('iconfont');
		if ( io_get_option('is_iconfont')) {
			wp_enqueue_style( 'iconfontd',  io_get_option('iconfont_url'), array(), VERSION );
		}else{
			wp_enqueue_style('font-awesome');
			wp_enqueue_style('font-awesome4');
		}
		wp_enqueue_style('bootstrap');
		wp_enqueue_style('lightbox'); 
		wp_enqueue_style('style'); 

		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', get_theme_file_uri('/js/jquery.min.js'), array(), VERSION ,false);
		wp_enqueue_script('jquery');

		wp_enqueue_script('popper');
		wp_enqueue_script('bootstrap');
		wp_enqueue_script('sidebar'); 
		if(io_get_option('lazyload')) wp_enqueue_script('lazyload'); 

		wp_enqueue_script('lightbox-js'); 
		wp_enqueue_script('appjs'); 
		

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'captcha','https://ssl.captcha.qq.com/TCaptcha.js',[],VERSION,true );
			wp_enqueue_script( 'comment-reply' );
			wp_enqueue_script( 'comments-ajax' );
		}
	}
	wp_localize_script('popper', 'theme' , array(
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
		'addico'       => get_theme_file_uri('/images/add.png'),
		'order'        => get_option('comment_order'),
        'formpostion'  => 'top', //默认为bottom，如果你的表单在顶部则设置为top。
		'defaultclass' => io_get_option('theme_mode')=="io-black-mode"?'':io_get_option('theme_mode'), //默认为bottom，如果你的表单在顶部则设置为top。
		'isCustomize'  => io_get_option('customize_card'),
		'icourl'       => io_get_option('ico-source')['ico_url'],
		'icopng'       => io_get_option('ico-source')['ico_png'],
		'urlformat'    => io_get_option('ico-source')['url_format'],
		'customizemax' => io_get_option('customize_n'),
		'newWindow'    => io_get_option('new_window'),
		'lazyload'     => io_get_option('lazyload'),
		'minNav'       => io_get_option('min_nav'),
		'loading'      => io_get_option('loading_fx'),
	)); 
}
add_action('wp_enqueue_scripts', 'theme_load_scripts');
 

if (io_get_option('disable_gutenberg')) {
	add_editor_style( 'css/editor-style.css' );
}
// 古腾堡编辑器样式
function block_editor_styles() {
	wp_enqueue_style( 'block-editor-style', get_theme_file_uri( '/css/editor-blocks.css' ), array(), VERSION );
}
add_action( 'enqueue_block_editor_assets', 'block_editor_styles' );
 