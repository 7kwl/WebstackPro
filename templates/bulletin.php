<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if( io_get_option('show_bulletin') && io_get_option('bulletin')) : ?>
<div id="bulletin_box" class="card my-2" >
    <div class="card-body py-1 px-2 px-md-3 d-flex flex-fill text-xs text-muted">
		<div><i class="iconfont icon-bulletin" style="line-height:25px"></i></div>
        <div class="bulletin mx-1 mx-md-2">
            <ul class="bulletin-ul">
				<?php 
					$args = array(
						'post_type' => 'bulletin', 
						'posts_per_page' => io_get_option('bulletin_n')
					);
					query_posts($args); while ( have_posts() ) : the_post();
				?>
				<?php the_title( sprintf( '<li class="scrolltext-title overflowClip_1"><a href="%s" rel="bulletin">', esc_url( get_permalink() ) ), '</a> ('. get_the_time('m/d').')</li>' ); ?>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
            </ul>
		</div>
		<div class="flex-fill"></div>
        <a title="<?php _e('关闭','i_theme') ?>" href="javascript:;" rel="external nofollow" class="bulletin-close" onClick="$('#bulletin_box').slideUp('slow');"><i class="iconfont icon-close" style="line-height:25px"></i></a>
    </div>
</div>
<script type="text/javascript">$(document).ready(function() {$("#bulletin_box").textSlider({line: 1, speed: 300, timer: 4000});})</script>
<?php endif; ?> 