<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
include( get_theme_file_path('/templates/sidebar-nav.php') );
?>
<div class="main-content flex-fill single">
<?php get_template_part( 'templates/header','banner' ); ?>
<div id="content" class="container my-4 my-md-5">
                <?php 
                $sites_type = get_post_meta(get_the_ID(), '_sites_type', true);
                if($sites_type == "down") include( get_theme_file_path('/templates/content-down.php') );
                else include( get_theme_file_path('/templates/content-site.php') );
                ?>

                <h4 class="text-gray text-lg my-4"><i class="site-tag iconfont icon-tag icon-lg mr-1" ></i><?php _e('相关导航','i_theme') ?></h4>
                <div class="row mb-n4 customize-site"> 
                    <?php
                    $post_num = 6;
                    $i = 0;
                    if ($i < $post_num) {
                        $custom_taxterms = wp_get_object_terms( $post->ID,'favorites', array('fields' => 'ids') );
                        $args = array(
                        'post_type' => 'sites',// 文章类型
                        'post_status' => 'publish',
                        'posts_per_page' => 6, // 文章数量
                        'orderby' => 'rand', // 随机排序
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'favorites', // 分类法
                                'field' => 'id',
                                'terms' => $custom_taxterms
                            )
                        ),
                        'post__not_in' => array ($post->ID), // 排除当前文章
                        );
                        $related_items = new WP_Query( $args ); 
                        if ($related_items->have_posts()) :
                            while ( $related_items->have_posts() ) : $related_items->the_post();
                            $link_url = get_post_meta($post->ID, '_sites_link', true); 
                            $default_ico = get_theme_file_uri('/images/favicon.png');
                            //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)):
                            ?>
                                <div class="url-card <?php echo io_get_option('two_columns')?"col-6":"" ?> col-sm-6 col-md-4 <?php echo before_class($post->ID) ?>">
                                <?php include( get_theme_file_path('/templates/card-site.php') ); ?>
                                </div>
                            <?php //endif; 
                            $i++; endwhile; endif; wp_reset_postdata();
                    }
                    if ($i == 0) echo '<div class="col-lg-12"><div class="nothing">'.__('没有相关内容!','i_theme').'</div></div>';
                    ?>
                </div>
    	        <?php 
    	        if ( comments_open() || get_comments_number() ) :
			    	comments_template();
    	        endif; 
    	        ?>
</div>
<script type='text/javascript'>
    $(document).ready(function(){
        if($(".security_check.d-none").length>0) {
            $("#check_s").show();
            $("#country").show();
            if(!$("#country").hasClass('loadcountry'))
                ipanalysis($(".security_check.d-none").data('ip'));
        }
        else{
            $("#check_s").remove();
            $("#country:not(.loadcountry)").remove();
        }
    });
    var tim=1;
    var timer = setInterval("tim++",100); 
    function check(url){
        var msg ="";
        if(tim>100) { 
            clearInterval(timer);
            $.getJSON('//api.iowen.cn/webinfo/get.php?url='+url,function(data){
                if(data.code==0){
                    msg = '<i class="iconfont icon-crying-circle mr-1" style="color:#f12345"></i><?php _e('链接超时，网站可能下线了，请点击直达试试','i_theme') ?> <i class="iconfont icon-crying"></i>';
                    updateStatus(false);
                }
                else{
                    msg = '<i class="iconfont icon-smiley-circle mr-1" style="color:#f1b223"></i><?php _e('墙外世界需要梯子','i_theme') ?> <i class="iconfont icon-smiley"></i>';
                    updateStatus(true);
                }
                $("#check_s").html(msg); 
            }).fail(function () {
                msg = '<i class="iconfont icon-crying-circle mr-1" style="color:#f12345"></i><?php _e('链接超时，网站可能下线了，请点击直达试试','i_theme') ?> <i class="iconfont icon-crying"></i>';
                $("#check_s").html(msg); 
                updateStatus(false);
            });
        }
        else {
            msg = '<i class="iconfont icon-smiley-circle mr-1" style="color:#26f123"></i><?php _e('链接成功:','i_theme') ?>' + tim/10 + '<?php _e('秒','i_theme') ?>';
            $("#check_s").html(msg); 
            updateStatus(true);
            clearInterval(timer);
        }
    } 
    function ipanalysis(ip){
        $.getJSON('//api.iotheme.cn/ip/get.php?ip='+ip,function(data){
            if(data.status == 'success'){
                $("#country").html('<i class="iconfont icon-globe mr-1"></i>'+ data.country); 
                $.ajax({
		        	url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",  
		        	data : {
		        		action: "io_set_country",
		        		country: data.country,
		        		id: <?php echo $post->ID ?>
		        	},
		        	type : 'POST',
                    error:function(){ 
                        console.log('<?php _e('网络错误','i_theme') ?> --.'); 
                    }
		        });
            }
            else
                $("#country").html('<i class="iconfont icon-crying-circle mr-1"></i><?php _e('查询失败','i_theme') ?>'); 
        }).fail(function () {
            $("#country").html('<i class="iconfont icon-crying-circle"></i>'); 
        });
    }
    function updateStatus(isInvalid){ 
		$.ajax({
			url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",  
			data : {
				action: "link_failed",
				is_inv: isInvalid,
				post_id: <?php echo $post->ID ?>
			},
			type : 'POST',
            error:function(){ 
                console.log('<?php _e('网络错误','i_theme') ?> --.'); 
            }
		});
    }
</script>
<?php get_footer(); ?>