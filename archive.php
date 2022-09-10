<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/tools','header' ); ?>
    <div class="row">
        <div class="col-lg-8">
            <?php include( get_theme_file_path('/templates/cat-list.php') ); ?>
        </div> 
		<div class="sidebar col-lg-4 pl-xl-4 d-none d-lg-block">
			<?php get_sidebar(); ?>
		</div> 
    </div>
</div>
<?php get_footer(); ?>
