<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

//图片上传
add_action('wp_ajax_nopriv_img_upload', 'io_img_upload');  
add_action('wp_ajax_img_upload', 'io_img_upload');
function io_img_upload(){  
	if (!wp_verify_nonce($_POST['_ajax_nonce'],"upload_img")){
		error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
	}
	$extArr = array("jpg", "png", "jpeg");
	$file = $_FILES['files'];
	if ( !empty( $file ) ) {
		$wp_upload_dir = wp_upload_dir();                                     // 获取上传目录信息
    	$basename = $file['name'];
    	$basesize = $file['size'];
    	$baseext = pathinfo($basename, PATHINFO_EXTENSION);
    
    	if (!in_array($baseext, $extArr)) { 
    	    echo '{"status":3,"msg":"'.__('图片类型只能是jpeg,jpg,png！','i_theme').'"}'; 
    	    exit();
    	}  
    	if ($basesize > (64 * 1024)) { 
    	    echo '{"status":3,"msg":"'.__('图片大小不能超过64kb','i_theme').'"}'; 
    	    exit();
		} 
		
		if( io_get_option('io_captcha')['tcaptcha_007'] ) {
		$result = validate_ticket($_POST['tcaptcha_ticket'],$_POST['tcaptcha_randstr']);
		if (!$result['result']) { 
			error('{"status":4,"msg":"'. $result['message'] .'"}');
		}
		}
       
	    $dataname = date("YmdHis_").substr(md5(time()), 0, 8) . '.' . $baseext;
	    $filename = $wp_upload_dir['path'] . '/' . $dataname;
	    rename( $file['tmp_name'], $filename );                               // 将上传的图片文件移动到上传目录
	    $attachment = array(
	        'guid'           => $wp_upload_dir['url'] . '/' . $dataname,      // 外部链接的 url
	        'post_mime_type' => $file['type'],                                // 文件 mime 类型
	        'post_title'     => preg_replace( '/\.[^.]+$/', '', $basename ),  // 附件标题，采用去除扩展名之后的文件名
	        'post_content'   => '',                                           // 文章内容，留空
	        'post_status'    => 'inherit'
	    );
	    $attach_id = wp_insert_attachment( $attachment, $filename );          // 插入附件信息
	    if($attach_id != 0){
	        require_once( ABSPATH . 'wp-admin/includes/image.php' );          // 确保包含此文件，因为wp_generate_attachment_metadata（）依赖于此文件。
	        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	        wp_update_attachment_metadata( $attach_id, $attach_data );        // 生成附件的元数据，并更新数据库记录。
	        print_r(json_encode(array('status'=>1,'msg'=>__('图片添加成功','i_theme'),'data'=>array('id'=>$attach_id,'src'=>wp_get_attachment_url( $attach_id ),'title'=>$basename))));
	        exit();
	    }else{
	        echo '{"status":4,"msg":"'.__('图片上传失败！','i_theme').'"}';
	        exit();
	    }
	} 
}

//删除图片
add_action('wp_ajax_nopriv_img_remove', 'io_img_remove');  
add_action('wp_ajax_img_remove', 'io_img_remove');
function io_img_remove(){    
	if (!wp_verify_nonce($_POST['_ajax_nonce'],"remove_img")){
		error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
	}
	$attach_id = sanitize_key($_POST["id"]);
	if( empty($attach_id) ){
		echo '{"status":3,"msg":"'.__('没有上传图像！','i_theme').'"}';
		exit;
	}

	if($attach_id <= 0)
		return;

	if ( false === wp_delete_attachment( $attach_id ) )
		echo '{"status":4,"msg":"'.__('图片','i_theme').' '.$attach_id.' '.__('删除失败！','i_theme').'"}';
	else
		echo '{"status":1,"msg":"'.__('删除成功！','i_theme').'"}';
	exit; 
}


// 接收前端ajax参数
add_action('wp_ajax_title_checks', 'duplicate_title_checks_callback');
	function duplicate_title_checks_callback(){
	global $wpdb;           
	$title = $_POST['post_title'];
	$post_id = $_POST['post_id'];
	$titles = "SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'
	AND post_title = '{$title}' AND ID != {$post_id} ";
	$results = $wpdb->get_results($titles);
	if($results) {
	echo "<span style='color:red'>". _( '此标题已存在，请换一个标题！' , '' ) ." </span>";
	} else {
	echo '<span style="color:green">'._('恭喜，此标题未与其他文章标题重复！' , '').'</span>';
	}
	die();
}


