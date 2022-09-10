<?php
/*
 * 如果当前帖子受密码保护，而访问者尚未输入密码，将不加载评论。
 */
if ( post_password_required() ) {
	return;
}
?>

<!-- comments -->
<?php if(io_get_option('ad_c')) { ?>
<div class="post-apd mt-4"><?php echo stripslashes( io_get_option('ad_c_c') ); ?></div>
<?php } ?>
<div id="comments" class="comments">
	<h1 id="comments-list-title" class="comments-title h5 mx-1 my-4">
		<i class="iconfont icon-comment"></i>
		<span class="noticom">
			<?php comments_popup_link(__('暂无评论','i_theme'), __('1 条评论','i_theme'), __('% 条评论','i_theme'),'comments-title'); ?> 
		</span>
	</h1> 
	<div class="card">
		<div class="card-body"> 
			<?php if(comments_open() != false) {?>
			<div id="respond_box">
				<div id="respond" class="comment-respond">
					<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
					<div class="rounded bg-light text-center p-4 mb-4">
						<div class="text-muted text-sm mb-2"><?php _e('您必须登录才能参与评论！','i_theme') ?></div>
						<a class="btn btn-light btn-sm btn-rounded" href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('立即登录','i_theme') ?></a>
					</div>
    				<?php else : ?>
    				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="text-sm mb-4">	
						<div class="visitor-avatar d-flex flex-fill mb-2">
							<?php if ( $user_ID )://判断是否登录，获取admin头像 ?>
							<?php global $current_user;wp_get_current_user(); ?>
							<img class="v-avatar rounded-circle" src="<?php echo get_avatar_url( $current_user->user_email, array('size'=>64));?>">
							<p class="loginby ml-2" style="line-height: 32px;"><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>&nbsp;&nbsp;<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('退出','i_theme') ?>">[ <?php _e( '退出','i_theme') ?> ]</a></p>
							<?php elseif($comment_author_email): ?>		
							<img class="v-avatar rounded-circle" src="<?php 
								echo get_avatar_url( $comment_author_email, array('size'=>64));
							?>">
							<?php else: ?>
							<img class="v-avatar rounded-circle" src="<?php bloginfo('template_url'); ?>/images/gravatar.jpg">
							<?php endif; ?>
						</div> 
						<div class="comment-textarea mb-3">
							<textarea name="comment" id="comment" class="form-control" placeholder="<?php _e('输入评论内容...','i_theme') ?>" tabindex="4" cols="50" rows="3"></textarea>
						</div>
						<?php if ( ! $user_ID ): ?>	
						<div id="comment-author-info" class="row  row-sm">
							<div class="col-sm-6 col-lg-4 mb-3"><input type="text" name="author" id="author" class="form-control" value="<?php echo $comment_author; ?>" size="22" placeholder="<?php _e('昵称','i_theme') ?>" tabindex="2"/></div>	
							<div class="col-sm-6 col-lg-4 mb-3"><input type="text" name="email" id="email" class="form-control" value="<?php echo $comment_author_email; ?>" size="22" placeholder="<?php _e('邮箱','i_theme') ?>" tabindex="3" /></div>
							<div class="col-sm-12 col-lg-4 mb-3"><input type="text" name="url" id="url" class="form-control" value="<?php echo $comment_author_url; ?>" size="22"placeholder="<?php _e('网址','i_theme') ?>"  tabindex="4" /></div>
						</div>
						<?php endif; ?>
						<div class="com-footer text-right">
							<?php wp_nonce_field('comment_ticket'); ?>
							<a rel="nofollow" id="cancel-comment-reply-link" style="display: none;" href="javascript:;" class="btn btn-light custom_btn-outline mx-2"><?php _e('再想想','i_theme') ?></a>
							<?php if( io_get_option('io_captcha')['tcaptcha_007'] && io_get_option('io_captcha')['comment_007'] ) { ?>
							<input type="hidden" id="c_tcaptcha_ticket" name="tcaptcha_ticket" value="" />
                            <input type="hidden" id="c_tcaptcha_randstr" name="tcaptcha_randstr" value="" />
							<input class="btn btn-dark custom_btn-d" name="submit" type="button" id="TencentCaptcha" tabindex="5" value="<?php _e('发表评论','i_theme') ?>" data-appid="<?php echo io_get_option('io_captcha')['appid_007'] ?>" data-cbfn="commentsTicket"/>
                            <?php } else { ?>
							<input class="btn btn-dark custom_btn-d" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('发表评论','i_theme') ?>"/>
							<?php } ?>
							<?php comment_id_fields(); ?>
						</div>
						<?php do_action('comment_form', $post->ID); ?>
    				</form>
					<div class="clear"></div>
    				<?php endif; ?>
  				</div>
  			</div>	
			<?php } else { ?>
			<div class="commclose card  mb-4"><div class="card-body text-center color-d"><?php _e('评论已关闭...','i_theme') ?></div></div>
			<?php } ?>
			<div id="loading-comments"><span></span></div>
			<?php if(have_comments()) { ?>
			<ul class="comment-list">
				<?php wp_list_comments('type=comment&callback=my_comment_format&max_depth=10000'); ?>	
			</ul>
		 		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
				<nav id="comments-navi"  class="text-center my-3">
				<?php paginate_comments_links('prev_text=<i class="iconfont icon-arrow-l"></i>&next_text=<i class="iconfont icon-arrow-r"></i>');?>
				</nav>	
				<?php } ?>
			<?php }else { ?>
			<div class="not-comment card"><div class="card-body nothing text-center color-d"><?php _e('暂无评论...','i_theme') ?></div></div>
			<?php } ?>		
		</div>	
	</div>
</div>
<!-- comments end -->
