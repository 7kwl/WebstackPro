<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>


	<?php if ( is_page('template-cms') ) : ?>
		<?php dynamic_sidebar( 'cms-page' ); ?>
	<?php endif; ?>

	<?php if (is_single() || is_page() ) : ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-s' ) ) : ?>
			<div id="add-widgets" class="card widget_text bk">
				
				<div class="card-header">
					<span><i class="iconfont icon-category mr-2"></i><?php _e('添加小工具','i_theme') ?></span>
				</div>
				<div class="card-body text-sm">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank"><?php _e('点此为“正文侧边栏”添加小工具','i_theme') ?></a>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( is_archive() || is_search() || is_404() ) : ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-a' ) ) : ?>
			<div id="add-widgets" class="card widget_text bk">
				<div class="card-header">
					<span><i class="iconfont icon-category mr-2"></i><?php _e('添加小工具','i_theme') ?></span>
				</div>
				<div class="card-body text-sm">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank"><?php _e('点此为“分类归档侧边栏”添加小工具','i_theme') ?></a>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

