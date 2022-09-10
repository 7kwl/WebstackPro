<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  

if( (!wp_is_mobile() && io_get_option('ad_home_s_link')) || (wp_is_mobile() && io_get_option('ad_home_mobile_link') && io_get_option('ad_home_s_link')) ) { ?>
 	<?php if( io_get_option('ad_home_s2_link') ) { ?>
	<div class="row mb-4">
		<div class="apd apd-home col-12 col-xl-6"><?php echo stripslashes( io_get_option('ad_home_link') ) ?></div>
		<div class="apd apd-home col-12 col-xl-6 d-none d-xl-block"><?php echo stripslashes( io_get_option('ad_home2_link') ) ?></div>
	</div>      
	<?php } else { ?>
	<div class="row mb-4">
		<div class="apd apd-home col-12"><?php echo stripslashes( io_get_option('ad_home_link') ) ?></div>
	</div> 
	<?php } ?>
<?php } ?>