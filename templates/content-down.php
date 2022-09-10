<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php 
while( have_posts() ): the_post();                   
$name          = get_the_title();//资源名称  
$version       = get_post_meta(get_the_ID(), '_down_version', true);//当前版本
$info          = htmlspecialchars(get_post_meta(get_the_ID(), '_sites_sescribe', true));//说明与描述
$preview       = get_post_meta(get_the_ID(), '_down_preview', true);//演示地址
$formal        = get_post_meta(get_the_ID(), '_down_formal', true);//官方地址
//准备移除
//$baidu         = get_post_meta(get_the_ID(), '_sites_down', true);//百度网盘
//$baidupassword = get_post_meta(get_the_ID(), '_sites_password', true);//百度网盘密码
//-------

$decompression = get_post_meta(get_the_ID(), '_dec_password', true);//解压密码
$size          = get_post_meta(get_the_ID(), '_down_size', true);//资源大小
$platform      = get_post_meta(get_the_ID(), '_app_platform', true);//资源大小
$down_list     = get_post_meta(get_the_ID(), '_down_url_list', true);//下载列表
$down_screen   = get_post_meta(get_the_ID(), '_sites_screenshot', true); //资源截图
$contentinfo   = get_the_content();

$default_ico = get_theme_file_uri('/images/t.png');
$m_link_url    = get_post_meta(get_the_ID(), '_sites_link', true);  
$imgurl        = get_post_meta_img(get_the_ID(), '_thumbnail', true);
if($imgurl == ''){
    if( $m_link_url != '' || ($sites_type == "sites" && $m_link_url != '') )
        $imgurl = (io_get_option('ico-source')['ico_url'] .format_url($m_link_url) . io_get_option('ico-source')['ico_png']);
    elseif($sites_type == "wechat")
        $imgurl = get_theme_file_uri('/images/qr_ico.png');
    elseif($sites_type == "down")
        $imgurl = get_theme_file_uri('/images/down_ico.png');
    else
        $imgurl = get_theme_file_uri('/images/favicon.png');
}

