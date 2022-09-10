<?php
/* -------------------------------------
 * By... 内容都是网上找的，一为只是做了整合
 * 文章地址：https://www.iowen.cn/wordpress-pinglunshenhepinglunhuifutongzhizhanghubiangengtongzhiwenzhangxiugaixinwenzhangyoujiantongai
 * 设置：
 * 1、模板提供两种头样式请到下方设置邮件头的样式
 * 2、邮件底部有广告位，可设置内容或者删除
 *-----------------------------------*/
 

/*-------------------------------------
 * 头部风格，true 为居中, false 为标准
 * 效果查看：https://www.iowen.cn/demo/email-notify.html
 * 效果演示页的第一个为居中，其他的都为标准风格
 * 问题：标准风格右上角的菜单按钮在QQ手机邮箱里面没有隐藏，导致排版有点问题
 *      他没有生效 @media only screen and (max-width: 500px)
 *      在浏览器上表现正常。
 *-----------------------------------*/
$style_center = false;


//定义界面顶部区域内容,请注意修改您的主题目录
$email_headertop_center = '
    <div class="emailpaged" style="background-color: #f2f5f8;">
        <div class="emailcontent" style="width:96%;max-width:720px;text-align: left;margin: 0 auto;padding-top: 20px;padding-bottom: 20px">
            <div class="emailtitle">
                <div style="position: relative;margin:0;">
                    <div style="text-align: center;margin-bottom: -20px;"> <img src="'.io_get_option('logo_normal_light')['url'].'"  title="' . get_option("blogname") . '" style="display:inline;margin-bottom:20px;max-height:40px;width:auto" border="0"> </div>
                    
                    <div style="line-height:40px;font-size:12px;text-align: center;">
                        <a href="' . get_bloginfo('url') . '" title="' . get_option("blogname") . '" style="color:#222222;text-decoration:none;padding:0 6px;">首页</a>
                        <a href="' . get_permalink(io_get_option('blog_pages')) . '" title="最新文章" style="color:#222222;text-decoration:none;padding:0 6px;">最新文章</a>
                        <a href="' . get_bloginfo('url') . '" title="最新收录" style="color:#222222;text-decoration:none;padding:0 6px;">最新收录</a>
                    </div>
                    <div class="clear" style="clear: both;display: block;"></div>
                </div>
                <div style="margin: 0;color: #2f2f2f; background: #fff;font-size: 20px;padding: 20px 0;text-align: center;border-bottom: 1px solid #eeeeee;">
';
$email_headertop = '
    <div class="emailpaged" style="background-color: #f2f5f8;">
        <div class="emailcontent" style="width:96%;max-width:720px;text-align: left;margin: 0 auto;padding-top: 80px;padding-bottom: 20px">
            <div class="emailtitle">
                <div style="background: #fff;position: relative;margin:0;border-bottom: 1px solid #eeeeee;">
                    <div style="float: left;"><div style="height:60px;padding: 15px 0 0 20px;"><img src="'.io_get_option('logo_small_light')['url'].'"  title="' . get_option("blogname") . '" style="display:inline;margin:0;max-height:45px;width: auto;" border="0"></div></div>     
                    <div class="imenu" style="float:right;position:absolute;right:0;"><div style="height:60px;line-height:60px;padding: 10px 20px 0 0;font-size:12px;">
                        <a href="' . get_bloginfo('url') . '" title="' . get_option("blogname") . '" style="color:#222222;text-decoration:none;padding:0 6px;">首页</a>
                        <a href="' . get_permalink(io_get_option('blog_pages')) . '" title="最新文章" style="color:#222222;text-decoration:none;padding:0 6px;">最新文章</a> 
                        <a href="' . get_bloginfo('url') . '" title="最新收录" style="color:#222222;text-decoration:none;padding:0 6px;">最新收录</a>
                    </div></div>
                    <div class="clear" style="clear: both;display: block;"></div>
                </div>
                <div class="ititle" style="color: #2f2f2f;font-size: 17px;padding-left: 30px;padding-top: 30px;background: #fff;">
';

/*---------------
**----标题空间----
**-------------*/

$email_headerbot_center = '
                </div>
                <div class="emailtext" style="background:#fff;padding:20px 32px 40px;">
';
$email_headerbot = '
                    </div>
                <div class="emailtext" style="background:#fff;padding:20px 32px 40px;">
';
if($style_center){
    define ('EMAIL_HEADER_TOP',  $email_headertop_center );
    define ('EMAIL_HEADER_BOT', $email_headerbot_center );
}
else{
    define ('EMAIL_HEADER_TOP',  $email_headertop );
    define ('EMAIL_HEADER_BOT', $email_headerbot );
}
 
//定义界面底部区域内容，---[请注意修改下面广告图片地址,不需要请删除 <div class="emailad" ......</div> 这 4 行]---
$email_footer = '
				</div>
                <div class="copyright" style="font-size:13px;line-height: 1.5;color: #777777;padding: 5px 0;text-align:center;">
                    <p style="margin:10px 0 0;">(此为系统自动发送邮件, 请勿回复！)</p>
                    <p style="margin:10px 0 0;">© '. date("Y") . ' · 邮件来自 · <a href="' . get_bloginfo('url') . '" style="color:#51a0e3;text-decoration:none">' . get_option("blogname") . '</a></p>
                </div>
            </div>
        </div>
    </div>
';
define ('emailfooter', $email_footer );
 
//修改网站默认发信人以及邮箱
function new_from_name($email){
    $wp_from_name = get_option('blogname');
    return $wp_from_name;
}
function new_from_email($email) {
    $wp_from_email = get_option('admin_email');
    return $wp_from_email;
}
add_filter('wp_mail_from_name', 'new_from_name');
add_filter('wp_mail_from', 'new_from_email');

