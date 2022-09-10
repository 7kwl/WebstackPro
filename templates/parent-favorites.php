<?php if ( ! defined( 'ABSPATH' ) ) { exit; }?>
<?php get_header();?>


<?php 
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill">
<?php get_template_part( 'templates/tools','header' ); ?>  

    
     
                <div class="card mb-4 p-title">
                    <div class="card-body">
                        <h1 class="text-gray text-lg m-0"><?php single_cat_title() ?></h1>
                    </div>
                </div>
        <?php
        // 加载网址模块  
                $children = get_categories(array(
                    'taxonomy'   => 'favorites',
                    //'meta_key'   => '_term_order',
                    //'orderby'    => 'meta_value_num',
                    //'order'      => 'desc',
                    'child_of'   => get_queried_object_id(),
                    'hide_empty' => 0
                ));
                if( $children ){  
                    foreach($children as $mid) { 
                        fav_con($mid);
                    } 
                } 
        ?>   
 
    </div> 
<?php
get_footer();
