<?php 
/*
Template Name: 公告列表
*/

get_header(); ?>


<?php 
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/header','banner' ); ?>

<div id="content" class="container my-4 my-md-5">

<div class="post-cover overlay-hover mb-3 mb-md-4">
    <div class="media rounded media-5x1">
        <?php if(io_get_option('lazyload')): ?>
            <div class="media-content" data-src="//i.loli.net/2020/02/10/C1ayMpdRkPGvtI7.jpg"><span class="overlay"></span></div>
        <?php else: ?>
            <div class="media-content"style="background-image: url(//i.loli.net/2020/02/10/C1ayMpdRkPGvtI7.jpg);"><span class="overlay"></span></div>
        <?php endif ?>
        <div class="card-img-overlay d-flex justify-content-center text-center flex-column p-3 p-md-4">
            <h1 class="h4 text-white">—— <?php _e('公告','i_theme') ?> ——</h1>      
            <div class="text-white">
                <small><?php _e('总计：','i_theme') ?><?php echo wp_count_posts( 'bulletin')->publish ?></small>
            </div>          
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-lg-8"> 
        <div class="cat_list">
        <?php  
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$args = array(
			'post_type' => 'bulletin',
			'orderby'   => 'date',
			'paged' => $paged
		);
		query_posts( $args );  
		if ( have_posts() ) : 
		while ( have_posts() ) : the_post(); 
		?>
        <article class="card bulletin-card mb-3">
            <div class="card-body py-3 px-3 px-md-4 d-flex flex-fill text-muted">
                <div><i class="iconfont icon-instructions"></i></div>
                <div class="bulletin mx-1 mx-md-2">
		    	    <?php 
		    	        the_title( sprintf( '<p class="scrolltext-title overflowClip_1"><a href="%s" rel="bulletin">', esc_url( get_permalink() ) ), '</a></p>' ); 
                    ?>
		        </div>
		        <div class="flex-fill"></div>
                <div class="text-muted text-xs" style="white-space:nowrap;line-height:1.5625rem"><i class="iconfont icon-time mr-2"></i><?php wp_is_mobile()? the_time('m/d') : the_time(__('Y年m月d日','i_theme')) ?></div>
            </div>
        </article>
        <?php endwhile;endif; wp_reset_postdata();?>
 
        </div>
	    <div class="posts-nav">
	        <?php echo paginate_links(array(
	            'prev_next'          => 0,
	            'before_page_number' => '',
	            'mid_size'           => 2,
	        ));?>
	    </div>
        </div> 
		<div class="sidebar col-lg-4 pl-xl-4 d-none d-lg-block">
			<?php get_sidebar("bulletin"); ?>
		</div> 
    </div>
</div>

<?php get_footer(); ?>