//提交文章
add_action('wp_ajax_nopriv_contribute_post', 'io_contribute');  
add_action('wp_ajax_contribute_post', 'io_contribute');
function io_contribute(){  
	if (!wp_verify_nonce($_POST['_ajax_nonce'],"tougao_robot")){
		error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
	}
	$delay = 60; 
	if( isset($_COOKIE["tougao"]) && ( time() - $_COOKIE["tougao"] ) < $delay ){
		error('{"status":2,"msg":"'.sprintf( __('您投稿也太勤快了吧，“%s”秒后再试！', 'i_theme'), $delay - ( time() - $_COOKIE["tougao"] ) ).'"}');
	} 
	  
	//表单变量初始化
	$sites_type         = isset( $_POST['tougao_type'] ) ? trim(htmlspecialchars($_POST['tougao_type'])) : '';
	$sites_link         = isset( $_POST['tougao_sites_link'] ) ? trim(htmlspecialchars($_POST['tougao_sites_link'])) : '';
	$sites_sescribe     = isset( $_POST['tougao_sites_sescribe'] ) ? trim(htmlspecialchars($_POST['tougao_sites_sescribe'])) : '';
	$title              = isset( $_POST['tougao_title'] ) ? trim(htmlspecialchars($_POST['tougao_title'])) : '';
	$category           = isset( $_POST['tougao_cat'] ) ? sanitize_key($_POST['tougao_cat']) : '0';
	$sites_ico          = isset( $_POST['tougao_sites_ico'] ) ? trim(htmlspecialchars($_POST['tougao_sites_ico'])) : '';
	$wechat_qr          = isset( $_POST['tougao_wechat_qr'] ) ? trim(htmlspecialchars($_POST['tougao_wechat_qr'])) : '';
	$content            = isset( $_POST['tougao_content'] ) ? trim(htmlspecialchars($_POST['tougao_content'])) : '';
	$keywords           = isset( $_POST['tougao_sites_keywords'] ) ? trim(htmlspecialchars($_POST['tougao_sites_keywords'])) : '';
	$publish            = isset( $_POST['is_publish'] ) ? $_POST['is_publish'] : '0';

	
	$down_version       = isset( $_POST['tougao_down_version'] ) ? trim(htmlspecialchars($_POST['tougao_down_version'])) : '';//资源版本
	$down_formal        = isset( $_POST['tougao_down_formal'] ) ? trim(htmlspecialchars($_POST['tougao_down_formal'])) : '';//官网链接
	$sites_down         = isset( $_POST['tougao_sites_down'] ) ? trim(htmlspecialchars($_POST['tougao_sites_down'])) : '';//网盘链接
	$down_preview       = isset( $_POST['tougao_down_preview'] ) ? trim(htmlspecialchars($_POST['tougao_down_preview'])) : '';//演示链接
	$sites_password     = isset( $_POST['tougao_sites_password'] ) ? trim(htmlspecialchars($_POST['tougao_sites_password'])) : '';//网盘密码
	$down_decompression = isset( $_POST['tougao_down_decompression'] ) ? trim(htmlspecialchars($_POST['tougao_down_decompression'])) : '';//解压密码

	$typename = __('网站','i_theme');
	if( $sites_type == 'down' )
	$typename = __('资源','i_theme');
	if( $sites_type == 'wechat' )
	$typename = __('公众号','i_theme');

	$post_status = 'pending';
	if($publish != '0'){
		if(io_get_option('tougao_category'))
			$category = io_get_option('tougao_category');
		$post_status = 'publish';
	}

	// 表单项数据验证
	if ( empty($title) || mb_strlen($title) > 30 ) {
		error('{"status":4,"msg":"'.$typename.__('名称必须填写，且长度不得超过30字。','i_theme').'"}');
	}
	global $wpdb; 
	$titles = "SELECT post_title FROM $wpdb->posts WHERE post_status IN ('pending','publish') AND post_type = 'sites' AND post_title = '{$title}'";
	if($wpdb->get_results($titles)) {
		error('{"status":4,"msg":"'.__('存在相同的名称，请不要重复提交哦！','i_theme').'"}');
	}

	if ( $sites_type=='sites' && empty($sites_link) || (!empty($sites_link) && !preg_match("/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/", $sites_link))){
		error('{"status":3,"msg":"'.$typename.__('链接必须填写，且必须符合URL格式。','i_theme').'"}');
	}
	$meta_value = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_value = '{$sites_link}' AND meta_key='_sites_link'";
	if($wpdb->get_results($meta_value)) {
		error('{"status":4,"msg":"'.__('存在相同的链接地址，请不要重复提交哦！','i_theme').'"}');
	}

	if ( empty($sites_sescribe) || mb_strlen($sites_sescribe) > 50 ) {
		error('{"status":4,"msg":"'.$typename.__('描叙必须填写，且长度不得超过50字。','i_theme').'"}');
	}
	if ( $category == "0" ){
		error('{"status":4,"msg":"'.__('请选择分类。','i_theme').'"}');
	}
	if ( !empty(get_term_children($category, 'favorites'))){
		error('{"status":4,"msg":"'.__('不能选用父级分类目录。','i_theme').'"}');
	}
	if ( $sites_type=='wechat' && empty($wechat_qr)) {
		error('{"status":4,"msg":"'.__('必须添加二维码。','i_theme').'"}');
	}
	//if ( empty($content) || mb_strlen($content) > 10000 || mb_strlen($content) < 6) {
	//	error('{"status":4,"msg":"内容必须填写，且长度不得超过10000字，不得少于6字。"}');
	//}
	if( $sites_type == 'down'){
		if ( empty($down_formal) && empty($sites_down) ) {
			error('{"status":4,"msg":"'.__('“官网地址”和“网盘地址”怎么地也待填一个把。','i_theme').'"}');
		}
	}
	//if(!empty($sites_ico)){
	//	$sites_ico = array(
	//		'url'       => $sites_ico,  
	//		'thumbnail' => $sites_ico, 
	//	);
	//}
	//if(!empty($wechat_qr)){
	//	$wechat_qr = array(
	//		'url'       => $wechat_qr,  
	//		'thumbnail' => $wechat_qr, 
	//	);
	//}

	if( io_get_option('io_captcha')['tcaptcha_007'] ) {
		$result = validate_ticket($_POST['tcaptcha_ticket'],$_POST['tcaptcha_randstr']);
		if (!$result['result']) { 
			error('{"status":4,"msg":"'. $result['message'] .'"}');
		}
	}
	
	$down_list = array();
	if(!empty($sites_down)){ 
            $down_list['down_btn_name'] = '网盘下载';
            $down_list['down_btn_url'] = $sites_down;
            $down_list['down_btn_tqm'] = $sites_password;
            $down_list['down_btn_info'] = '';
	}
	if(!empty($keywords)) {
		$keywords = '<span style="color:red">&lt;删除&gt;</span><h1>剪切下方关键字到标签：</h1>'.PHP_EOL. $keywords.PHP_EOL.'<h1>正文：</h1><span style="color:red">&lt;/删除&gt;</span>'.PHP_EOL;
	}
	$tougao = array(
		'comment_status'   => 'closed',
		'ping_status'      => 'closed',
		//'post_author'      => 1,//用于投稿的用户ID
		'post_title'       => $title,
		'post_content'     => $keywords . $content,
		'post_status'      => $post_status,
		'post_type'        => 'sites',
		//'tax_input'        => array( 'favorites' => array($category) ) //游客不可用
	);
	
	// 将文章插入数据库
	$status = wp_insert_post( $tougao );
	if ($status != 0){
		global $wpdb;
		add_post_meta($status, '_sites_type', $sites_type);
		add_post_meta($status, '_sites_sescribe', $sites_sescribe);
		add_post_meta($status, '_sites_link', $sites_link);
		add_post_meta($status, '_down_version', $down_version);
		add_post_meta($status, '_down_formal', $down_formal);
		//add_post_meta($status, '_sites_down', $sites_down);
		add_post_meta($status, '_down_preview', $down_preview);
		//add_post_meta($status, '_sites_password', $sites_password);
		add_post_meta($status, '_down_url_list', array($down_list));//----
		add_post_meta($status, '_dec_password', $down_decompression);
		add_post_meta($status, '_sites_order', '0');
		if( !empty($sites_ico))
			add_post_meta($status, '_thumbnail', $sites_ico); 
		if( !empty($wechat_qr))
			add_post_meta($status, '_wechat_qr', $wechat_qr); 
		wp_set_post_terms( $status, array($category), 'favorites'); //设置文章分类
		//if(!empty($keywords)) wp_set_post_terms( $status, explode(',', $keywords), 'sitetag'); //设置文章tag
		setcookie("tougao", time(), time()+$delay+10);
		error('{"status":1,"msg":"'.__('投稿成功！','i_theme').'"}');
	}else{
		error('{"status":4,"msg":"'.__('投稿失败！','i_theme').'"}');
	}
}
 

