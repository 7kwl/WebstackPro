<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 最新文章 ------------------------------------------------------
class new_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'new_cat',
			'description' => __( '显示全部分类或某个分类的最新文章','io_setting'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('new_cat', __('最新文章','io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : '';
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			
		if ( ! empty( $title ) )
			echo $before_title . $title_ico . $title . $after_title; 
	?> 
	<div class="card-body"> 
		<div class="list-grid list-rounded my-n2">
			<?php 
				global $post;
				if ( is_single() ) {
				$q =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['numposts'],
					'post__not_in' => array($post->ID),
					'category__and' => $instance['cat'],
				));
				} else {
				$q =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['numposts'],
					'category__and' => $instance['cat'],
				));
			} ?>
			<?php while ($q->have_posts()) : $q->the_post(); ?>
			<div class="list-item py-2">
				<?php if($instance['show_thumbs']) { ?>
				<div class="media media-3x2 rounded col-4 mr-3">
                    <?php if(io_get_option('lazyload')): ?>
                    <a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" data-src="<?php echo  io_theme_get_thumb() ?>"></a>
                    <?php else: ?>
                    <a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" style="background-image: url(<?php echo  io_theme_get_thumb() ?>);"></a>
                    <?php endif ?>
				</div>
				<?php } ?>
				<div class="list-content py-0">
					<div class="list-body">
						<a href="<?php the_permalink(); ?>" class="list-title overflowClip_2" <?php echo $newWindow ?> rel="bookmark"><?php the_title(); ?></a>
					</div>
					<div class="list-footer">
						<div class="d-flex flex-fill text-muted text-xs">
							<time class="d-inline-block"><?php echo timeago(get_the_time('Y-m-d G:i:s')); ?></time>
							<div class="flex-fill"></div>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="iconfont icon-chakan"></i> ','</span>' ); } ?>
						</div>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>

	<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_ico'] = strip_tags($new_instance['title_ico']);
		$instance['hideTitle'] = isset($new_instance['hideTitle']);
		$instance['newWindow'] = isset($new_instance['newWindow']);
		$instance['numposts'] = $new_instance['numposts'];
		$instance['cat'] = $new_instance['cat'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => __('最新文章','io_setting'),
			'title_ico' => 'iconfont icon-category',
			'numposts' => 5,
			'cat' => 0));
			 ?> 

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：','io_setting') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('title_ico'); ?>"><?php _e('图标代码：','io_setting') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title_ico'); ?>" name="<?php echo $this->get_field_name('title_ico'); ?>" type="text" value="<?php echo $instance['title_ico']; ?>" />
			</p> 
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
				<label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('在新窗口打开标题链接','io_setting') ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'numposts' ); ?>"><?php _e( '显示文章数：' ,'io_setting'); ?></label>
				<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('选择分类：','io_setting') ?>
				<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => __('全部分类','io_setting'), 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
			</p>
			<p>
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>"><?php _e('显示缩略图','io_setting') ?></label>
			</p>
	<?php }
}

add_action( 'widgets_init', 'new_cat_init' );
function new_cat_init() {
	register_widget( 'new_cat' );
}

