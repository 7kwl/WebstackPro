<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill page">
<?php get_template_part( 'templates/header','banner' ); ?>
    <div id="content" class="container my-4 my-md-5">
            <div class="panel card">
		    <div class="card-body">
				<div class="panel-header mb-4">
                    <h1 class="h3"><?php echo get_the_title() ?></h1>
                </div>
                <div class="panel-body mt-2">
                    <?php while( have_posts() ): the_post(); ?>
	    		    <?php the_content();?>
                        <?php edit_post_link(__('编辑','i_theme'), '<span class="edit-link">', '</span>' ); ?>
	    	        <?php endwhile; ?>
                </div>
            </div>
            </div>
            <?php 
            if ( comments_open() || get_comments_number() ) :
	    		comments_template();
            endif; 
            ?>
	</div>
<?php get_footer(); ?>