<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
<?php 
$ico_info = get_post_meta(get_the_ID(), 'app_ico_o', true);
$bg = '';$size='';
if($ico_info && $ico_info['ico_a']){
    $bg ='style="background-image: linear-gradient(130deg, '.$ico_info['ico_color']['color-1'].', '.$ico_info['ico_color']['color-2'].');"';
    $size = 'background-size: '.$ico_info["ico_size"].'%';
}
?>
	<div class="card-book list-item">
        <div class="media media-5x7 p-0 rounded">
            <?php if(io_get_option('lazyload')): ?>
            <a class="media-content" href="<?php the_permalink(); ?>" <?php echo new_window() ?> data-bg="url(<?php echo get_post_meta_img(get_the_ID(), '_thumbnail', true) ?>)"></a>
            <?php else: ?>
            <a class="media-content" href="<?php the_permalink(); ?>" <?php echo new_window() ?>  style="background-image: url(<?php echo get_post_meta_img(get_the_ID(), '_thumbnail', true) ?>)"></a>
            <?php endif ?>
        </div>
        <div class="list-content">
            <div class="list-body">
                <a href="<?php the_permalink(); ?>" <?php echo new_window() ?> class=" list-title text-md overflowClip_1">
                <?php show_sticky_tag( is_sticky() ) . show_new_tag(get_the_time('Y-m-d H:i:s')) ?><?php the_title(); ?>         
                </a>
                <div class="mt-1">
                    <div class="list-subtitle text-muted text-xs overflowClip_1">
                    <?php echo get_post_meta(get_the_ID(), '_summary', true) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>	 