?>
					
			<div class="row app-content py-5 mb-xl-5 mb-0 mx-xxl-n5">
                <?php get_template_part( 'templates/fx' ); ?>
                <!-- 资源信息 -->
                <div class="col">
                    <div class="d-md-flex mt-n3 mb-5 my-xl-0">
                        <div class="app-ico text-center mr-0 mr-md-2 mb-3 mb-md-0">
                            <?php if(io_get_option('lazyload')): ?>
                            <img class="app-rounded mr-0 mr-md-3 lazy" src="<?php echo $default_ico; ?>" data-src="<?php echo $imgurl ?>" width="128" alt="<?php echo $name  ?>">
                            <?php else: ?>
                            <img class="app-rounded mr-0 mr-md-3" src="<?php echo $imgurl ?>" width="128" alt="<?php echo $name  ?>">
                            <?php endif ?>
                        </div>
                        <div class="app-info">
                            <h3 class="text-center text-md-left mb-0"><?php echo $name  ?>
                            <span class="text-md"><?php echo $version ?></span>
                            </h3>  
                            <p class="text-xs text-center text-md-left my-1"><?php echo $info ?></p>
                            <div class="app-nature text-center text-md-left mb-5 mb-md-4">
                                <span class="badge badge-pill badge-dark mr-1"><i class="iconfont icon-chakan-line mr-2"></i><?php echo function_exists('the_views')? the_views(false) :  '0' ; ?></span>
                            </div>
                            <p class="text-muted   mb-4">
                                <span class="info-term mr-3"><?php _e('更新日期：','i_theme') ?><?php the_time('Y-m-d') ?></span>
                                <span class="info-term mr-3"><?php _e('分类标签：','i_theme') ?><?php the_terms( get_the_ID(), 'favorites','<span class="mr-2">', '<i class="iconfont icon-wailian text-xs"></i></span> <span class="mr-2">', '<i class="iconfont icon-wailian text-xs"></i></span>' ); ?><?php the_terms( get_the_ID(), 'sitetag','<span class="mr-2">', '<i class="iconfont icon-wailian text-xs"></i></span> <span class="mr-2">', '<i class="iconfont icon-wailian text-xs"></i></span>' ); ?></span>
                                <span class="info-term mr-3"><?php _e('平台：','i_theme') ?><?php  if($platform){foreach($platform as $pl){  echo '<i class="iconfont '.$pl.' mr-1"></i>';}}else{echo __('没限制','i_theme');} ?></span>
                            </p>
                            <div class="mb-2 app-button">
                                <?php 
	                            $like_count	= get_like(get_the_ID());
	                            $liked		= isset($_COOKIE['liked_' . get_the_ID()]) ? 'liked' : ''; 
                                ?>
                                <button type="button" class="btn btn-lg px-4 text-lg radius-50 btn-danger custom_btn-d btn_down mr-3 mb-2" data-id="0" data-toggle="modal" data-target="#app-down-modal"><i class="iconfont icon-down mr-2"></i><?php _e('立即下载','i_theme') ?></button> 
                                <button type="button" data-action="post_like" data-id="<?php echo get_the_ID() ?>" class="btn btn-lg px-4 text-lg radius-50 btn-outline-danger custom_btn-outline  mb-2 btn-like <?php echo $liked ?>">
                                    <i class="iconfont icon-like mr-2"></i> <?php _e('赞','i_theme') ?> <span class="like-count "><?php echo $like_count ?></span>
                                </button> 
                            </div> 
                            <p class="mb-0 text-muted text-sm"> 
                                <span class="mr-2"><i class="iconfont icon-zip"></i> <span><?php echo $size?:__('大小未知','i_theme') ?></span></span> 
                                <span class="mr-2"><i class="iconfont icon-qushitubiao"></i> <span class="down-count-text count-a"><?php echo get_post_meta(get_the_ID(), '_down_count', true)?:0 ?></span> <?php _e('人已下载','i_theme') ?></span>
                                <?php 
                                if(!wp_is_mobile()){
                                $width = 150;
                                $qrurl = "<img src='".str_ireplace(array('$size','$url'),array($width,get_permalink($post->ID)),io_get_option('qr_url'))."' width='{$width}'>"; 
                                ?>
                                <span class="mr-2" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="<?php echo $qrurl ?>"><i class="iconfont icon-phone"></i> <?php _e('手机查看','i_theme') ?></span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div> 
                <!-- 资源信息 end -->
                <!-- 截图幻灯片 -->
                <?php if($down_screen) { 
                $down_screen = explode( ',', $down_screen ); ?>
                <div class="col-12 col-xl-5"> 
                    <div class="mx-auto screenshot-carousel rounded-lg">  
                        <div id="carousel" class="carousel slide" data-ride="carousel"> 
                            <div class="carousel-inner" role="listbox"> 
                                <?php for($i=0;$i<count($down_screen);$i++) { 
                                    $screen_img = wp_get_attachment_image_src( $down_screen[$i], 'full' )[0]; ?>
                                <div class="carousel-item <?php echo $i==0?'active':'' ?>"> 
                                    <div class="img_wrapper"> 
                                    <a href="<?php echo $screen_img ?>" class="text-center" data-fancybox="screen" data-caption="<?php echo sprintf( __('%s的使用截图', 'i_theme'), $name ).'['.($i+1).']' ?>"> 
                                    <?php if(io_get_option('lazyload')): ?>
                                    <img src="" data-src="<?php echo $screen_img ?>" class="img-fluid lazy" alt="<?php echo sprintf( __('%s的使用截图', 'i_theme'), $name ).'['.($i+1).']' ?>">
                                    <?php else: ?>
                                    <img src="<?php echo $screen_img ?>" class="img-fluid" alt="<?php echo sprintf( __('%s的使用截图', 'i_theme'), $name ).'['.($i+1).']' ?>">
                                    <?php endif ?>
                                    </a>
                                    </div> 
                                </div> 
                                <?php }?>
                            </div> 
                            <?php if(count($down_screen)>1) { ?>
                            <ol class="carousel-indicators"> 
                                <?php for($i=0;$i<count($down_screen);$i++) {?>
                                <li data-target="#carousel" data-slide-to="<?php echo $i ?>" class="<?php echo $i==0?'active':'' ?>"></li> 
                                <?php }?>
                            </ol> 
                            <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                                <i class="iconfont icon-arrow-l icon-lg" aria-hidden="true"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                                <i class="iconfont icon-arrow-r icon-lg" aria-hidden="true"></i>
                                <span class="sr-only">Next</span>
                            </a>
                            <?php }?>
                        </div> 
                    </div>
                </div> 
                <?php } ?>
                <!-- 截图幻灯片 end -->
			</div>  
			
            <div class="panel site-content card transparent"> 
		    	<div class="card-body p-0">
					<div class="apd-bg">
            	        <?php if(io_get_option('ad_app_s')) echo '<div class="apd apd-right">' . stripslashes( io_get_option('ad_app') ) . '</div>'; ?>
            	    </div> 
            	    <div class="panel-body my-4 ">
    	    	    <?php the_content();?> 
            	    </div>
            	    <?php if($formal_url = get_post_meta(get_the_ID(), '_down_formal', true)) echo ('<div class="text-center"><a href="' . (io_get_option('is_go') ? home_url().'/go/?url='.base64_encode($formal_url) : $formal_url) . '" target="_blank" class="btn btn-lg btn-outline-primary custom_btn-outline  text-lg radius-50 py-3 px-5 my-3">'.__('去官方网站了解更多','i_theme').'</a></div>') ?>
            	    <?php edit_post_link(__('编辑','i_theme'), '<span class="edit-link">', '</span>' ); ?>
            	</div> 
			</div>
			
            <div class="modal fade search-modal" id="app-down-modal">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">  
                        <div class="modal-body down_body"> 
                            <h4 class="h6"><?php _e('下载地址: ','i_theme') ?><?php echo $name ?><?php if($version) { ?> - <span class="app-v"><?php echo $version ?></span><?php } ?></h4>
                            <div class="down_btn_list my-4">
                                <?php if($down_list){ ?>
                                <div class="row">
                                    <div class="col-6 col-md-7">描叙</div>
                                    <div class="col-2 col-md-2" style="white-space: nowrap;">提取码</div>
                                    <div class="col-4 col-md-3 text-right">下载</div>
                                </div>
                                <div class="col-12 line-thead my-3" style="height:1px;background: rgba(136, 136, 136, 0.4);"></div>
								<?php  
									for($i=0;$i<count($down_list);$i++){
                                        echo '<div class="row">';
                                        echo '<div class="col-6 col-md-7">'. ($down_list[$i]['down_btn_info']?:'无') .'</div>';
                                        echo '<div class="col-2 col-md-2" style="white-space: nowrap;">'. ($down_list[$i]['down_btn_tqm']?:'无') .'</div>';
                                        echo '<div class="col-4 col-md-3 text-right"><a class="btn btn-danger custom_btn-d py-0 px-1 mx-auto down_count text-sm" href="'. home_url().'/go/?url='.base64_encode($down_list[$i]['down_btn_url']) .'" target="_blank" data-id="'. get_the_ID() .'" data-action="down_count" data-mmid="down-mm-'.$i.'">'.$down_list[$i]['down_btn_name'].'</a></div>';
                                        if($down_list[$i]['down_btn_tqm']) echo '<input type="text" style="width:1px;position:absolute;height:1px;background:transparent;border:0px solid transparent" name="down-mm-'.$i.'" value="'.$down_list[$i]['down_btn_tqm'].'" id="down-mm-'.$i.'">';
                                        echo '</div>';
                                        echo '<div class="col-12 line-thead my-3" style="height:1px;background: rgba(136, 136, 136, 0.2);"></div>';
                                    }
								}else{
                                    echo '<p class="py-3">'.__('没有添加下载地址，请到文章下方找到官网地址去下载，感谢！','i_theme').'</p>';
                                }
								if($decompression)
									echo '<p class="mt-2">'.__('解压密码：','i_theme').$decompression.'</p>';
								?>
                            </div>
                            <?php if(io_get_option('ad_down_popup_s')) echo '<div class="apd apd-footer d-none d-md-block mb-4">' . stripslashes( io_get_option('ad_down_popup') ) . '</div>'; ?> 
                            <div class="statement p-3"><p></p>
                                <i class="iconfont icon-statement icon-2x mr-2" style="vertical-align: middle;"></i><strong><?php _e('声明：','i_theme') ?></strong>
				                <div class="text-sm mt-2" style="margin-left: 39px;"><?php echo io_get_option('down_statement') ?></div>
				            </div>
                        </div>  
                        <div style="position: absolute;bottom: -40px;width: 100%;text-align: center;"><a href="javascript:" data-dismiss="modal"><i class="iconfont icon-close-circle icon-2x" style="color: #fff;"></i></a></div>
                    </div>
                </div>  
            </div> 
<?php endwhile; ?>