//评论通过 通知评论者
add_action('comment_unapproved_to_approved', 'iwill_comment_approved');
function iwill_comment_approved($comment) {
  if(is_email($comment->comment_author_email)) {
    $post_link = get_permalink($comment->comment_post_ID);
    // 邮件标题，可自行更改
    $title = '您在 [' . get_option('blogname') . '] 的评论已通过审核';
    // 邮件内容，按需更改。如果不懂改，可以给我留言
    $body = EMAIL_HEADER_TOP.'留言审核通过通知'.EMAIL_HEADER_BOT.'
        <p style="color: #6e6e6e;font-size:13px;line-height:24px;">您在' . get_option('blogname') . '《<a href="'.$post_link.'">'.get_the_title($comment->comment_post_ID).'</a>》发表的评论：</p>
        <p style="color: #6e6e6e;font-size:13px;line-height:24px;padding: 15px 20px;background:#f8f8f8;margin:0px;border: 1px solid #eee;">'.$comment->comment_content.'</p>
        <p style="color: #6e6e6e;font-size:13px;line-height:24px;">已通过管理员审核并显示。<br />
        您可在此查看您的评论：<a href="'.get_comment_link( $comment->comment_ID ).'">前往查看</a></p>'.emailfooter;
    @wp_mail($comment->comment_author_email, $title, $body, "Content-Type: text/html; charset=UTF-8");        
  }
}

/* 邮件评论回复美化版 */
function comment_mail_notify($comment_id) {
    $admin_email = get_bloginfo ('admin_email'); 
    $admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
    $comment = get_comment($comment_id);
    $comment_author_email = trim($comment->comment_author_email);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    $to = $parent_id ? trim(get_comment($parent_id)->comment_author_email) : '';
    $spam_confirmed = $comment->comment_approved;
    if (($parent_id != '') && ($spam_confirmed != 'spam') && ($to != $comment_author_email)) {
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $subject = '您在 [' . get_option("blogname") . '] 的留言有了新回复';
        $message = EMAIL_HEADER_TOP.'您在' . get_option("blogname") . '上的留言有回复啦！'.EMAIL_HEADER_BOT.'
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;">' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;">您于 '. trim(get_comment($parent_id)->comment_date) . ' 在文章《' . get_the_title($comment->comment_post_ID) . '》上发表评论:<br />
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;padding: 15px 20px;background:#f8f8f8;margin:0px;border: 1px solid #eee;">'. trim(get_comment($parent_id)->comment_content) . '</p>
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;">' . trim($comment->comment_author) . ' 于' . trim($comment->comment_date) . ' 给您的回复如下:<br />
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;padding: 15px 20px;background:#f8f8f8;margin:0px;border: 1px solid #eee;">'. trim($comment->comment_content) . '</p>
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;">你可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id, array('type' => 'comment'))) . '">查看完整内容</a></p>
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;">欢迎再度光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
            '.emailfooter;
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail( $to, $subject, $message, $headers );
    }
    //文章新评论给管理员通知
    if($parent_id == '' &&  $comment_author_email != $admin_email && $admin_notify == '1'){
        $wp_email = '';
        $subject = ' [' . get_option("blogname") . '] 上的文章有了新的评论';
        $message = EMAIL_HEADER_TOP. get_option("blogname") . '上有新的评论'.EMAIL_HEADER_BOT.'
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;">' . trim($comment->comment_author) . ' 在文章《<a href="' . htmlspecialchars(get_comment_link($comment_id)) . '" target="_blank">' . get_the_title($comment->comment_post_ID) . '</a>》中发表了回复，快去看看吧：<br></p>
            <p style="color: #6e6e6e;font-size:13px;line-height:24px;padding: 15px 20px;background:#f8f8f8;margin:0px;border: 1px solid #eee;">'. trim($comment->comment_content) . '</p>
            '.emailfooter;
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail( $admin_email, $subject, $message, $headers );
    }
  }
  add_action('comment_post', 'comment_mail_notify');

//用户更新账户通知用户
function user_profile_update( $user_id ) {
        $site_url = get_bloginfo('wpurl');
        $site_name = get_bloginfo('wpname');
        $user_info = get_userdata( $user_id );
        $to = $user_info->user_email;
        $subject = "".$site_name."账户更新";
        $message = EMAIL_HEADER_TOP.'您在' .$site_name. '账户资料修改成功！'.EMAIL_HEADER_BOT.'<p style="color: #6e6e6e;font-size:13px;line-height:24px;">亲爱的 ' .$user_info->display_name . '<br/>您的资料修改成功!<br/>谢谢您的光临</p>'.emailfooter;

        wp_mail( $to, $subject, $message, "Content-Type: text/html; charset=UTF-8");
}
add_action( 'profile_update', 'user_profile_update', 10, 2);

//用户账户被删除通知用户
function iwilling_delete_user( $user_id ) {
    global $wpdb;
    $site_name = get_bloginfo('name');
    $user_obj = get_userdata( $user_id );
    $email = $user_obj->user_email;
    $subject = "帐号删除提示：".$site_name."";
    $message = EMAIL_HEADER_TOP.'您在' .$site_name. '的账户已被管理员删除！'.EMAIL_HEADER_BOT.'<p style="color: #6e6e6e;font-size:13px;line-height:24px;">如果您对本次操作有什么异议，请联系管理员反馈！<br/>我们会在第一时间处理您反馈的问题.</p>'.emailfooter;
    wp_mail( $email, $subject, $message, "Content-Type: text/html; charset=UTF-8");
}
add_action( 'delete_user', 'iwilling_delete_user' );

 