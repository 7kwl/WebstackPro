<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if(io_get_option('customize_card')){ ?>
        <div class="d-flex flex-fill customize-menu">
            <div class='slider_menu mini_tab' sliderTab="sliderTab" >
                <ul class="nav nav-pills menu" role="tablist"> 
                    <li class="pagenumber nav-item">
                        <a class="nav-link active"  data-toggle="pill" href="#my-nav"><?php _e('我的导航', 'i_theme' ); ?></a>
                    </li><li class="pagenumber nav-item">
                        <a class="nav-link"  data-toggle="pill" href="#my-click"><?php _e('最近点击', 'i_theme' ); ?></a>
                    </li>
                </ul>
            </div> 
            <div class="flex-fill"></div>
            <a class='btn-edit text-xs' href="javascript:;"><?php _e('编辑网址', 'i_theme' ); ?></a>
        </div>
        <div class="tab-content mt-4">
            <div id="my-nav" class="tab-pane active">                    
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?>">
                <?php   

                global $post; 
                $args = array(
                    'post_type'           => 'sites',  
                    'ignore_sticky_posts' => 1,          
                    'post__in'            => explode(',', io_get_option('customize_d_n')),    
                    'orderby'             => 'post__in',     
                );
                $myposts = new WP_Query( $args );
                if(!$myposts->have_posts()): ?>
                    <div class="col-lg-12 customize_nothing">
                        <div class="nothing mb-4"><?php _e('没有数据！点右上角编辑添加网址', 'i_theme' ); ?></div>
                    </div>
                    <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a" id="add-site" style="display: none">
                        <a type="button" class="rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                            <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                                +
                            </div>
                        </a>
                    </div> 
                <?php
                elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
                    $link_url = get_post_meta($post->ID, '_sites_link', true); 
                    $default_ico = get_theme_file_uri('/images/favicon.png');
                ?>
                    <div class="url-card col-6 <?php get_columns() ?> <?php echo before_class($post->ID) ?> col-xxl-10a">
                    <?php include( get_theme_file_path('/templates/card-sitemini.php') ); ?>
                    </div>
                <?php endwhile; ?>
                    
                    <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a" id="add-site" style="display: none">
                        <a type="button" class="rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                            <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                                +
                            </div>
                        </a>
                    </div> 
                 <?php endif; wp_reset_postdata(); ?>
                </div> 
            </div> 
            <div id="my-click" class="tab-pane">            
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?> my-click-list">   
                    <div class="col-lg-12 customize_nothing_click">
                        <div class="nothing mb-4"><?php _e('没有数据！等待你的参与哦 ^_^', 'i_theme' ); ?></div>
                    </div>  
                </div> 
            </div>  
        </div>
    <!-- 模态框 -->
    <div class="modal fade" id="addSite">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">  
                <div class="modal-body">
                    <div class="modal-title text-center text-lg mb-4"><?php _e('添加自定义网址', 'i_theme' ); ?></div>
                    <form class="add-link-form" action="javascript:;">
                        <input type="text" class="site-add-name form-control mb-2" placeholder="<?php _e('网站名称', 'i_theme' ); ?>" required="required">
                        <input type="url" class="site-add-url form-control mb-2" value="http://" required="required">
                        <div class="text-center mt-4">
                        	<button type="submit" class="btn btn-light btn-xs mr-3"><?php _e('添加', 'i_theme' ); ?></button>
                        	<button type="button" class="btn-close-fm btn btn-dark btn-xs" data-dismiss="modal"><?php _e('取消', 'i_theme' ); ?></button>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
    </div>
<?php } ?>
