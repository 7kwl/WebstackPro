<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
    <div class="col  col-md  col-lg">
        <div class="card hot-card mb-2">
            <div class="card-header widget-header d-flex" style="background-color:transparent;border-bottom:0">
                <span><i class="mr-2 iconfont <?php echo $ico ?>" style="color:<?php echo $color ?>"></i><?php echo $title ?></span>
                <span class="ml-auto d-none d-md-block"><?php echo $slug ?></span>
            </div>
            <div class="card-body pb-3 pt-0">
                <div class="overflow-auto hot-body">
                    <div id="hot_news-<?php echo $t ?>">
                    </div>
                </div>
                <div class="d-flex text-xs text-muted pt-2 mb-n2">
                    <div class="flex-fill"></div>
                    <span><a href= "javascript:" id="hot-lod-<?php echo $t ?>" title='刷新' style="color:#6c757d" ><i class="iconfont icon-refresh icon-lg"></i></a></span>
                </div>
            </div>
        </div>
        <div id="hot-loading-<?php echo $t ?>" class="ajax-loading text-center rounded" style="position:absolute;display:flex;width:100%;left:0;top:0;bottom:.5rem;background:rgba(125,125,125,.5)"><div id="hot-success-<?php echo $t ?>" class="col align-self-center"><i class="iconfont icon-loading icon-spin icon-2x"></i></div></div>
    </div>
<script>
(function($){ 
    let apiurl =  "<?php echo $type ?>" ;
    getList(apiurl);
    
    $('#hot-lod-<?php echo $t ?>').on('click', function() {
        $(this).children('i').addClass('icon-spin');
        $("#hot-success-<?php echo $t ?>").html('<i class="iconfont icon-loading icon-spin icon-2x"></i>');
        $("#hot-loading-<?php echo $t ?>").fadeIn(200); 
        getList(apiurl);
    });
    function getList(_type){
        $.post("//api.iotheme.cn/hot/get.php", { type: _type ,key:"<?php echo io_get_option('iowen_key') ?>" },function(data,status){ 
            let html = '';
            var index = 1;
            for(let item of data) {
                html += '<div class="text-sm mb-3">'+
                '<div class="mb-1"><span class="hot-rank hot-rank-'+ index+' text-xs text-center">'+ index +'</span><a class="ml-2" href="'+item['link']+'" target="_blank" rel="external nofollow">'+item['title']+'</a></div>'+
                '<div class="d-flex">'+
                '<span class="hot-platform text-xs text-center"><span>'+ (item['platform'])+'</span></span><div class="ml-auto d-none d-md-block">'+item['price']+'</div>'+
                '</div></div>';
                index++;
            } 
            $("#hot-loading-<?php echo $t ?>").fadeOut(200); 
            $('#hot-lod-<?php echo $t ?>').children('i').removeClass('icon-spin');
            $("#hot_news-<?php echo $t ?>").html(html);
        }).fail(function () {
            $('#hot-lod-<?php echo $t ?>').children('i').removeClass('icon-spin');
            $("#hot-success-<?php echo $t ?>").show(200).html('获取失败，请再试一次！').parent().delay(3500).fadeOut(200); 
        });
    }
    function timestampToTime(timestamp,s,time = false) {
        var date = new Date(timestamp * s);
        Y = date.getFullYear() + '-';
        M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D = date.getDate() + ' ';
        h = date.getHours();
        m = ':' + (date.getMinutes() < 10 ? '0'+(date.getMinutes()) : date.getMinutes());
        s = ':' + (date.getSeconds() < 10 ? '0'+(date.getSeconds()) : date.getSeconds());
        if(time)
            return h+m;
        else
            return Y+M+D+h+m+s;
    } 
})(jQuery)
</script> 