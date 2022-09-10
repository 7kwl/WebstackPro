<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php  
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill page">
<?php get_template_part( 'templates/header','banner' ); ?>
<div id="content" class="container my-4 my-md-5">
	<div class="row">
		<div class="col-lg-8">
            <div class="post-cover overlay-hover mb-3 mb-md-4">
                <div class="media rounded media-5x1">
                    <?php if(io_get_option('lazyload')): ?>
                        <div class="media-content" data-src="//i.loli.net/2020/02/10/C1ayMpdRkPGvtI7.jpg"><span class="overlay"></span></div>
                    <?php else: ?>
                        <div class="media-content"style="background-image: url(//i.loli.net/2020/02/10/C1ayMpdRkPGvtI7.jpg);"><span class="overlay"></span></div>
                    <?php endif ?>
                    <div class="card-img-overlay d-flex justify-content-center text-center flex-column p-3 p-md-4">
                        <h1 class="h4 text-white">—— <?php _e('公告','i_theme') ?> ——</h1>
                            <div class="text-xs">
                                <a class="mx-1 custom-piece_c_b" href="<?php echo get_permalink(io_get_option('all_bull')) ?>" style="color:#fff;background:#f12345;padding:2px 5px;border-radius:3px;"><small><?php _e('所有公告','i_theme') ?></small></a>
                            </div>                
                    </div>
                </div>
            </div>
    	    <div class="panel card">
			<div class="card-body">
    	        <?php while( have_posts() ): the_post(); ?>
    	        <h1 class="h2 mb-3"><?php echo get_the_title() ?></h1>
    	        <div class="d-flex flex-fill text-muted text-sm mb-5">
    	        <?php 
    	        $category = get_the_category();
    	        if(!empty($category) && $category[0]){
    	        echo '<span class="mr-3 d-none d-sm-block"><a href="'.get_category_link($category[0]->term_id ).'"><i class="iconfont icon-classification"></i> '.$category[0]->cat_name.'</a></span>';
    	        }
    	        ?>
    	        <span class="mr-3 d-none d-sm-block"><i class="iconfont icon-time"></i> <?php echo timeago(get_the_time('Y-m-d G:i:s')); ?></span>
				<span class="mr-3"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" title="<?php the_author_meta('nickname') ?>"><i class="iconfont icon-user-circle"></i> <?php the_author_meta('nickname') ?></a></span>
				<div class="flex-fill"></div>
				<?php 
				if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views mr-3"><i class="iconfont icon-chakan"></i> ','</span>' ); }
	            $like_count	= get_like(get_the_ID());
	            $liked		= isset($_COOKIE['liked_' . get_the_ID()]) ? 'liked' : ''; 
                ?>
         
                <span class="mr-3"><a class="" href="#comments"> <i class="iconfont icon-comment"></i> <?php echo get_post(get_the_ID())->comment_count; ?></a></span>
                <span class="mr-3"><a class="btn-like btn-link-like <?php echo $liked ?>" href="javascript:;" post_like data-id="<?php echo get_the_ID() ?>"><i class="iconfont icon-like"></i> <span class="like-count"><?php echo $like_count ?></span></a></span>
    
    	        </div>
    	        <div class="panel-body mt-2"> 
    	            <?php the_content();?>
    	        </div>
			
				<div class="tags my-2">
					<?php
						$post_tags = get_the_tags();
						if ($post_tags) {
							echo '<i class="iconfont icon-tags"></i>';
						  	foreach($post_tags as $tag) {
								echo '<a href="'.get_tag_link($tag->term_id).'" rel="tag" class="tag-' . $tag->slug . ' color-'.mt_rand(0, 8).'">' . $tag->name . '</a>';
						  	}
						}
					?>
				</div>
    	        <?php edit_post_link(__('编辑','i_theme'), '<span class="edit-link">', '</span>' ); ?>
			    <?php endwhile; ?> 
    	    </div>
			</div>
    	    <div class="near-navigation rounded mt-4 py-2">
    	    	<?php
    	    	$current_category = get_the_category();//获取当前文章所属分类ID
    	    	$prev_post = get_previous_post($current_category,'');//与当前文章同分类的上一篇文章
    	    	$next_post = get_next_post($current_category,'');//与当前文章同分类的下一篇文章
    	    	?>
    	    	<?php if (!empty( $prev_post )) { ?>
    	    	<div class="nav previous border-right border-color">
    	            <a class="near-permalink" href="<?php echo get_permalink( $prev_post->ID ); ?>">
    	    		<span><?php _e('上一篇','i_theme') ?></span>
    	    		<h4 class="near-title"><?php echo $prev_post->post_title; ?></h4>
    	    		</a>
    	    	</div>
    	    	<?php } else { ?>
    	    	<div class="nav none border-right border-color">
    	    		<span><?php _e('上一篇','i_theme') ?></span>
    	    		<h4 class="near-title"><?php _e('没有更多了...','i_theme') ?></h4>
    	    	</div>
    	    	<?php } ?>
    	    	<?php if (!empty( $next_post )) { ?>
    	    	<div class="nav next border-left border-color">
    	    		<a class="near-permalink" href="<?php echo get_permalink( $next_post->ID ); ?>">
    	    		<span><?php _e('下一篇','i_theme') ?></span>
    	            <h4 class="near-title"><?php echo $next_post->post_title; ?></h4>
    	        </a>
    	    	</div>
    	    	<?php } else { ?>
    	    	<div class="nav none border-left border-color" style="text-align: right;">
    	    		<span><?php _e('下一篇','i_theme') ?></span>
    	    		<h4 class="near-title"><?php _e('没有更多了...','i_theme') ?></h4>	
    	    	</div>
    	    	<?php } ?>
    	    </div>
    	    <?php 
    	    if ( comments_open() || get_comments_number() ) :
				comments_template();
    	    endif; 
    	    ?>
		</div> 
		<div class="sidebar col-lg-4 pl-xl-4 d-none d-lg-block">
			<?php get_sidebar(); ?>
		</div>
	</div> 
</div>
<?php get_footer(); ?>