//提交评论
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');
if(!function_exists('fa_ajax_comment_callback')){
function fa_ajax_comment_callback(){
	if (!wp_verify_nonce($_POST['_wpnonce'],"comment_ticket")){
		error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}',true);
	}
	if( io_get_option('io_captcha')['tcaptcha_007'] && io_get_option('io_captcha')['comment_007'] ) {
		$result = validate_ticket( $_POST['tcaptcha_ticket'], $_POST['tcaptcha_randstr'] );
		if (!$result['result']) { 
			error('{"status":4,"msg":"'. $result['message'] .'"}', true);
		}
	}
    $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
    if ( is_wp_error( $comment ) ) {
        $data = $comment->get_error_data();
        if ( ! empty( $data ) ) {
			error('{"status":4,"msg":"'.$comment->get_error_message().'"}', true);
        } else {
            exit;
        }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
    ?> 
	<li <?php comment_class('comment'); ?> id="li-comment-<?php comment_ID() ?>" style="position: relative;">
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
				</div>
			</section>
		</div>
		<div class="new-comment" style="background: #4bbbff;position: absolute;top: -1rem;bottom: 1rem;left: -1.25rem;right: -1.25rem;opacity: .2;"></div>
		</li>	
    <?php die();
}
}


// 查重
add_action('wp_ajax_nopriv_check_duplicate', 'io_check_duplicate');  
add_action('wp_ajax_check_duplicate', 'io_check_duplicate');
function io_check_duplicate(){ 
	global $wpdb;  

	$sites_link = isset( $_POST['sites_link'] ) ? trim(htmlspecialchars($_POST['sites_link'])) : '';
	$sites_link = rtrim($sites_link, '/');
	if(!empty($sites_link)){
		
		$meta_value = "SELECT meta_value FROM $wpdb->postmeta WHERE ( meta_value = '{$sites_link}' OR meta_value = '{$sites_link}/' ) AND meta_key='_sites_link'";
		if($wpdb->get_results($meta_value)) {
			echo __('存在相同的链接地址，请不要重复提交哦！','i_theme') ;
		}
		else{
			echo __('没有重复地址，可以提交！','i_theme') ;
		}  
	} else {
		echo __('请填写地址！','i_theme') ;
	}
	exit;  
}