// 最新公告 ------------------------------------------------------
class new_bulletin extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'new_bulletin',
			'description' => __( '显示所有公告','io_setting' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('new_bulletin', __('最新公告','io_setting' ), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'newWindow'   => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : '';
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			
		if ( ! empty( $title ) )
			echo $before_title . $title_ico . $title . $after_title; 
	?> 
	<div class="card-body"> 
		<div class="list-grid list-bulletin my-n2">
			<?php 
				$q =  new WP_Query(array(
					'post_type' => 'bulletin', 
					'posts_per_page' => $instance['numposts'],
					'ignore_sticky_posts' => 1,
				));
			?>
			<?php while ($q->have_posts()) : $q->the_post(); ?>
			<div class="list-item py-2">
					<i class="iconfont icon-point"></i>
				<div class="list-content py-0">
					<div class="list-body">
						<a href="<?php the_permalink(); ?>" class="list-title overflowClip_2" <?php echo $newWindow ?> rel="bulletin"><?php the_title(); ?></a>
					</div>
					<div class="list-footer">
						<div class="d-flex flex-fill text-muted text-xs">
							<time class="d-inline-block"><?php echo timeago(get_the_time('Y-m-d G:i:s')); ?></time>
							<div class="flex-fill"></div>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="iconfont icon-chakan"></i> ','</span>' ); } ?>
						</div>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		<div class="mt-4"><a href="<?php echo get_permalink(io_get_option('all_bull')) ?>" target="_blank" class="btn btn-outline-danger btn-block"><?php _e('更多','io_setting') ?></a></div>
	</div>

	<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_ico'] = strip_tags($new_instance['title_ico']);
		$instance['hideTitle'] = isset($new_instance['hideTitle']);
		$instance['newWindow'] = isset($new_instance['newWindow']);
		$instance['numposts'] = $new_instance['numposts'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => __('站点公告','io_setting'),
			'title_ico' => 'iconfont icon-bulletin',
			'numposts' => 5));
			 ?> 

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：','io_setting') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('title_ico'); ?>"><?php _e('图标代码：','io_setting') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title_ico'); ?>" name="<?php echo $this->get_field_name('title_ico'); ?>" type="text" value="<?php echo $instance['title_ico']; ?>" />
			</p> 
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
				<label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('在新窗口打开标题链接','io_setting') ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'numposts' ); ?>"><?php _e( '显示文章数：' ,'io_setting'); ?></label>
				<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
			</p>
	<?php }
}

add_action( 'widgets_init', 'new_bulletin_init' );
function new_bulletin_init() {
	register_widget( 'new_bulletin' );
}

