<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
   

        <div class="card flex-fill mb-3" >
            <div class="row no-gutters">
                <div class="col-4">
                    
                <div class="media media-4x3 rounded-left">
                    <?php if(io_get_option('lazyload')): ?>
                    <a class="media-content" href="<?php the_permalink(); ?>" <?php echo new_window() ?> data-src="<?php echo  io_theme_get_thumb() ?>"></a>
                    <?php else: ?>
                    <a class="media-content" href="<?php the_permalink(); ?>" <?php echo new_window() ?>  style="background-image: url(<?php echo  io_theme_get_thumb() ?>);"></a>
                    <?php endif ?>
                </div>
                </div>
                <div class="col-8">
                    <div class="card-body list-content p-2 p-xl-3" style="height: 100%;"> 
                        <div class="list-body"> 
                    
            				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="list-title no-c text-md overflowClip_1"><?php show_sticky_tag( is_sticky() ) . show_new_tag(get_the_time('Y-m-d H:i:s')) ?><?php the_title(); ?></a>
            				   
                        </div>
                        <div>
							<div class="d-flex flex-fill align-items-center text-muted text-xs">
								<?php
        						$category = get_the_category();
        						if($category[0]){   ?>
								<span><i class="iconfont icon-classification"></i>
        							<a href="<?php echo get_category_link($category[0]->term_id ) ?>" class="no-c"><?php echo $category[0]->cat_name ?></a>
								</span>
        						<?php } ?>
    							<div class="flex-fill"></div>
        						<div>
            						<time class="mx-1"><?php echo timeago( get_the_time('Y-m-d G:i:s') ) ?></time>
								</div>
							</div>      
                        </div>  
						 
                    </div>
                </div>
            </div>
        </div>

            					
      