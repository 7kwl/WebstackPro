<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if(io_get_option('hot_card')){ 
    if($menu_list = io_get_option('hot_menu_1')['enabled']){
    ?>
    
    <div class="overflow-x-auto mb-4">
        <div class='slider_menu mini_tab ajax-list' sliderTab="sliderTab" >
            <ul class="nav nav-pills menu" role="tablist"> 
                <?php $index = 0; foreach ($menu_list as $key => $value) {
                    $active = ($index == 0?'active':'');
                    switch($key){
                        case 'sites-views': 
                            $name = __('热门网址', 'i_theme' );
                            $action = "load_hot_sites";
                            break;
                        case 'sites-_like_count': 
                            $name = __('大家喜欢', 'i_theme' );
                            $action = "load_hot_sites";
                            break;
                        case 'sites-date': 
                            $name = __('最新网址', 'i_theme' );
                            $action = "load_hot_sites";
                            break;
                        case 'app-views': 
                            $name = __('热门 App', 'i_theme' );
                            $action = "load_hot_app";
                            break;
                        case 'app-_like_count': 
                            $name = __('最爱 App', 'i_theme' );
                            $action = "load_hot_app";
                            break;
                        case 'app-date': 
                            $name = __('最新 App', 'i_theme' );
                            $action = "load_hot_app";
                            break;
                        case 'app-_down_count': 
                            $name = __('下载最多 APP', 'i_theme' );
                            $action = "load_hot_app";
                            break;
                        case 'book-views': 
                            $name = __('热门书籍', 'i_theme' );
                            $action = "load_hot_book";
                            break;
                        case 'book-_like_count': 
                            $name = __('最爱书籍', 'i_theme' );
                            $action = "load_hot_book";
                            break;
                        case 'book-date': 
                            $name = __('最新书籍', 'i_theme' );
                            $action = "load_hot_book";
                            break;
                        default: 
                            $class = ' col-sm-6 col-md-4 col-lg-3 ';
                    }
                    $type   = explode('-', $key)[0];
                    $meta   = explode('-', $key)[1];
                    if($index == 0){
                        $my_type   = $type;
                        $my_meta   = $meta;
                    }
                    echo '<li class="pagenumber nav-item">
                    <a class="nav-link '.$active.'" data-action="'.$action.'" data-type="'.$meta.'" data-target="ajax-list-body">'.$name.'</a>
                    </li>';
                    $index++;
                } ?>
            </ul>
        </div> 
        </div> 
        <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?> ajax-list-body" style="position: relative;">
            <?php   
            global $post;
            $site_n = io_get_option('hot_n');
            $args = array(
                'post_type'           => $my_type, 
                //'post_status'         => 'publish',        
                'ignore_sticky_posts' => 1,              
                'posts_per_page'      => $site_n,       
            );
            if($my_meta == 'date'){
                $args['orderby'] = 'date';
            }else{
                $args['meta_key'] = $my_meta;
                $args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
            }
            $myposts = new WP_Query( $args );
            if(!$myposts->have_posts()): ?>
                <div class="col-lg-12">
                    <div class="nothing mb-4"><?php _e('没有数据！请开启统计并等待产生数据', 'i_theme' ); ?></div>
                </div>
            <?php
            elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
            if($my_type == 'sites'){
                //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)){
                $link_url = get_post_meta($post->ID, '_sites_link', true); 
                $default_ico = get_theme_file_uri('/images/favicon.png');
                ?>
		        <?php if(io_get_option("hot_card_mini")) {?>
                	<div class="url-card col-6 <?php get_columns() ?> col-xxl-10a <?php echo before_class($post->ID) ?>">
                    <?php include( get_theme_file_path('/templates/card-sitemini.php')  ); ?>
                    </div>
		        <?php }else{?>
                	<div class="url-card <?php echo io_get_option('two_columns')?"col-6":"" ?> <?php get_columns() ?> <?php echo before_class($post->ID) ?>">
                    <?php include( get_theme_file_path('/templates/card-site.php')  );?>
                    </div>
		        <?php }?>
            <?php //}
            }elseif ($my_type == 'app') { ?>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-5a ">
                <?php
                include( get_theme_file_path('/templates/card-appcard.php') ); 
                ?>
                </div>
                <?php
            } elseif($my_type == "book") { 
                echo'<div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xxl-8a">';
                include( get_theme_file_path('/templates/card-book.php') ); 
                echo'</div>'; 
            }
            endwhile; endif; wp_reset_postdata(); ?>
        </div>   
<?php }
} ?>
