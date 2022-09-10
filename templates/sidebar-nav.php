<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php 

$menu_list = array();
if(io_get_option('sort_menu')){
    foreach (io_get_option('sort_menu')['enabled'] as $key => $value) {
        $menu_list[] = substr($key, 3);
    }
}
//$categories= get_categories(array(
//    'taxonomy'     => array('favorites','apps'),
//    //'meta_key'     => '_term_order',
//    'include'      => $menu_list,
//    'orderby'      => 'include',
//    //'order'        => 'desc',
//    'hide_empty'   => 0,
//)); 
$categories= get_menu_list('nav_menu');

// 兼容低版本
function get_cate_ico($class){
    if(strpos($class,' ') !== false){ 
        return $class; 
    }else{
        return 'fa '.$class; 
    }
} 
$logo_class = '';
$logo_light_class = 'class="d-none"';
if(io_get_option('theme_mode')=="io-grey-mode"){
$logo_class = 'class="logo-dark d-none"';
$logo_light_class = 'class="logo-light"';
} 
?>
        <div id="sidebar" class="sticky sidebar-nav fade <?php echo io_get_option('min_nav')?'mini-sidebar" style="width: 60px;':''?>">
            <div class="modal-dialog h-100  sidebar-nav-inner">
                <div class="sidebar-logo border-bottom border-color">
                    <!-- logo -->
                    <div class="logo overflow-hidden">
                        <a href="<?php bloginfo('url') ?>" class="logo-expanded">
                            <img src="<?php echo io_get_option('logo_normal_light')['url'] ?>" height="40" <?php echo $logo_light_class ?> alt="<?php bloginfo('name') ?>">
						    <img src="<?php echo io_get_option('logo_normal')['url'] ?>" height="40" <?php echo $logo_class ?> alt="<?php bloginfo('name') ?>">
                        </a>
                        <a href="<?php bloginfo('url') ?>" class="logo-collapsed">
                            <img src="<?php echo io_get_option('logo_small_light')['url'] ?>" height="40" <?php echo $logo_light_class ?> alt="<?php bloginfo('name') ?>">
						    <img src="<?php echo io_get_option('logo_small')['url'] ?>" height="40" <?php echo $logo_class ?> alt="<?php bloginfo('name') ?>">
                        </a>
                    </div>
                    <!-- logo end -->
                </div>
                <div class="sidebar-menu flex-fill">
                    <div class="sidebar-scroll" >
                        <div class="sidebar-menu-inner">
                            <ul>
                            <?php 
                            foreach($categories as $category) {
                                if($category['menu_item_parent'] == 0){ 
                                    if($category['type'] != 'taxonomy' && empty($category['submenu'])){
                                        $icon = implode(" ",$category['classes']);
                                        $url = trim($category['url']);
                                        if( strlen($url)>1 && substr( $url, 0, 1 ) == '#') { ?>
                                            <li class="sidebar-item">
                                                <a href="<?php if (is_home() || is_front_page()): ?><?php else: echo home_url()?>/<?php endif; ?><?php echo $url ?>" class="smooth">
                                                   <i class="<?php echo $icon ?> icon-fw icon-lg mr-2"></i>
                                                   <span><?php echo $category['title']; ?></span>
                                                </a>
                                            </li> 
                                        <?php }
                                        continue;
                                    }else{
                                        if(empty($category['classes']) || ( count($category['classes'])==1 && empty($category['classes'][0])) )
                                        $icon = get_cate_ico($category['post_content']);
                                        else{
                                            $classes = preg_grep( '/^(fa[b|s]?|io)(-\S+)?$/i', $category['classes'] );
			                                if( !empty( $classes ) ){
			                                	$icon = implode(" ",$category['classes']);
			                                }else{
                                                $icon = '';
                                            }
                                        }
                                    }
                                    if(empty($category['submenu'])){ ?>
                                        <li class="sidebar-item">
                                            <a href="<?php if (is_home() || is_front_page()): ?><?php else: echo home_url()?>/<?php endif; ?>#term-<?php echo $category['object_id'];?>" class="smooth">
                                               <i class="<?php echo $icon ?> icon-fw icon-lg mr-2"></i>
                                               <span><?php echo $category['title']; ?></span>
                                            </a>
                                        </li> 
                                    <?php }else { ?>
                                        <li class="sidebar-item">
                                            <a href="javascript:;">
                                               <i class="<?php echo $icon ?> icon-fw icon-lg mr-2"></i>
                                               <span><?php echo $category['title']; ?></span>
                                               <i class="iconfont icon-arrow-r-m sidebar-more text-sm"></i>
                                            </a>
                                            <ul>
                                                <?php foreach ($category['submenu'] as $mid) { ?>
                                                
                                                <li>
                                                    <a href="<?php if (is_home() || is_front_page()): ?><?php else: echo home_url()?>/<?php endif; ?>#term-<?php  echo $mid['object_id'] ;?>" class="smooth"><span><?php echo $mid['title']; ?></span></a>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                <?php }
                                } 
                            }
                            ?> 
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border-top py-2 border-color">
                    <div class="flex-bottom">
                        <ul> 
                            <?php wp_menu('nav_main');?> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        