//点赞
add_action('wp_ajax_nopriv_post_like', 'io_like_ajax_handler');  
add_action('wp_ajax_post_like', 'io_like_ajax_handler');
function io_like_ajax_handler(){
	global $wpdb, $post;  
	if($post_id = sanitize_key($_POST["post_id"])){
		
		if($post_id <= 0)
			return;

		$like_count = get_post_meta($post_id, '_like_count', true);  

		$expire = time() + 99999999;  
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost  

		setcookie('liked_' . $post_id, $post_id, $expire, '/', $domain, false);  
		if (!$like_count || !is_numeric($like_count)){
			update_post_meta($post_id, '_like_count', 1);
		}else{
			update_post_meta($post_id, '_like_count', ($like_count + 1));
		}

		echo get_post_meta($post_id, '_like_count', true); 
	}
	exit;  
}
//设置链接失败
add_action('wp_ajax_nopriv_link_failed', 'io_link_failed');  
add_action('wp_ajax_link_failed', 'io_link_failed');
function io_link_failed(){  
	global $wpdb, $post;  
	if($post_id = (int) sanitize_key( $_POST["post_id"]) ){
		$is_inv = $_POST["is_inv"];
		if( $post_id > 0 ){
			$invalid_count = get_post_meta($post_id, 'invalid', true);  
			if( $is_inv=="false" ){
				if ( !$invalid_count || !is_numeric($invalid_count) ){
					update_post_meta($post_id, 'invalid', 1);
				}else{
					update_post_meta($post_id, 'invalid', ($invalid_count + 1));
				}
			} else {
				if ( ($invalid_count || is_numeric($invalid_count)) && $invalid_count > 0){ 
					update_post_meta($post_id, 'invalid', ($invalid_count - 1));
				}
			}
			echo "反馈成功 ".$is_inv; 
		}
	}
	exit;  
}

