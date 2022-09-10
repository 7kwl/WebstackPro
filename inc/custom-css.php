<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
//  
function custom_color(){
    if(io_get_option('custom_color')){
        $link_c = io_get_option('link_c');
        $bnt_c = io_get_option('bnt_c');
        $card_a_c = io_get_option('card_a_c');
        $piece_c = io_get_option('piece_c');
        $styles = '';
        $styles .= ".panel-body a:not(.no-c){color:" . $link_c['color'] . "}.panel-body a:not(.no-c):hover{color:" . $link_c['hover'] . "}"; 
        $styles .= "a:hover,.io-grey-mode .sidebar-show,.io-grey-mode .sidebar-item>a:hover,.io-grey-mode .sidebar-item li>a:hover,.home-like:hover,
        .io-grey-mode .sidebar-popup>div>ul>li>a:hover{color:" . $card_a_c . "}
        .header-mini-btn label:hover path{ stroke:" . $card_a_c . "}
        .url-card .max .tga a:not(.no-tag):hover,.card-app.card .tga a:not(.no-tag):hover{background:" . $card_a_c . "}
        .sidebar .url-card .card:hover{border: 1px solid " . $card_a_c . "!important;}"; 
        //高亮色块
        $styles .= ".tags i{color:" . $piece_c . "}.custom-piece_c_b{background:" . $piece_c . "!important}.custom-piece_c{color:" . $piece_c . "!important}.slider_menu[sliderTab] .anchor,.customize-menu .btn-edit,.badge-danger,.comment-list .rank,.sidebar .card-header:after{background:" . $piece_c . "}.badge-outline-primary{color:" . $piece_c . ";border:" . $piece_c . " solid 1px}"; 
        $styles .= ".posts-nav .page-numbers.current,.posts-nav .page-numbers:not(.dots):hover,#comments-navi>a:hover,#comments-navi>.current,.page-nav>a:hover span,.page-nav>.current span{background-color:" . $piece_c . ";box-shadow: 0px 5px 20px -3px rgba(".hex2rgb($piece_c).",.6);}     
.custom-control-input:not(:disabled):active~.custom-control-label::before{background-color:rgba(".hex2rgb($piece_c).",.15);border-color:rgba(".hex2rgb($piece_c).",.15);}
.custom-control-input:focus~.custom-control-label::before{box-shadow:0 0 0 .2rem rgba(".hex2rgb($piece_c).",.25)}
.custom-control-input:focus:not(:checked)~.custom-control-label::before{border-color:".$piece_c."}
.custom-control-input:checked~.custom-control-label::before{border-color:".$piece_c.";background-color:".$piece_c."}
.btn-search:hover,.btn-search.current{background-color:".$piece_c.";box-shadow: 0 5px 20px -3px rgba(".hex2rgb($piece_c).",.6)}.btn-search.current:after{border-top-color:".$piece_c."}.panel-body h2,.panel-body h3 {border-color:".$piece_c."}";
        //按钮
        $styles .= " .custom_btn-outline {color: ".$bnt_c['color'].";background-color: transparent;border-color: ".$bnt_c['color'].";}
.custom_btn-outline:hover {color: ".$bnt_c['hover-t'].";background-color: ".$bnt_c['hover'].";border-color: ".$bnt_c['hover'].";}
.custom_btn-outline:focus, .custom_btn-outline.focus {color: ".$bnt_c['hover'].";box-shadow: 0 0 0 0 transparent!important;background-color: transparent;}
.custom_btn-outline.disabled, .custom_btn-outline:disabled {color: ".$bnt_c['color'].";background-color: transparent!important;}
.custom_btn-outline:not(:disabled):not(.disabled):active, .custom_btn-outline:not(:disabled):not(.disabled).active,.show > .custom_btn-outline.dropdown-toggle {color: #fff;background-color: ".$bnt_c['color'].";border-color: ".$bnt_c['color'].";}
.custom_btn-outline:not(:disabled):not(.disabled):active:focus, .custom_btn-outline:not(:disabled):not(.disabled).active:focus,.show > .custom_btn-outline.dropdown-toggle:focus {box-shadow: 0 0 0 0 transparent!important;}
.custom_btn-d {color: ".$bnt_c['color-t'].";background-color: ".$bnt_c['color'].";border-color: ".$bnt_c['color'].";}
.custom_btn-d:hover {color: ".$bnt_c['hover-t'].";background-color: ".$bnt_c['hover'].";border-color: ".$bnt_c['hover'].";}
.custom_btn-d:focus,.custom_btn-d.focus {color: ".$bnt_c['hover-t'].";background-color: ".$bnt_c['hover'].";border-color: ".$bnt_c['hover'].";box-shadow: 0 0 0 0 transparent!important;}
.custom_btn-d.disabled,.custom_btn-d:disabled {color: ".$bnt_c['color-t'].";background-color:".$bnt_c['color'].";border-color: ".$bnt_c['color'].";}
.custom_btn-d:not(:disabled):not(.disabled):active,.custom_btn-d:not(:disabled):not(.disabled).active{color: ".$bnt_c['hover-t'].";background-color: ".$bnt_c['hover'].";border-color: ".$bnt_c['hover'].";}
.custom_btn-d:not(:disabled):not(.disabled):active:focus,.custom_btn-d:not(:disabled):not(.disabled).active:focus{box-shadow: 0 0 0 0 transparent!important;}
.btn.custom_btn-d {color: ".$bnt_c['color-t'].";background-color: ".$bnt_c['color'].";border-color: ".$bnt_c['color'].";}
.btn.custom_btn-d:hover {color: ".$bnt_c['hover-t'].";background-color: ".$bnt_c['hover'].";border-color: ".$bnt_c['hover'].";}
.btn.custom_btn-d:focus,.btn.custom_btn-d.focus {color: ".$bnt_c['hover-t'].";background-color: ".$bnt_c['hover'].";border-color: ".$bnt_c['hover'].";box-shadow: 0 0 0 0 transparent!important;}
.btn.custom_btn-d.disabled,.btn.custom_btn-d:disabled {color: ".$bnt_c['color-t'].";background-color:".$bnt_c['color'].";border-color: ".$bnt_c['color'].";}
.btn.custom_btn-d:not(:disabled):not(.disabled):active,.btn.custom_btn-d:not(:disabled):not(.disabled).active,.show > .custom_btn-d.dropdown-toggle {color: ".$bnt_c['hover-t'].";background-color: ".$bnt_c['hover'].";border-color: ".$bnt_c['hover'].";}
.btn.custom_btn-d:not(:disabled):not(.disabled):active:focus,.btn.custom_btn-d:not(:disabled):not(.disabled).active:focus,.show > .custom_btn-d.dropdown-toggle:focus {box-shadow: 0 0 0 0 transparent!important;}"; 
        
        if ($styles) {
        	echo "<style>" . $styles . "</style>";
        }
    }
}

function hex2rgb($hexColor,$isarray=false) {
    $color = str_replace('#', '', $hexColor);
    if (strlen($color) > 3) {
        $r = hexdec(substr($color,0,2));
        $g = hexdec(substr($color,2,2));
        $b = hexdec(substr($color,4,2));
    } else {
        $r = hexdec(substr($color,0,1).substr($color,0,1));
        $g = hexdec(substr($color,1,1).substr($color,1,1));
        $b = hexdec(substr($color,2,1).substr($color,2,1));
    }
    if($isarray)
        return array($r, $g, $b);
    else
        return  $r.','.$g.','.$b ;
} 
