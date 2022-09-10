<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php while( have_posts() ): the_post();?>
            <div class="row site-content py-4 py-md-5 mb-xl-5 mb-0 mx-xxl-n5">
                <?php get_template_part( 'templates/fx' ); ?>
                <!-- 书籍信息 -->
	    		<div class="col-12 col-sm-5 col-md-4 col-lg-3">
                    <?php 
                    $down_list  = get_post_meta(get_the_ID(), '_down_list', true);  
                    $imgurl = get_post_meta_img(get_the_ID(), '_thumbnail', true);
                    $booktitle = get_the_title();
                    $journal = '';
                    if($sites_type=='periodical'){
                        $j = "";
                        switch (get_post_meta(get_the_ID(), '_journal', true)){
                            case 1: 
                                $j = '季刊';
                                break;
                            case 2: 
                                $j = '双月刊';
                                break;
                            case 3: 
                                $j = '月刊';
                                break;
                            case 6: 
                                $j = '半月刊';
                                break;
                            case 9: 
                                $j = '旬刊';
                                break;
                            case 12: 
                                $j = '周刊';
                                break;
                            default: 
                                $j = '月刊';
                        }
                        $journal = '<span class="badge badge-danger text-sm ml-2 journal">'. $j .'</span>';
                    }
                    ?>
                    <div class="text-center">
                        <img class="rounded shadow" src="<?php echo $imgurl ?>" alt="<?php echo $booktitle ?>" title="<?php echo $booktitle ?>" style="max-height: 350px;">
                        <?php 
	                    $like_count	= get_like(get_the_ID());
	                    $liked		= isset($_COOKIE['liked_' . get_the_ID()]) ? 'liked' : ''; 
                        ?>
                        <div class="tool-actions text-center mt-md-4">
                            <a href="javascript:;" post_like data-id="<?php echo get_the_ID() ?>" class=" btn btn-like btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2 <?php echo $liked ?>" data-toggle="tooltip" data-placement="top" title="<?php _e('点赞','i_theme') ?>">
                                <span class="flex-column text-height-xs">
                                    <i class="icon-lg iconfont icon-like"></i>
                                    <small class="like-count text-xs mt-1"><?php echo $like_count ?></small>
                                </span>
                            </a>
                            <a href="javascript:;" class="btn-share-toggler btn btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2" data-toggle="tooltip" data-placement="top" title="<?php _e('浏览','i_theme') ?>">
                                <span class="flex-column text-height-xs">
                                    <i class="icon-lg iconfont icon-chakan"></i>
                                    <small class="share-count text-xs mt-1"><?php echo function_exists('the_views')? the_views(false) :  '0' ; ?></small>
                                </span>
                            </a> 
                        </div>
                    </div>
	    		</div>
	    		<div class="col mt-4 mt-sm-0">
	    			<div class="site-body text-sm">
                        <?php 
                        $terms = get_the_terms( get_the_ID(), 'books' ); 
                        if( !empty( $terms ) ){
                            foreach( $terms as $term ){
                                 if($term->parent != 0){
                                      $parent_category = get_term( $term->parent );
                                      echo '<a class="btn-cat custom_btn-d mb-2" href="' . esc_url( get_category_link($parent_category->term_id)) . '">' . esc_html($parent_category->name) . '</a>';
                                      echo '<i class="iconfont icon-arrow-r-m mr-n1 custom-piece_c" style="font-size:50%;color:#f1404b;vertical-align:0.075rem"></i>';
                                      break;
                                 }
                            } 
                        	foreach( $terms as $term ){
                                $name = $term->name;
                                $link = esc_url( get_term_link( $term, 'favorites' ) );
                                echo " <a class='btn-cat custom_btn-d mb-2' href='$link'>".$name."</a>";
                            }
                        }  
                        ?>
                        <div class="site-name h3 my-3"><?php echo $booktitle.$journal ?></div>
                        <div class="mt-n2"> 
                            <p><?php echo mb_strimwidth(htmlspecialchars(get_post_meta(get_the_ID(), '_summary', true)), 0, 170,"……");?></p> 
                            <div class="book-info text-sm text-muted">
								<ul>
                                    <?php foreach (get_post_meta(get_the_ID(), '_books_data', true) as  $value) {
                                        echo '<li class="my-2"><span class="info-title mr-3">'.$value['term'].'</span>'.$value['detail'].'</li>';
                                    }
                                    the_terms( get_the_ID(), 'booktag','<li class="my-2"><span class="info-title mr-3">'.__('标签','i_theme').'</span><span class="mr-1">', '<i class="iconfont icon-wailian text-xs"></i></span> <span class="mr-1">', '<i class="iconfont icon-wailian text-xs"></i></span></li>' ); 
                                    the_terms( get_the_ID(), 'series','<li class="my-2"><span class="info-title mr-3">'.__('丛书','i_theme').'</span><span class="mr-1">', '<i class="iconfont icon-wailian text-xs"></i></span> <span class="mr-1">', '<i class="iconfont icon-wailian text-xs"></i></span></li>' ); ?>									
                                </ul>			
							</div>
	    					<div class="site-go mt-3">
                                <?php if($buy_list = get_post_meta(get_the_ID(), '_buy_list', true)) foreach ($buy_list as  $value) {
                                    echo '
                                    <a href="'.home_url().'/go/?url='.base64_encode($value['url']).'" class="btn"  title=""><span>'.$value['term'].'(￥'.($value['price']?:'0').')</span></a>';
                                }
                                if( $down_list ) { ?>
                                <a href="javascript:" class="btn btn-arrow qr-img"  title="" data-id="0" data-toggle="modal" data-target="#book-down-modal"><span><?php echo __('下载电子书','i_theme') ?><i class="iconfont icon-down"></i></span></a>
                                <?php } ?>
                            </div>
	    				</div>
	    			</div>
                </div>
                <!-- 书籍信息 end -->
            </div>
            <div class="panel site-content card transparent"> 
		        <div class="card-body p-0">
					<div class="apd-bg">
            	        <?php if(io_get_option('ad_app_s')) echo '<div class="apd apd-right">' . stripslashes( io_get_option('ad_app') ) . '</div>'; ?>
            	    </div> 
                    <div class="panel-body my-4 ">
                            <?php  
                            $contentinfo = get_the_content();
                            if( $contentinfo ){
                                the_content();   
                            }else{
                                echo htmlspecialchars(get_post_meta(get_the_ID(), '_summary', true));
                            }
                            ?>
                    </div>
                        <?php edit_post_link(__('编辑','i_theme'), '<span class="edit-link">', '</span>' ); ?>
                </div>
            </div>
            <?php if($down_list){ ?>
            <div class="modal fade search-modal" id="book-down-modal">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">  
                        <div class="modal-body down_body"> 
                            <h4 class="h6"><?php _e('下载地址: ','i_theme') ?><?php echo $booktitle ?></h4>
                            <div class="down_btn_list my-4">
                                <div class="row">
                                    <div class="col-6 col-md-7">描叙</div>
                                    <div class="col-2 col-md-2" style="white-space: nowrap;">提取码</div>
                                    <div class="col-4 col-md-3 text-right">下载</div>
                                </div>
                                <div class="col-12 line-thead my-3" style="height:1px;background: rgba(136, 136, 136, 0.4);"></div>
								<?php  
									for($i=0;$i<count($down_list);$i++){
                                        echo '<div class="row">';
                                        echo '<div class="col-6 col-md-7">'. ($down_list[$i]['info']?:'无') .'</div>';
                                        echo '<div class="col-2 col-md-2" style="white-space: nowrap;">'. ($down_list[$i]['tqm']?:'无') .'</div>';
                                        echo '<div class="col-4 col-md-3 text-right"><a class="btn btn-danger custom_btn-d py-0 px-1 mx-auto down_count text-sm" href="'.home_url().'/go/?url='.base64_encode($down_list[$i]['url']).'" target="_blank" data-id="'. get_the_ID() .'" data-action="down_count" data-mmid="down-mm-'.$i.'">'.$down_list[$i]['name'].'</a></div>';
                                        if($down_list[$i]['tqm']) echo '<input type="text" style="width:1px;position:absolute;height:1px;background:transparent;border:0px solid transparent" name="down-mm-'.$i.'" value="'.$down_list[$i]['tqm'].'" id="down-mm-'.$i.'">';
                                        echo '</div>';
                                        echo '<div class="col-12 line-thead my-3" style="height:1px;background: rgba(136, 136, 136, 0.2);"></div>';
                                    }
								?>
                            </div>
                            <?php if(io_get_option('ad_down_popup_s')) echo '<div class="apd apd-footer d-none d-md-block mb-4">' . stripslashes( io_get_option('ad_down_popup') ) . '</div>'; ?> 
                            <div class="statement p-3"><p></p>
                                <i class="iconfont icon-statement icon-2x mr-2" style="vertical-align: middle;"></i><strong><?php _e('声明：','i_theme') ?></strong>
				                <div class="text-sm mt-2" style="margin-left: 39px;"><?php echo io_get_option('down_statement') ?></div>
				            </div>
                        </div>  
                        <div style="position: absolute;bottom: -40px;width: 100%;text-align: center;"><a href="javascript:" data-dismiss="modal"><i class="iconfont icon-close-circle icon-2x" style="color: #fff;"></i></a></div>
                    </div>
                </div>  
            </div> 
            <?php } ?>
<?php endwhile; ?>