// 热评文章 ------------------------------------------------------
class hot_comment extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_comment',
			'description' => __( '调用评论最多的文章' ,'io_setting'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_comment',  __( '热评文章' ,'io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : '';
		if ($newWindow) $newWindow = "target='_blank'";
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_ico . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
	?>

	
	<div class="card-body"> 
		<div class="list-grid list-rounded my-n2">
				<?php
					$review = new WP_Query( array(
						'post_type' => array( 'post' ),
						'showposts' => $number,
						'ignore_sticky_posts' => true,
						'orderby' => 'comment_count',
						'order' => 'dsc',
						'date_query' => array(
							array(
								'after' => ''.$days. 'day ago',
							),
						),
					) );
				?>

				<?php while ( $review->have_posts() ): $review->the_post(); ?>
				<div class="list-item py-2">
					<?php if($instance['show_thumbs']) { ?>
					<div class="media media-3x2 rounded col-4 mr-3">
            	        <?php if(io_get_option('lazyload')): ?>
            	        <a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" data-src="<?php echo  io_theme_get_thumb() ?>"></a>
            	        <?php else: ?>
            	        <a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" style="background-image: url(<?php echo  io_theme_get_thumb() ?>);"></a>
            	        <?php endif ?>
					</div>
					<?php } ?>
					<div class="list-content py-0">
						<div class="list-body">
							<a href="<?php the_permalink(); ?>" class="list-title overflowClip_2" <?php echo $newWindow ?> rel="bookmark"><?php the_title(); ?></a>
						</div>
						<div class="list-footer">
							<div class="d-flex flex-fill text-muted text-xs">
								<time class="d-inline-block"><?php echo timeago(get_the_time('Y-m-d G:i:s')); ?></time>
								<div class="flex-fill"></div>
								<span class="discuss"><?php comments_number( '<i class="iconfont icon-comment"></i> 0', '<i class="iconfont icon-comment"></i> 1', '<i class="iconfont icon-comment"></i> %' ); ?></span>
							</div>
						</div>
					</div>
				</div> 
				<?php endwhile;?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>

	<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array(); 
		$instance['title_ico'] = strip_tags($new_instance['title_ico']);
		$instance['newWindow'] = isset($new_instance['newWindow']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __('热评文章' ,'io_setting');
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$instance = wp_parse_args((array) $instance, array('title_ico' => 'iconfont icon-hot'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('标题：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p> 
	<p>
		<label for="<?php echo $this->get_field_id('title_ico'); ?>"><?php _e('图标代码：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_ico'); ?>" name="<?php echo $this->get_field_name('title_ico'); ?>" type="text" value="<?php echo $instance['title_ico']; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('在新窗口打开标题链接','io_setting') ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( '显示文章数：' ,'io_setting'); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>"><?php _e('时间限定（天）：','io_setting') ?></label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>"><?php _e('显示缩略图','io_setting') ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function hot_comment_init() {
	register_widget( 'hot_comment' );
}
add_action( 'widgets_init', 'hot_comment_init' );

// 热门标签 ------------------------------------------------------
class cx_tag_cloud extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cx_tag_cloud',
			'description' => __( '包含所有标签类型' ,'io_setting'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('cx_tag_cloud', __('热门标签' ,'io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'title_ico' => 'iconfont icon-tags'
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : '';
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_ico . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 20;
	?> 
		
		<div class="card-body">
		<div class="tags text-justify">
			<?php 
			$tax = array('post_tag','apptag','sitetag','booktag');
			foreach (get_terms( array('taxonomy' => $tax, 'number' => $number, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => false) ) as $tag){
				$tag_link = get_term_link($tag->term_id);
				?> 
				<a href="<?php echo $tag_link ?>" title="<?php echo $tag->name ?>" class="tag-<?php echo $tag->slug ?> color-<?php echo mt_rand(0, 8) ?>">
				<?php echo $tag->name ?><span>(<?php echo $tag->count ?>)</span></a>
			<?php } ?> 
		</div>
		</div>

	<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_ico'] = strip_tags($new_instance['title_ico']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __('热门标签','io_setting');
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '20'));
		$number = strip_tags($instance['number']);
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('标题：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_ico'); ?>"><?php _e('图标代码：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_ico'); ?>" name="<?php echo $this->get_field_name('title_ico'); ?>" type="text" value="<?php echo $instance['title_ico']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('显示数量：','io_setting') ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function cx_tag_cloud_init() {
	register_widget( 'cx_tag_cloud' );
}
add_action( 'widgets_init', 'cx_tag_cloud_init' );
 
// 相关文章 ------------------------------------------------------
class related_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'related_post',
			'description' => __( '显示相关文章' ,'io_setting'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('related_post', __('相关文章','io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : '';
		if ($newWindow) $newWindow = "target='_blank'";
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_ico . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
	?>


	
	<div class="card-body"> 
		<div class="list-grid list-rounded my-n2">
			<?php
				$post_num = $number;
				global $post;
				$tmp_post = $post;
				$tags = ''; $i = 0;
				if ( get_the_tags( $post->ID ) ) {
				foreach ( get_the_tags( $post->ID ) as $tag ) $tags .= $tag->slug . ',';
				$tags = strtr(rtrim($tags, ','), ' ', '-');
				$myposts = get_posts('numberposts='.$post_num.'&tag='.$tags.'&exclude='.$post->ID);
				foreach($myposts as $post) {
				setup_postdata($post);
			?>  
				<div class="list-item py-2">
					<?php if($instance['show_thumbs']) { ?>
					<div class="media media-3x2 rounded col-4 mr-3">
            	        <?php if(io_get_option('lazyload')): ?>
            	        <a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" data-src="<?php echo  io_theme_get_thumb() ?>"></a>
            	        <?php else: ?>
            	        <a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" style="background-image: url(<?php echo  io_theme_get_thumb() ?>);"></a>
            	        <?php endif ?>
					</div>
					<?php } ?>
					<div class="list-content py-0">
						<div class="list-body">
							<a href="<?php the_permalink(); ?>" class="list-title overflowClip_2" <?php echo $newWindow ?> rel="bookmark"><?php the_title(); ?></a>
						</div>
						<div class="list-footer">
							<div class="d-flex flex-fill text-muted text-xs">
								<time class="d-inline-block"><?php echo timeago(get_the_time('Y-m-d G:i:s')); ?></time>
								<div class="flex-fill"></div>
								<span class="discuss"><?php comments_number( '<i class="iconfont icon-comment"></i> 0', '<i class="iconfont icon-comment"></i> 1', '<i class="iconfont icon-comment"></i> %' ); ?></span>
							</div>
						</div>
					</div>
				</div> 
			<?php
				$i += 1;
				}
				}
				if ( $i < $post_num ) {
				$post = $tmp_post; setup_postdata($post);
				$cats = ''; $post_num -= $i;
				foreach ( get_the_category( $post->ID ) as $cat ) $cats .= $cat->cat_ID . ',';
				$cats = strtr(rtrim($cats, ','), ' ', '-');
				$myposts = get_posts('numberposts='.$post_num.'&category='.$cats.'&exclude='.$post->ID);
				foreach($myposts as $post) {
				setup_postdata($post);
			?>
				<div class="list-item py-2">
					<?php if($instance['show_thumbs']) { ?>
					<div class="media media-3x2 rounded col-4 mr-3">
						<?php if(io_get_option('lazyload')): ?>
						<a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" data-src="<?php echo  io_theme_get_thumb() ?>"></a>
						<?php else: ?>
						<a class="media-content" href="<?php the_permalink(); ?>" <?php echo $newWindow ?> title="<?php the_title(); ?>" style="background-image: url(<?php echo  io_theme_get_thumb() ?>);"></a>
						<?php endif ?>
					</div>
					<?php } ?>
					<div class="list-content py-0">
						<div class="list-body">
							<a href="<?php the_permalink(); ?>" class="list-title overflowClip_2" <?php echo $newWindow ?> rel="bookmark"><?php the_title(); ?></a>
						</div>
						<div class="list-footer">
							<div class="d-flex flex-fill text-muted text-xs">
								<time class="d-inline-block"><?php echo timeago(get_the_time('Y-m-d G:i:s')); ?></time>
								<div class="flex-fill"></div>
								<span class="discuss"><?php comments_number( '<i class="iconfont icon-comment"></i> 0', '<i class="iconfont icon-comment"></i> 1', '<i class="iconfont icon-comment"></i> %' ); ?></span>
							</div>
						</div>
					</div>
				</div> 
			<?php
			}
			}
			$post = $tmp_post; setup_postdata($post);
			?>
		</div>
	</div>

	<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_ico'] = strip_tags($new_instance['title_ico']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __('相关文章','io_setting');
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('title_ico' => 'iconfont icon-related'));
		$number = strip_tags($instance['number']);
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('标题：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_ico'); ?>"><?php _e('图标代码：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_ico'); ?>" name="<?php echo $this->get_field_name('title_ico'); ?>" type="text" value="<?php echo $instance['title_ico']; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('在新窗口打开标题链接','io_setting') ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( '显示文章数：' ,'io_setting'); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>"><?php _e('显示缩略图','io_setting') ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function related_post_init() {
	register_widget( 'related_post' );
}
add_action( 'widgets_init', 'related_post_init' );
 
// 广告位 ------------------------------------------------------
class advert extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'advert',
			'description' => __( '用于侧边添加广告代码' ,'io_setting'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('advert', __('广告位','io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'text' => ''
		);
	}
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;

		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
	?>

	<?php if ( ! wp_is_mobile() ) { ?>
	<div id="advert_widget">
		<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
	</div>
	<?php } ?>

	<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			if ( current_user_can('unfiltered_html') )
				$instance['text'] =  $new_instance['text'];
			else
				$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['filter'] = ! empty( $new_instance['filter'] );
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __('广告位','io_setting');
		}
		$text = esc_textarea($instance['text']);
		global $wpdb;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( '标题：' ,'io_setting'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('内容：','io_setting') ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e( '自动分段' ,'io_setting'); ?></label></p>
		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	<?php }
}
function advert_init() {
	register_widget( 'advert' );
}
add_action( 'widgets_init', 'advert_init' );

// 关于本站 ------------------------------------------------------
class about extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about',
			'description' => __( '本站信息、微信、微博、QQ' ,'io_setting'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about', __('关于本站','io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'show_social_icon' => 1
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
	?>
	<div class="widget-author-cover">
		<div class="media media-2x1">
            <?php if(io_get_option('lazyload')): ?>
            <div class="media-content" data-src="<?php echo $instance['about_back']; ?>"></div>
            <?php else: ?>
            <div class="media-content" style="background-image: url(<?php echo $instance['about_back']; ?>);"></div>
            <?php endif ?>
		</div>
		<div class="widget-author-avatar"> 
			<div class="flex-avatar"> 
            	<img src="<?php echo $instance['about_img']; ?>" height="90" width="90"> 
		  	</div>
		</div>
  	</div>
    <div class="widget-author-meta text-center p-4">
      	<div class="h6 mb-3"><?php echo get_bloginfo('name') ?><small class="d-block mt-2"><?php echo get_bloginfo('description') ?></small> </div>
		
		<?php if($instance['show_social_icon']) { ?> 
	    <div class="row no-gutters text-center my-3">
			<?php if($instance['weixin']) { ?> 
	      	<div class="col">
			  <span class="weixin-b" data-toggle="tooltip" data-placement="top" data-html="true" title="<img src='<?php echo $instance['weixin']; ?>' height='100' width='100'>"><i class="iconfont icon-wechat icon-lg"></i></span>
	      	</div>
			<?php } ?>
			<?php if($instance['tqqurl']) { ?> 
	      	<div class="col">
			  <a target="_blank" rel="external nofollow" href=http://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&Site=QQ&Menu=yes  data-toggle="tooltip" data-placement="top" title="<?php _e('QQ在线','i_theme') ?>"><i class="iconfont icon-qq icon-lg"></i></a>
	      	</div>
			<?php } ?>
			<?php if($instance['tsinaurl']) { ?> 
	      	<div class="col">
			  <a href="<?php echo $instance['tsinaurl']; ?>" target="_blank"  data-toggle="tooltip" data-placement="top" title="<?php _e('微博','i_theme') ?>" rel="external nofollow"><i class="iconfont icon-weibo icon-lg"></i></a>
			</div>
			<?php } ?>
			<?php if($instance['github']) { ?> 
	      	<div class="col">
			  <a href="<?php echo $instance['github']; ?>" target="_blank"  data-toggle="tooltip" data-placement="top" title="GitHub" rel="external nofollow"><i class="iconfont icon-github icon-lg"></i></a>
	      	</div>
			<?php } ?>
		</div>
		<?php } ?>
      	<div class="desc text-xs mb-3 overflowClip_2"></div>
	    <div class="row no-gutters text-center">
	      	<div class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php echo wp_count_posts('sites')->publish ?></span><small class="d-block text-xs text-muted"><?php _e('收录网站','i_theme') ?></small>
	      	</div>
	      	<div class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php echo wp_count_posts('app')->publish ?></span><small class="d-block text-xs text-muted"><?php _e('收录 App','i_theme') ?></small>
	      	</div>
	      	<div class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span><small class="d-block text-xs text-muted"><?php _e('文章','i_theme') ?></small>
	      	</div>
	      	<div class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php author_posts_views(); ?></span><small class="d-block text-xs text-muted"><?php _e('访客','i_theme') ?></small>
	      	</div>
	    </div>
	</div>
	<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
			$instance = $old_instance;
			$instance = array();
			$instance['show_social_icon'] = $new_instance['show_social_icon']?1:0; 
			$instance['about_img'] = $new_instance['about_img'];
			$instance['about_back'] = $new_instance['about_back'];
			$instance['weixin'] = $new_instance['weixin'];
			$instance['tsinaurl'] = $new_instance['tsinaurl'];
			$instance['github'] = $new_instance['github'];
			$instance['tqqurl'] = $new_instance['tqqurl'];
			return $instance;
		}

	function form($instance) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('weixin' => '' . get_theme_file_uri('/images/wechat_qrcode.png').'"'));
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('about_img' => '' . get_theme_file_uri('/images/avatar.png').'"'));
		$about_img = $instance['about_img'];
		$instance = wp_parse_args((array) $instance, array('about_back' => 'https://i.loli.net/2020/01/11/wHoOcfQGhqvlUkd.jpg'));
		$about_back = $instance['about_back'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => __('输入链接地址','io_setting')));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('github' => 'https://github.com/域名'));
		$github = $instance['github'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '888888'));
		$tqqurl = $instance['tqqurl'];
	?> 

	<p>
		<label for="<?php echo $this->get_field_id('about_img'); ?>"><?php _e('头像：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_img' ); ?>" name="<?php echo $this->get_field_name( 'about_img' ); ?>" type="text" value="<?php echo $about_img; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_back'); ?>"><?php _e('背景图片：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_back' ); ?>" name="<?php echo $this->get_field_name( 'about_back' ); ?>" type="text" value="<?php echo $about_back; ?>" />
	</p>



	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_social_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_social_icon') ); ?>" <?php checked( (bool) $instance["show_social_icon"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_social_icon') ); ?>"><?php _e('显示社交图标','io_setting') ?></label>
	</p>


	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>"><?php _e('微信二维码：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqqurl'); ?>"><?php _e('QQ号：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsinaurl'); ?>"><?php _e('新浪微博地址：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('github'); ?>"><?php _e('GitHub地址：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'github' ); ?>" name="<?php echo $this->get_field_name( 'github' ); ?>" type="text" value="<?php echo $github; ?>" />
	</p>
	<p><?php _e('留空可以隐藏对应项','io_setting') ?></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function about_init() {
	register_widget( 'about' );
}
add_action( 'widgets_init', 'about_init' );

// 关于作者 ------------------------------------------------------
class about_author extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about_author',
			'description' => __( '只显示在正文和作者页面','io_setting' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about_author', __('关于作者','io_setting'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		if ( is_author() || is_single() ){ 
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
     	}
	?>

	<?php if ( is_author() || is_single() ) { ?>
	<?php
		global $wpdb;
		$author_id = get_the_author_meta( 'ID' );
		$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
		$author_url = get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) );
	?>

	<div class="widget-author-cover">
		<div class="media media-2x1">
            <?php if(io_get_option('lazyload')): ?>
            <div class="media-content" data-src="<?php echo $instance['author_back']; ?>"></div>
            <?php else: ?>
            <div class="media-content" style="background-image: url(<?php echo $instance['author_back']; ?>);"></div>
            <?php endif ?>
		</div>
		<div class="widget-author-avatar">  
			<div class="flex-avatar"> 
			<?php echo get_avatar( get_the_author_meta('user_email'), '80' ); ?>	 
	  		</div>	 
	  	</div>
  	</div>
    <div class="widget-author-meta text-center p-4">
      	<div class="h6 mb-3"><?php the_author(); ?><small class="d-block">
			<span class="badge badge-outline-primary mt-2">
			    <?php $user_id=$author_id;//get_post($id)->post_author;
                if(user_can($user_id,'install_plugins')) {
                    echo  __('博主','io_setting');
                }elseif(user_can($user_id,'edit_others_posts')) {
                    echo  __('编辑','io_setting');
                }elseif(user_can($user_id,'publish_posts')) {
                    echo __('作者','io_setting');
                }elseif(user_can($user_id,'delete_posts')) {
                    echo __('投稿者','io_setting');
                }elseif(user_can($user_id,'read')) {
                    echo __('订阅者','io_setting');
                }?>
			</span></small>
		</div>
      	<div class="desc text-xs mb-3 overflowClip_2"></div>
	    <div class="row no-gutters text-center">
	      	<a href="<?php echo $author_url ?>" target="_blank" class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php the_author_posts(); ?></span><small class="d-block text-xs text-muted"><?php _e('文章','i_theme') ?></small>
	      	</a>
	      	<a href="<?php echo $author_url ?>" target="_blank" class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php echo $comment_count;?></span><small class="d-block text-xs text-muted"><?php _e('评论','i_theme') ?></small>
	      	</a>
	      	<a href="<?php echo $author_url ?>" target="_blank" class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php author_posts_views(get_the_author_meta('ID'));?></span><small class="d-block text-xs text-muted"><?php _e('浏览','i_theme') ?></small>
	      	</a>
	      	<a href="<?php echo $author_url ?>" target="_blank" class="col">
	        	<span class="font-theme font-weight-bold text-md"><?php author_posts_likes(get_the_author_meta('ID'));?></span><small class="d-block text-xs text-muted"><?php _e('获赞','i_theme') ?></small>
	      	</a>
	    </div>
	</div>

	<?php } ?>

	<?php
		if ( is_author() || is_single() ){ 
			echo $after_widget;
	 	}
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array(); 
			$instance['author_back'] = $new_instance['author_back'];
			// $instance['author_url'] = $new_instance['author_url'];
			return $instance;
		}
	function form($instance) { 
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('author_back' => 'https://i.loli.net/2020/01/11/wHoOcfQGhqvlUkd.jpg'));
		$author_back = $instance['author_back'];
	?> 
	<p>
		<label for="<?php echo $this->get_field_id('author_back'); ?>"><?php _e('背景图片：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'author_back' ); ?>" name="<?php echo $this->get_field_name( 'author_back' ); ?>" type="text" value="<?php echo $author_back; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function about_author_init() {
	register_widget( 'about_author' );
}
add_action( 'widgets_init', 'about_author_init' );
 

