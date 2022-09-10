<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>

            <?php
            $sites_type = get_post_meta($post->ID, '_sites_type', true);
            if($post->post_type != 'sites')
                $link_url = get_permalink($post->ID);
            $title = $link_url;
            $is_html = '';
            $width = 128;
            $tooltip = 'data-toggle="tooltip" data-placement="bottom"';
            if(get_post_meta_img($post->ID, '_wechat_qr', true)){
                $title="<img src='" . get_post_meta_img(get_the_ID(), '_wechat_qr', true) . "' width='{$width}'>";
                $is_html = 'data-html="true"';
            } else {
                switch(io_get_option('po_prompt')) {
                    case 'null':  
                        $title = get_the_title();
                        $tooltip = '';
                        break;
                    case 'url': 
                        if($link_url==""){
                            if($sites_type == "down")
                                $title = __('下载','i_theme').'“'.get_the_title().'”';
                            elseif ($sites_type == "wechat") 
                                $title = __('居然没有添加二维码','i_theme');
                            else
                                $title = __('没有 url','i_theme');
                        }
                        break;
                    case 'summary':
                        if($sites_type == "down")
                            $title = __('下载','i_theme').'“'.get_the_title().'”';
                        else
                            $title = htmlspecialchars(get_post_meta($post->ID, '_sites_sescribe', true)) ?: preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",get_the_excerpt($post->ID));
                        break;
                    case 'qr':
                        if($link_url==""){
                            if($sites_type == "down")
                                $title = __('下载','i_theme').'“'.get_the_title().'”';
                            elseif ($sites_type == "wechat") 
                                $title = __('居然没有添加二维码','i_theme');
                            else
                                $title = __('没有 url','i_theme');
                        }
                        else{
                            $title = "<img src='" . str_ireplace( array('$size','$url'), array($width,$link_url), io_get_option('qr_url') ). "' width='{$width}' height='{$width}'>";
                            $is_html = 'data-html="true"';
                        }
                        break;
                    default:  
                } 
            } 
            
            $url = '';
            $blank = new_window() ;
            $is_views = '';
            if($sites_type == "sites" && get_post_meta($post->ID, '_goto', true)){
                $is_views = 'is-views';
                $blank = 'target="_blank"' ;
                $url = $link_url;
            }else{
            if(io_get_option('details_page')){
                $url=get_permalink();
            }else{ 
                if($sites_type && $sites_type != "sites"){
                    $url=get_permalink();
                }
                elseif($link_url==""){
                    $url = 'javascript:';
                    $blank = '';
                }else{
                    $is_views = 'is-views';
                    $blank = 'target="_blank"' ;
                    $url = go_to($link_url);
                }
            }
            }
            if( !io_get_option('no_ico') ){
                if($post->post_type != 'sites')
                    $ico =  io_theme_get_thumb();
                else
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
            }
            ?>
        <div class="url-body default">    
            <a href="<?php echo $url ?>" <?php echo ($sites_type == "sites" && get_post_meta($post->ID, '_goto', true))?'':nofollow($link_url, io_get_option('details_page')) ?> <?php echo $blank ?> data-id="<?php echo $post->ID ?>" data-url="<?php echo rtrim($link_url,"/") ?>" class="card no-c <?php echo $is_views ?> mb-4 site-<?php echo $post->ID ?>" <?php echo $tooltip . ' ' . $is_html ?> title="<?php echo $title ?>">
                <div class="card-body">
                <div class="url-content d-flex align-items-center">
                    <?php if(!io_get_option('no_ico')) : ?>
                    <div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
                        <?php if(io_get_option('lazyload')): ?>
                        <img class="lazy" src="<?php echo $default_ico; ?>" data-src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'">
                        <?php else: ?>
                        <img class="" src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'">
                        <?php endif ?>
                    </div>
                    <?php endif; ?>
                    <div class="url-info flex-fill">
                        <div class="text-sm overflowClip_1">
                        <?php show_sticky_tag( is_sticky() ) . show_new_tag(get_the_time('Y-m-d H:i:s')) ?><strong><?php the_title() ?></strong>
                        </div>
                        <p class="overflowClip_1 m-0 text-muted text-xs"><?php echo htmlspecialchars(get_post_meta($post->ID, '_sites_sescribe', true)) ?: preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",get_the_excerpt($post->ID)); ?></p>
                    </div>
                </div>
                </div>
            </a> 
            <?php if( $link_url!="" && io_get_option("togo") && io_get_option("details_page") ) { ?>
            <a href="<?php echo ($sites_type == "sites" && get_post_meta($post->ID, '_goto', true))?$link_url:go_to($link_url) ?>" class="togo text-center text-muted is-views" target="_blank" data-id="<?php echo $post->ID ?>" data-toggle="tooltip" data-placement="right" title="<?php _e('直达','i_theme') ?>" <?php echo ($sites_type == "sites" && get_post_meta($post->ID, '_goto', true))?'':nofollow($link_url) ?>><i class="iconfont icon-goto"></i></a>
            <?php } ?>
        </div>
              