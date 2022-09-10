<?php if ( ! defined( 'ABSPATH' ) ) { exit; } 
// 站内搜索框，mini搜索框
?> 
<div id="search" class="s-search mx-auto my-4">
	<form name="formsearch" method="get" action="<?php bloginfo('url'); ?>" id="super-search-fm">
	 
			<input type="hidden" name="post_type"  value="sites"/> 
			<input type="text" id="search-text" required="required" name="s" class="form-control search-keyword" placeholder="<?php _e( '输入关键字搜索', 'i_theme' ); ?>" style="outline:0"/> 
        	<button type="submit"><i class="iconfont icon-search "></i></button>
	 
    </form>
</div>