// 热门文章 ------------------------------------------------------
class hot_post_img extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_post_img',
			'description' => __( '调用点击最多的文章，需开启主题设置里的“访问统计”,并有统计数据','io_setting' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_post_img', __('热门文章','io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : '';
		$show_thumbs = !empty($instance['show_thumbs']) ? true : false;
		if ($newWindow) $newWindow = "target='_blank'";
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_ico . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
	?>

	
	
	<div class="card-body"> 
		<div class="list-grid list-rounded my-n2">
		    <?php if( function_exists( 'the_views' ) ): ?>
			 
				<?php get_timespan_most_viewed('post',$number,$days, $show_thumbs, $newWindow, true); ?>
		 
			
			<?php wp_reset_query(); ?>
		    <?php endif; ?>
		</div>
	</div>

	<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_ico'] = strip_tags($new_instance['title_ico']);
		$instance['newWindow'] = isset($new_instance['newWindow']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __('热门文章','io_setting');
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$instance = wp_parse_args((array) $instance, array('title_ico' => 'iconfont icon-chart-pc'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('标题：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p> 
	<p>
		<label for="<?php echo $this->get_field_id('title_ico'); ?>"><?php _e('图标代码：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_ico'); ?>" name="<?php echo $this->get_field_name('title_ico'); ?>" type="text" value="<?php echo $instance['title_ico']; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('在新窗口打开标题链接','io_setting') ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( '显示文章数：' ,'io_setting'); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>"><?php _e('时间限定（天）：','io_setting') ?></label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>"><?php _e('显示缩略图','io_setting') ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function hot_post_img_init() {
	register_widget( 'hot_post_img' );
}
add_action( 'widgets_init', 'hot_post_img_init' );

  

// 热门网址 ------------------------------------------------------
class hot_sites extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_sites',
			'description' => __( '调用点击最多的网址，需开启主题设置里的“访问统计”,并有统计数据','io_setting' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_sites', __('热门网址','io_setting'), $widget_ops);
	}

	public function io_defaults() {
		return array(
			'number'   => 6,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : ''; 
		if ($newWindow) $newWindow = "target='_blank'";
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_ico . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5; 
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
	?>

	
	
	<div class="card-body"> 
		<div class="row-sm">
		    <?php if( function_exists( 'the_views' ) ): ?>
				<?php get_sites_most_viewed($number, $days,  $newWindow, true); ?>
				<?php wp_reset_query(); ?>
		    <?php endif; ?>
		</div>
	</div>

	<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_ico'] = strip_tags($new_instance['title_ico']);
		$instance['newWindow'] = isset($new_instance['newWindow']); 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']); 
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> io_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __('热门网址','io_setting');
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '6')); 
		$instance = wp_parse_args((array) $instance, array('days' => '120'));
		$instance = wp_parse_args((array) $instance, array('title_ico' => 'iconfont icon-chart-pc'));
		$number = strip_tags($instance['number']); 
		$days = strip_tags($instance['days']);
 	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('标题：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p> 
	<p>
		<label for="<?php echo $this->get_field_id('title_ico'); ?>"><?php _e('图标代码：','io_setting') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_ico'); ?>" name="<?php echo $this->get_field_name('title_ico'); ?>" type="text" value="<?php echo $instance['title_ico']; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('在新窗口打开标题链接','io_setting') ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( '显示文章数：' ,'io_setting'); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>  
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>"><?php _e('时间限定（天）：','io_setting') ?></label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function hot_sites_init() {
	register_widget( 'hot_sites' );
}
add_action( 'widgets_init', 'hot_sites_init' );

 
 

add_action('admin_head', 'widget_icon');
function widget_icon() {
?>

<style type="text/css">   
[id*="about"] h3:before,       
[id*="advert"] h3:before,      
[id*="new_cat"] h3:before,  
[id*="new_bulletin"] h3:before,  
[id*="hot_comment"] h3:before, 
[id*="hot_post_img"] h3:before, 
[id*="hot_sites"] h3:before, 
[id*="cx_tag_cloud"] h3:before,   
[id*="related_post"] h3:before,   
[id*="random_post"] h3:before {
	content:'';
	margin-top: -2px;
    margin-right: 8px;
    width: 15px;
    display: inline-block;
	height: 11px;
	vertical-align: middle;
	background:url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMzIiIGhlaWdodD0iMzIiPjxwYXRoIGQ9Ik0xMDA0LjUxMiA0NDQuMjU2bC0xNjAtMjU2Yy0yMy4zNi0zNy40MDgtNjQuMzg0LTYwLjE2LTEwOC41MTItNjAuMTZIMTI4Yy03MC42ODggMC0xMjggNTcuMzEyLTEyOCAxMjh2NTEyYzAgNzAuNjg4IDU3LjMxMiAxMjggMTI4IDEyOGg2MDhjNDQuMTI4IDAgODUuMTItMjIuNzUyIDEwOC41MTItNjAuMTkybDE2MC0yNTZjMjUuOTg0LTQxLjQ0IDI1Ljk4NC05NC4xNDQgMC0xMzUuNjQ4ek05NTAuMjQgNTQ1Ljk4NGwtMTYwIDI1Ni4wNjRjLTExLjc0NCAxOC44MTYtMzIuMDY0IDMwLjA0OC01NC4yNCAzMC4wNDhIMTI4Yy0zNS4yOTYgMC02NC0yOC43MzYtNjQtNjR2LTUxMmMwLTM1LjI5NiAyOC43MDQtNjQgNjQtNjRoNjA4YzIyLjE3NiAwIDQyLjQ5NiAxMS4yNjQgNTQuMjQgMzAuMDQ4bDE2MCAyNTZjMTIuODk2IDIwLjY0IDEyLjg5NiA0Ny4yNjQgMCA2Ny44NHpNNzM2IDQxNi4wOTZjLTUzLjA1NiAwLTk2IDQyLjk3Ni05NiA5NnM0Mi45NDQgOTYgOTYgOTZjNTIuOTkyIDAgOTYtNDMuMDA4IDk2LTk2IDAtNTMuMDI0LTQzLjAwOC05Ni05Ni05NnptMCAxNjAuMDMyYy0zNS4zNiAwLTY0LTI4LjY3Mi02NC02NHMyOC42NC02NCA2NC02NGMzNS4zMjggMCA2NCAyOC42NzIgNjQgNjRzLTI4LjY3MiA2NC02NCA2NHoiIGZpbGw9IiM1NTUiLz48L3N2Zz4=) no-repeat center;
	background-size:100%;
}
</style>
<?php 
}