// 增加文章浏览统计
add_action( 'wp_ajax_io_postviews', 'io_n_increment_views' );
add_action( 'wp_ajax_nopriv_io_postviews', 'io_n_increment_views' );
function io_n_increment_views() {
	if( empty( $_GET['postviews_id'] ) )
		return;
 
	$post_id =  (int) sanitize_key( $_GET['postviews_id'] );
	if( $post_id > 0 ) {
		$views_count = get_post_meta($post_id, 'views', true);  
		if (!$views_count || !is_numeric($views_count)){
			$views_count = 0;
		}
		update_post_meta($post_id, 'views', ($views_count + 1));
		echo $views_count+1;
		exit();
	}
}

// 增加国家数据，临时方法
add_action( 'wp_ajax_io_set_country', 'io_set_country' );
add_action( 'wp_ajax_nopriv_io_set_country', 'io_set_country' );
function io_set_country() {
	if( empty( $_POST['id'] ) )
		return;
	$country = $_POST['country'];
	$post_id =  (int) sanitize_key( $_POST['id'] );
	if( $post_id > 0 ) { 
		update_post_meta($post_id, '_sites_country', $country); 
		exit();
	}
}
//显示模式切换
add_action('wp_ajax_nopriv_switch_dark_mode', 'io_switch_dark_mode');  
add_action('wp_ajax_switch_dark_mode', 'io_switch_dark_mode');
function io_switch_dark_mode(){    
	$mode = $_POST["mode_toggle"];
	$expire = time() + 99999999;  
	$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost  
	setcookie('night_mode', $mode, $expire, '/', $domain, false);  
	exit; 
}

// 增加app下载量
add_action( 'wp_ajax_down_count', 'io_add_down_count' );
add_action( 'wp_ajax_nopriv_down_count', 'io_add_down_count' );
function io_add_down_count() {
	if( empty( $_POST['id'] ) )
		return;
 
	$post_id =  (int) sanitize_key( $_POST['id'] );
	if( $post_id > 0 ) {
		$down_count = get_post_meta($post_id, '_down_count', true);  
		if (!$down_count || !is_numeric($down_count)){
			$down_count = 0;
		}
		update_post_meta($post_id, '_down_count', ($down_count + 1));
		echo $down_count+1;
		exit();
	}
}

