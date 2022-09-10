<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 文章缩略图或图片处理操作相关
 */

/**
 * 添加特色缩略图支持
 * 如果需要，取消下面注释
 */
if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');

/**
 * 获取特色图地址
 */
function io_theme_get_thumb($post = null){
	if( $post === null ){
    	global $post;
    }
	if( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
		$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
		$post_thumbnail_src = $thumbnail_src [0];
	} else {
		$post_thumbnail_src = '';
		$strResult = io_get_post_first_img(true);
		if(!empty($strResult[1][0])){
			$post_thumbnail_src = $strResult[1][0];   //获取该图片 src
		}else{	
            //如果日志中没有图片，则显示随机图片
            $random_img = explode(PHP_EOL , io_get_option('random_head_img'));
            $random_img_array = array_rand($random_img);
            $post_thumbnail_src = trim($random_img[$random_img_array]);
		}
    }
    return $post_thumbnail_src;
}

/**
 * 获取/输出缩略图地址
 */
function io_get_post_first_img($is_array = false){ 
     
    global $post; 
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $strResult);
    if($is_array)
        return $strResult;
    else{
        if(!empty($strResult[1][0])){
			return $strResult[1][0];  
		}else{	
            return null;
		}
    }
}
    
/**
 * 获取/输出缩略图地址
 */
function io_get_thumbnail($size = 'thumbnail',$isback = false){
    $post_thumbnail_src = io_theme_get_thumb();
    if($isback){
        return getOptimizedImageUrl($post_thumbnail_src, $size,'90');
    }
    if(io_get_option('lazyload')){
        $loadimg_url=get_theme_file_uri('/images/t.png');
        return 'src="'.$loadimg_url.'" data-src="'.getOptimizedImageUrl($post_thumbnail_src, $size,'90').'"';
    } else {
        return 'src="'.getOptimizedImageUrl($post_thumbnail_src, $size,'90').'"';
    }
}

/**
 * 获取Timthumb裁剪的图片链接
 */
function getTimthumbImage($url, $size = 'thumbnail', $q='70', $nohttp = false){
    if($nohttp)
        $timthumb =  get_theme_file_uri('/timthumb.php');
    else
        $timthumb = str_replace(array('https:','http:'),array('',''), get_theme_file_uri()) . '/timthumb.php';
    // 不裁剪Gif，因为生成黑色无效图片
    $imgtype = strtolower(substr($url, strrpos($url, '.')));
    if($imgtype === 'gif') return $url;

    $size = getFormatedSize($size);
    return $timthumb . stripslashes('?src=' . $url . '&q=' . $q . '&w=' . $size['width'] . '&h=' . $size['height'] . '&zc=1');
} 


/**
 * 根据用户设置选择合适的图片链接处理方式(timthumb|cdn)
 */
function getOptimizedImageUrl($url, $size, $q='70', $nohttp = false){
    if (!preg_match('/'. str_replace('/', '\/', get_host(home_url())) .'/i',$url)) {
        //error_log("非法地址".$url.PHP_EOL, 3, "./php_3.log");
        return getTimthumbImage($url, $size, $q, $nohttp);
    }
    else{
        return getTimthumbImage($url, $size, $q, $nohttp);
    }
}


/**
 * 转换尺寸
 */
function getFormatedSize($size){
    if(is_array($size)){
        $width = array_key_exists('width', $size) ? $size['width'] : 225;
        $height = array_key_exists('height', $size) ? $size['height'] : 150;
        $str = array_key_exists('str', $size) ? $size['str'] : 'thumbnail';
    }else{
        switch ($size){
            case 'medium':
                $width = 375;
                $height = 250;
                $str = 'medium';
                break;
            case 'large':
                $width = 960;
                $height = 640;
                $str = 'large';
                break;
            default:
                $width = 225;
                $height = 150;
                $str = 'thumbnail';
        }
    }
    return array(
        'width'   =>  $width,
        'height'  =>  $height,
        'str'     =>  $str
    );
}

/**
 * 获取顶级域名
 * @return [type] 
 * 比如www.iowen.cn返回iowen.cn
 */
function get_host($to_virify_url = ''){
    
    $url   = $to_virify_url ? $to_virify_url : $_SERVER['HTTP_HOST'];
    $data = explode('.', $url);
    $co_ta = count($data);
 
    //判断是否是双后缀
    $zi_tow = true;
    $host_cn = 'com.cn,net.cn,org.cn,gov.cn';
    $host_cn = explode(',', $host_cn);
    foreach($host_cn as $host){
        if(strpos($url,$host)){
            $zi_tow = false;
        }
    }
 
    //如果是返回FALSE ，如果不是返回true
    if($zi_tow == true){
 
        // 是否为当前域名
        if($url == 'localhost'){
            $host = $data[$co_ta-1];
        }
        else{
            $host = $data[$co_ta-2].'.'.$data[$co_ta-1];
        }
        
    }
    else{
        $host = $data[$co_ta-3].'.'.$data[$co_ta-2].'.'.$data[$co_ta-1];
    }
    
    return $host;
}