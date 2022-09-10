<?php if ( ! defined( 'ABSPATH' ) ) { exit; }  
function hot_search($type){
$t= mt_rand();
switch ($type) {
    case "baidu_topsearch":
        $title = '百度';
        $slug = '热点';
        $ico = 'icon-baidu';
        $color = '#2319DC';
        include( get_theme_file_path('/templates/hot/hot-search.php') ); 
        break;
    case "weibo_topsearch":
        $title = '微博';
        $slug = '热搜榜';
        $ico = 'icon-weibo';
        $color = '#E6162D';
        include( get_theme_file_path('/templates/hot/hot-weibo.php') ); 
        break;
    case "zhihu_topsearch":
        $title = '知乎';
        $slug = '热榜';
        $ico = 'icon-zhihu';
        $color = '#0084FF';
        include( get_theme_file_path('/templates/hot/hot-zhihu.php') ); 
        break;
    case "smzdm_select":
        $title = '什么值得买';
        $slug = '精选好价';
        $ico = 'icon-smzdm';
        $color = '#ED382F';
        include( get_theme_file_path('/templates/hot/hot-smzdm.php') ); 
        break;
    default:
        $title = '百度';
        $slug = '热搜';
        $ico = 'icon-baidu';
        $color = '#2319DC';
        include( get_theme_file_path('/templates/hot/hot-search.php') ); 
}
?>

<?php }

//http://zhibo.sina.com.cn/api/zhibo/feed?callback=jQuery1112042151262348278307_1583126404217&page=1&page_size=20&zhibo_id=152&tag_id=0&dire=f&dpc=1&pagesize=20&id=1638768&type=0&_=1583126404220
//http://zhibo.sina.com.cn/api/zhibo/feed?callback=jQuery1112042151262348278307_1583126404217&page=1&page_size=20&zhibo_id=152&tag_id=0&dire=f&dpc=1&pagesize=20&id=1638768&type=0&_=1583126404221
//http://zhibo.sina.com.cn/api/zhibo/feed?page=1&page_size=20&zhibo_id=152&tag_id=0&dire=f&dpc=1&pagesize=20&_=1583119028651