// 加载热门网址   
add_action( 'wp_ajax_load_hot_sites' , 'load_hot_sites' );
add_action( 'wp_ajax_nopriv_load_hot_sites' , 'load_hot_sites' );
function load_hot_sites(){

    $meta_key = sanitize_text_field($_POST['type']); 
     
    global $post;
    $site_n = io_get_option('hot_n');
    $args = array(
        'post_type'           => 'sites',  
		'post_status'         => array( 'publish', 'private' ),//'publish',
		'perm'                => 'readable',
        'ignore_sticky_posts' => 1,   
        'posts_per_page'      => $site_n,       
	);
	if($meta_key == 'date'){
		$args['orderby'] = 'date';
	}else{
		$args['meta_key'] = $meta_key;
		$args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
	}
    $myposts = new WP_Query( $args );
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有数据！请开启统计并等待产生数据','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
        $link_url = get_post_meta($post->ID, '_sites_link', true); 
        $default_ico = get_theme_file_uri('/images/favicon.png');
        //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)):
    ?>
		<?php if(io_get_option("hot_card_mini")) {?>
        	<div class="url-card ajax-url col-6 <?php get_columns() ?> col-xxl-10a <?php echo before_class($post->ID) ?>">
            <?php include( get_theme_file_path('/templates/card-sitemini.php')  ); ?>
		<?php }else{?>
        	<div class="url-card <?php echo io_get_option('two_columns')?"col-6":"" ?> ajax-url <?php get_columns() ?> <?php echo before_class($post->ID) ?>">
            <?php include( get_theme_file_path('/templates/card-site.php')  );?>
		<?php }?>
        </div>
	<?php //endif; 
	endwhile; endif; wp_reset_postdata();  
	
    die();
}
// 加载热门app
add_action( 'wp_ajax_load_hot_app' , 'load_hot_app' );
add_action( 'wp_ajax_nopriv_load_hot_app' , 'load_hot_app' );
function load_hot_app(){

    $meta_key = sanitize_text_field($_POST['type']); 
     
    global $post;
    $site_n = io_get_option('hot_n');
    $args = array(
        'post_type'           => 'app', 
        'post_status'         => array( 'publish', 'private' ),//'publish',
		'perm'                => 'readable',
        'ignore_sticky_posts' => 1,              
        'posts_per_page'      => $site_n,       
	);
	if($meta_key == 'date'){
		$args['orderby'] = 'date';
	}else{
		$args['meta_key'] = $meta_key;
		$args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
	}
    $myposts = new WP_Query( $args );
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有数据！请开启统计并等待产生数据','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post();   
    ?> 
		<div class="col-12 col-md-6 col-lg-4 col-xxl-5a ajax-url">
		<?php
             
            include( get_theme_file_path('/templates/card-appcard.php') ); 
             
            ?>
        </div>
	<?php  endwhile; endif; wp_reset_postdata();  
	
    die();
}
// 加载热门书籍
add_action( 'wp_ajax_load_hot_book' , 'load_hot_book' );
add_action( 'wp_ajax_nopriv_load_hot_book' , 'load_hot_book' );
function load_hot_book(){

    $meta_key = sanitize_text_field($_POST['type']); 
     
    global $post;
    $site_n = io_get_option('hot_n');
    $args = array(
        'post_type'           => 'book', 
        'post_status'         => array( 'publish', 'private' ),//'publish',  
		'perm'                => 'readable',
        'ignore_sticky_posts' => 1,              
        'posts_per_page'      => $site_n,       
	);
	if($meta_key == 'date'){
		$args['orderby'] = 'date';
	}else{
		$args['meta_key'] = $meta_key;
		$args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
	}
    $myposts = new WP_Query( $args );
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有数据！请开启统计并等待产生数据','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post();   
    ?> 
		<div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xxl-8a ajax-url">
		<?php
             
            include( get_theme_file_path('/templates/card-book.php') ); 
             
            ?>
        </div>
	<?php  endwhile; endif; wp_reset_postdata();  
	
    die();
}

// 首页TAB模式ajax加载内容     
add_action( 'wp_ajax_load_home_tab' , 'load_home_tab_post' );
add_action( 'wp_ajax_nopriv_load_home_tab' , 'load_home_tab_post' );
function load_home_tab_post(){

    $meta_id   = sanitize_key($_POST['id']); 
	$taxonomy  = $_POST['taxonomy'];
	
    $quantity = io_get_option('card_n'); 
    global $post;
	$link = "";
	$site_n           = $quantity[$taxonomy];
	$category_count   = get_term_by( 'id', $meta_id, $taxonomy )->count;//get_category( (int)$meta_id )->count;
	$count            = $site_n;
	if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
	if($site_n >= 0 && $count < $category_count){
		$link = get_category_link( $meta_id );
		//$link = esc_url( get_term_link( $_mid, 'res_category' ) );
	}
	show_card($site_n,$meta_id,$taxonomy,'ajax-url');
	if($link != "") {?>
		<div id="ajax-cat-url" data-url="<?php echo $link ?>"></div>
	<?php } 
	die();
}


function error($ErrMsg, $err=false) {
	if($err){
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/json;charset=UTF-8');
	}
	echo $ErrMsg;
	exit;
} 

/**
 * 请求服务器验证
 */
function validate_ticket($Ticket,$Randstr){
    $AppSecretKey = io_get_option('io_captcha')['appsecretkey_007'];  
    $appid = io_get_option('io_captcha')['appid_007'];  
    $UserIP = $_SERVER["REMOTE_ADDR"]; 

    $url = "https://ssl.captcha.qq.com/ticket/verify";
    $params = array(
        "aid" => $appid,
        "AppSecretKey" => $AppSecretKey,
        "Ticket" => $Ticket,
        "Randstr" => $Randstr,
        "UserIP" => $UserIP
    );
    $paramstring = http_build_query($params);
    $content = txcurl($url,$paramstring);
    $result = json_decode($content,true);
    if($result){
        if($result['response'] == 1){
            return array(
                'result'=>1,
                'message'  => ''
            );
        }else{
            return array(
                'result'=>0,
                'message'  => $result['err_msg']
            );
        }
    }else{
        return array(
            'result'=>0,
            'message'  => '请求失败,请再试一次！'
        );
    }
}

/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
*/
function txcurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}
