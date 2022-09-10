<?php if ( ! defined( 'ABSPATH' ) ) { exit; }?>
            <?php if(io_get_option('ad_footer_s')) echo '<div class="container apd apd-footer">' . stripslashes( io_get_option('ad_footer') ) . '</div>'; ?> 
            <footer class="main-footer footer-type-1 text-xs">
                <div id="footer-tools" class="d-flex flex-column">
                    <a href="javascript:" id="go-to-up" class="btn rounded-circle go-up m-1" rel="go-top">
                        <i class="iconfont icon-to-up"></i>
                    </a>
                    <?php if( io_get_option('search_position') && in_array("tool",io_get_option('search_position')) ){ ?>
                    <a href="javascript:" data-toggle="modal" data-target="#search-modal" class="btn rounded-circle m-1" rel="search">
                        <i class="iconfont icon-search"></i>
                    </a>
                    <?php } ?>
                    <?php if(io_get_option('weather') && io_get_option('weather_location')=='footer'){ ?>
                    <!-- 天气  -->
                    <div class="btn rounded-circle weather m-1">
                        <div id="he-plugin-simple" style="display: contents;"></div>
                        <script>WIDGET = {CONFIG: {"modules": "02","background": 5,"tmpColor": "888","tmpSize": 14,"cityColor": "888","citySize": 14,"aqiSize": 14,"weatherIconSize": 24,"alertIconSize": 18,"padding": "7px 2px 7px 2px","shadow": "1","language": "auto","borderRadius": 5,"fixed": "false","vertical": "middle","horizontal": "left","key": "a922adf8928b4ac1ae7a31ae7375e191"}}</script>
                        <script src="//widget.heweather.net/simple/static/js/he-simple-common.js?v=1.1"></script>
                    </div>
                    <!-- 天气 end -->
                    <?php } ?>
                    <a href="javascript:" id="switch-mode" class="btn rounded-circle switch-dark-mode m-1" data-toggle="tooltip" data-placement="left" title="<?php _e('夜间模式','i_theme') ?>">
                        <i class="mode-ico iconfont icon-light"></i>
                    </a>
                </div>
                <div class="footer-inner">
                    <div class="footer-text">
                        <?php if(io_get_option('footer_copyright')) : 
                           echo io_get_option('footer_copyright');
                        ?>
                        <?php else: ?>
                        Copyright © <?php echo date('Y') ?> <?php bloginfo('name'); ?> <?php if(io_get_option('icp')) echo '<a href="http://www.beian.miit.gov.cn/" target="_blank" rel="link noopener">' . io_get_option('icp') . '</a>'?>
                        &nbsp;&nbsp;Design by <a href="https://www.iowen.cn" target="_blank"><strong>一为</strong></a>&nbsp;&nbsp;<?php echo io_get_option('footer_statistics') ?>
                        <?php endif; ?>
                    </div>
                </div>
            </footer>
        </div><!-- main-content end -->
    </div><!-- page-container end -->
<?php if(io_get_option('search_position') && ( in_array("top",io_get_option('search_position')) || in_array("tool",io_get_option('search_position')) ) ){ ?>  
<div class="modal fade search-modal" id="search-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">  
            <div class="modal-body">
                <?php get_template_part( 'templates/search/modal' ); ?>  
                <div class="px-1 mb-3"><i class="text-xl iconfont icon-hot mr-1" style="color:#f1404b;"></i><span class="h6"><?php _e('热门推荐：','i_theme') ?> </span></div>
                <div class="mb-3">
                    <?php wp_menu("search_menu") ?>
                </div>
            </div>  
            <div style="position: absolute;bottom: -40px;width: 100%;text-align: center;"><a href="javascript:" data-dismiss="modal"><i class="iconfont icon-close-circle icon-2x" style="color: #fff;"></i></a></div>
        </div>
    </div>  
</div>
<?php } ?>
<?php wp_footer(); ?>
<?php if(io_get_option('loading_fx')) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        var siteWelcome = document.getElementById('loading');
        siteWelcome.classList.add('close');
        setTimeout(function() {
            siteWelcome.remove();
        }, 600);
    });
</script>
<?php } ?>
<?php if (is_home() || is_front_page()): ?>
    <script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function () { 
            $('a.smooth[href="'+window.location.hash+'"]').click();
        }, 300);
        $(document).on('click','a.smooth',function(ev) {
            ev.preventDefault();
            if($('#sidebar').hasClass('show')){
                $('#sidebar').modal('toggle');
            }
            $("html, body").animate({
                scrollTop: $($(this).attr("href")).offset().top - 90
            }, {
                duration: 500,
                easing: "swing"
            });
            if($(this).hasClass('go-search-btn')){
                $('#search-text').focus();
            }
            <?php if(io_get_option("tab_type")) { ?>
            var menu =  $("a"+$(this).attr("href"));
            menu.click();
            toTarget(menu.parent().parent());
            <?php } ?>
        });
        $(document).on('click','a.tab-noajax',function(ev) { 
            var url = $(this).data('link');
            if(url)
                $(this).parents('.d-flex.flex-fill.flex-tab').children('.btn-move.tab-move').show().attr('href', url);
            else
                $(this).parents('.d-flex.flex-fill.flex-tab').children('.btn-move.tab-move').hide();
        });
    });
    </script>
<?php else: ?>
    <script type="text/javascript">
    $(document).on('click','a.smooth-n',function(ev) {
        ev.preventDefault();
        $("html, body").animate({
            scrollTop: $($(this).attr("href")).offset().top - 90
        }, {
            duration: 500,
            easing: "swing"
        });
    });
    </script>
<?php endif; ?>
<!-- 自定义代码 -->
<?php echo io_get_option('code_2_footer');?>
<!-- end 自定义代码 -->
</body>
</html>
