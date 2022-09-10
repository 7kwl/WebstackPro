<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
if(get_term_children(get_queried_object_id(), 'favorites')){
	get_template_part( 'templates/parent','favorites' );
	exit;
}
get_header();
?>

<?php include( get_theme_file_path('/templates/sidebar-nav.php') ); ?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/tools','header' ); ?>
    
		<div class="row">
    	    <div class="col-lg-8 col-xl-9">
                <h4 class="text-gray text-lg mb-4">
                    <i class="site-tag iconfont icon-book icon-lg mr-1" id="<?php single_cat_title() ?>"></i><?php single_cat_title() ?>
                </h4>
                <div class="row">  
	            	<?php 
					if ( !have_posts() ){
						echo '<div class="col-lg-12"><div class="nothing">'.__("没有内容","i_theme").'</div></div>';
					}
					if ( have_posts() ) : while ( have_posts() ) : the_post(); 
					?>
            		    <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-5a">
            		    <?php include( get_theme_file_path('/templates/card-book.php') ); ?>
            		    </div>
            		<?php  
					endwhile; endif;?>
                </div>  
	            <div class="posts-nav mb-4">
	                <?php echo paginate_links(array(
	                    'prev_next'          => 0,
	                    'before_page_number' => '',
	                    'mid_size'           => 2,
	                ));?>
                </div>
			</div> 
			<div class="sidebar col-lg-4 col-xl-3 pl-xl-4 d-none d-lg-block">
				<?php get_sidebar(); ?>
			</div> 
    	</div>
  	</div>
<?php get_footer(); ?>
