<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php while( have_posts() ): the_post();?>
            <div class="row site-content py-4 py-md-5 mb-xl-5 mb-0 mx-xxl-n5">
                <?php get_template_part( 'templates/fx' ); ?>
                <!-- 网址信息 -->
	    					<div class="col-12 col-sm-5 col-md-4 col-lg-3">
                                <?php 
                                $m_link_url  = get_post_meta(get_the_ID(), '_sites_link', true);  
                                $imgurl = get_post_meta_img(get_the_ID(), '_thumbnail', true);
                                if($imgurl == ''){
                                    if( $m_link_url != '' || ($sites_type == "sites" && $m_link_url != '') )
                                        $imgurl = (io_get_option('ico-source')['ico_url'] .format_url($m_link_url) . io_get_option('ico-source')['ico_png']);
                                    elseif($sites_type == "wechat")
                                        $imgurl = get_theme_file_uri('/images/qr_ico.png');
                                    else
                                        $imgurl = get_theme_file_uri('/images/favicon.png');
                                }
                                $sitetitle = get_the_title();
                                ?>
                                <div class="siteico">
                                    <div class="blur blur-layer" style="background: transparent url(<?php echo $imgurl ?>) no-repeat center center;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;animation: rotate 30s linear infinite;"></div>
                                    <img class="img-cover" src="<?php echo $imgurl ?>" alt="<?php echo $sitetitle ?>" title="<?php echo $sitetitle ?>">
                                    <?php if($country = get_post_meta(get_the_ID(),'_sites_country', true)) {
                                        echo '<div id="country" class="text-xs custom-piece_c_b country-piece loadcountry"><i class="iconfont icon-globe mr-1"></i>'.$country.'</div>';
                                    }else{
                                        echo '<div id="country" class="text-xs custom-piece_c_b country-piece" style="display:none;"><i class="iconfont icon-loading icon-spin"></i></div>';
                                    }
	                                $like_count	= get_like(get_the_ID());
	                                $liked		= isset($_COOKIE['liked_' . get_the_ID()]) ? 'liked' : ''; 
                                    ?>
                                    <div class="tool-actions text-center mt-md-4">
                                        <a href="javascript:;" post_like data-id="<?php echo get_the_ID() ?>" class=" btn btn-like btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2 <?php echo $liked ?>" data-toggle="tooltip" data-placement="top" title="<?php _e('点赞','i_theme') ?>">
                                            <span class="flex-column text-height-xs">
                                                <i class="icon-lg iconfont icon-like"></i>
                                                <small class="like-count text-xs mt-1"><?php echo $like_count ?></small>
                                            </span>
                                        </a>
                                        <a href="javascript:;" class="btn-share-toggler btn btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2" data-toggle="tooltip" data-placement="top" title="<?php _e('浏览','i_theme') ?>">
                                            <span class="flex-column text-height-xs">
                                                <i class="icon-lg iconfont icon-chakan"></i>
                                                <small class="share-count text-xs mt-1"><?php echo function_exists('the_views')? the_views(false) :  '0' ; ?></small>
                                            </span>
                                        </a> 
                                    </div>
                                </div>
	    					</div>
	    					<div class="col mt-4 mt-sm-0">
	    						<div class="site-body text-sm">
                                    <?php 
                                    $terms = get_the_terms( get_the_ID(), 'favorites' ); 
                                    if( !empty( $terms ) ){
                                        foreach( $terms as $term ){
                                             if($term->parent != 0){
                                                  $parent_category = get_term( $term->parent );
                                                  echo '<a class="btn-cat custom_btn-d mb-2" href="' . esc_url( get_category_link($parent_category->term_id)) . '">' . esc_html($parent_category->name) . '</a>';
                                                  echo '<i class="iconfont icon-arrow-r-m mr-n1 custom-piece_c" style="font-size:50%;color:#f1404b;vertical-align:0.075rem"></i>';
                                                  break;
                                             }
                                        } 
                                    	foreach( $terms as $term ){
                                            $name = $term->name;
                                            $link = esc_url( get_term_link( $term, 'favorites' ) );
                                            echo " <a class='btn-cat custom_btn-d mb-2' href='$link'>".$name."</a>";
                                        }
                                    }  
                                    ?>
                                    <div class="site-name h3 my-3"><?php echo $sitetitle ?></div>
                                    <div class="mt-2">
                                        <?php 
                                        $width = 150;
                                        $m_post_link_url = $m_link_url ?: get_permalink($post->ID);
                                        $qrurl = "<img src='".str_ireplace(array('$size','$url'),array($width,$m_post_link_url),io_get_option('qr_url'))."' width='{$width}'>";
                                        $qrname = __("手机查看",'i_theme');
                                        if(get_post_meta_img(get_the_ID(), '_wechat_qr', true) || $sites_type == 'wechat'){
                                            $m_qrurl = get_post_meta_img(get_the_ID(), '_wechat_qr', true);
                                            if($m_qrurl == "")
                                                $qrurl = '<p>'.__('居然没有添加二维码','i_theme').'</p>';
                                            else 
                                                $qrurl = "<img src='".$m_qrurl."' width='{$width}'>";
                                            $qrname = get_post_meta(get_the_ID(),'_is_min_app', true) ? __("小程序",'i_theme') : __("公众号",'i_theme');
                                        }
                                        ?>
                                        <p class="mb-2"><?php echo mb_strimwidth(htmlspecialchars(get_post_meta(get_the_ID(), '_sites_sescribe', true)), 0, 170,"……");?></p> 
                                        <?php the_terms( get_the_ID(), 'sitetag',__('标签：','i_theme').'<span class="mr-1">', '<i class="iconfont icon-wailian text-ss"></i></span> <span class="mr-1">', '<i class="iconfont icon-wailian text-ss"></i></span>' ); ?>
	    								<div class="site-go mt-3">
                                            <?php if($m_link_url!=""): ?>
                                                <?php security_check($m_link_url); ?>
                                                <span class="site-go-url">
                                                <a style="margin-right: 10px;" href="<?php echo go_to($m_link_url) ?>" title="<?php echo $sitetitle ?>" target="_blank" class="btn btn-arrow"><span><?php _e('链接直达','i_theme') ?><i class="iconfont icon-arrow-r-m"></i></span></a>
                                                </span>
                                            <?php endif; ?>
                                            <a href="javascript:" class="btn btn-arrow qr-img"  data-toggle="tooltip" data-placement="bottom" title="" data-html="true" data-original-title="<?php echo $qrurl ?>"><span><?php echo $qrname ?><i class="iconfont icon-qr-sweep"></i></span></a>
                                        </div>
                                        <?php if($spare_link =get_post_meta(get_the_ID(),'_spare_sites_link', true)) { ?>
                                            <div class="spare-site mb-3"> 
                                            <i class="iconfont icon-url"></i><span class="mr-3"><?php _e('其他站点:','i_theme') ?></span>
                                            <?php for ($i=0;$i<count($spare_link);$i++) { ?>
                                            <a class="mb-2 mr-3" href="<?php echo go_to($spare_link[$i]['spare_url']) ?>" title="<?php echo $spare_link[$i]['spare_note'] ?>" target="_blank" style="white-space:nowrap"><span><?php echo $spare_link[$i]['spare_name'] ?><i class="iconfont icon-wailian"></i></span></a>
                                            <?php } ?> 
                                            </div>
                                        <?php } ?>
                                                
                                        <p id="check_s" class="text-sm" style="display:none"><i class="iconfont icon-loading icon-spin"></i></p> 
	    							</div>

	    						</div>
                            </div>
                <!-- 网址信息 end -->
                            <?php if(io_get_option('ad_right_s')) { ?>
                            <div class="col-12 col-md-12 col-lg-4 mt-4 mt-lg-0">
                                <div class="apd apd-right">
                                    <?php  echo  stripslashes( io_get_option('ad_right') )   ?>
                                </div>
                            </div>
                            <?php } ?>
            </div>
            <div class="panel site-content card transparent"> 
		        <div class="card-body p-0">
					<div class="apd-bg">
            	        <?php if(io_get_option('ad_app_s')) echo '<div class="apd apd-right">' . stripslashes( io_get_option('ad_app') ) . '</div>'; ?>
            	    </div> 
                    <div class="panel-body my-4 ">
                            <?php  
                            $contentinfo = get_the_content();
                            if( $contentinfo ){
                                the_content();   
                            }else{
                                echo htmlspecialchars(get_post_meta(get_the_ID(), '_sites_sescribe', true));
                            }
                            ?>
                    </div>
                        <?php edit_post_link(__('编辑','i_theme'), '<span class="edit-link">', '</span>' ); ?>
                </div>
            </div>
<?php endwhile; ?>
