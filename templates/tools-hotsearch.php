<?php if ( ! defined( 'ABSPATH' ) ) { exit; } 

if( $hotlist= io_get_option('hot_list_id') ){
if(!empty($hotlist['enabled'])){
    echo '<div class="overflow-x-auto mb-3" style="margin:0 -.5rem;padding:0 .5rem"><div class="row row-sm hot-search">';
    foreach ($hotlist['enabled'] as $key => $value) {
        hot_search($key);
    } 
    echo '</div></div>';
}
}
