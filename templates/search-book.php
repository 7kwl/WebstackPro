<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

                <div class="row">
                 
                <?php if ( !have_posts() ) : ?>
                    <div class="col-lg-12">
                        <div class="nothing"><?php _e('没有内容','i_theme') ?></div>
                      </div>
                <?php endif; ?>
                
                <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); 
                ?>
                    <div class="url-card col-6 col-sm-4 col-md-3 col-lg-4 col-xl-5a">
            		    <?php include( get_theme_file_path('/templates/card-book.php') ); ?>
            		</div> 
                <?php  
                endwhile; endif;?>
                </div> 
