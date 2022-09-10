<?php
/*
Template Name: 博客页面
*/

get_header(); ?>


<?php 
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/header','banner' ); ?>

<div id="content" class="container my-4 my-md-5">
    <div class="slide-blog mb-4">
    <?php get_template_part( 'templates/slide','blog' ); ?>
    </div>
    <div class="row">
        <div class="col-lg-8">

            <?php
                
                $is_blog = true;
		 	?>
             
            
            <?php include( get_theme_file_path('/templates/cat-list.php') ); ?>
            <?php wp_reset_query(); ?>
        </div> 
		<div class="sidebar col-lg-4 pl-xl-4 d-none d-lg-block">
			<?php get_sidebar('blog'); ?>
		</div> 
    </div>
</div>

<?php get_footer(); ?>
