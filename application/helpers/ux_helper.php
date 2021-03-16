<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//ux helper
function ux_menu_html($data, $is_depth2=true, $current_code='', $lang='ko'){
    $CI = &get_instance();
    $depth1 = "";
    $menuLength = 1;
    foreach ($data as $menu){
        if( $menu -> mn_is_view == '1' ){
			if( strlen($menu->mn_code) == 2 ){
                $is_on = substr($current_code, 0, 2) == $menu -> mn_code ? "on" : "";
				$link = $menu->mn_is_alert == 1 ? "javascript:alert(\"{$CI->lang->line('text_prepare')}\")" : $menu->mn_link;
                $depth1 .= "<li class='main-0{$menuLength} {$is_on}'><span class='line'></span><a href='{$link}' data-duration='0.5s'>";
                
                //언어
                $menu_name = $menu->mn_name;
                if( $lang == 'en'){
                    $menu_name = $menu->mn_name_en;
                }
                $depth1 .= "<span>".$menu_name."</span></a>";
                
                if( $is_depth2 ){
                    $depth1 .= ux_menu_dpeth_html($data, $menu -> mn_code, $current_code, $lang);
                }
                $depth1 .="</li>";
                //var_dump($depth1);
                $menuLength++;
			}
		}
    }
    //log_message("debug", $depth1);
    return $depth1;
}

//서브 메뉴
function ux_menu_dpeth_html($data, $code, $current_code, $lang='ko'){
    $CI = &get_instance();
    $output = "";
    foreach ($data as $menu){
        if( $menu -> mn_is_view == '1' ){
            if( strlen($menu->mn_code) == strlen($code)+2 && substr($menu->mn_code, 0, strlen($code)) == $code ){

                //언어
                $menu_name = $menu->mn_name;
                if( $lang == 'en'){
                    $menu_name = $menu->mn_name_en;
                }

                $is_on = $current_code == $menu -> mn_code ? "class='on'" : "";
                $link = $menu->mn_is_alert == 1 ? "javascript:alert(\"{$CI->lang->line('text_prepare')}\")" : $menu->mn_link;
                $output .= "<li {$is_on}><a href='{$link}' data-duration='0.5s'><span>{$menu_name}</span></a></li>";
            }
        }
    }
    if( $output != "" ) $output = "<ul class='clear clearfix'>".$output."</ul>";
    return $output;
    //var_dump($output);
    //log_message("debug", $output);
}
// main 메뉴에서 depth3 메뉴까지 출력
function ux_total_menu_html($type='FR', $tail, $current_code='', $lang='ko'){
    $CI =  &get_instance();
    $depth1_menu = $CI->menu_m->menu_list($type);
    $output = "";
    $i=1;
    foreach($depth1_menu as $menu){
       if( $menu->mn_is_view == '1' ){
            $is_on = substr($current_code,0,2) == $menu->mn_code ? "on" : "";
            $output .= "<li class='{$is_on} menu_{$i}'>";
            if( $tail == '0' ){
                $output .= ux_menu_item_html($menu, $lang);
            } else {
                $output .= ux_tail_menu_item_html($menu, $i, $lang);
            }
            $sub1 = $CI->menu_m->menu_code_sub_list($type, $menu->mn_code);
            if( $sub1 != null ){
                $output1 = "";
                foreach($sub1 as $menu_2){
                    $is_on = substr($current_code,0,4) == $menu_2->mn_code ? "class='on'" : "";
                    $output1 .= "<li {$is_on}>";
                    $output1 .= ux_menu_item_html($menu_2, $lang);
                    $sub2 = $CI->menu_m->menu_code_sub_list($type, $menu_2->mn_code);
                    if( $sub1 != null ){
                        $output2 = "";
                        foreach($sub2 as $menu_3){
                            $is_on = substr($current_code,0,6) == $menu_3->mn_code ? "class='on'" : "";
                            $output2 .= "<li {$is_on}>";
                            $output2 .= ux_menu_item_html($menu_3, $lang);
                            $output2 .= "</li>";
                        }
                        
                        if($output2 != "") $output1 .= "<ul class='clear clearfix'>".$output2."</ul>";
                    }
                    $output1 .= "</li>";
                }
                $output .= "<ul class='clear clearfix'>".$output1."</ul>";
            }
            $output .="</li>";
            $i++;
        }
    }
    //log_message("debug", $output);
   return $output;
}
// main 메뉴에서 depth3 메뉴 없애기 위함
function ux_main_menu_html($type='FR', $tail, $current_code='', $lang='ko'){
    $CI =  &get_instance();
    $depth1_menu = $CI->menu_m->menu_list($type);
    $output = "";
    $i=1;
    foreach($depth1_menu as $menu){
       if( $menu->mn_is_view == '1' ){
            $is_on = substr($current_code,0,2) == $menu->mn_code ? "on" : "";
            $output .= "<li class='{$is_on} menu_{$i}'>";
            if( $tail == '0' ){
                $output .= ux_menu_item_html($menu, $lang);
            } else {
                $output .= ux_tail_menu_item_html($menu, $i, $lang);
            }
            $sub1 = $CI->menu_m->menu_code_sub_list($type, $menu->mn_code);
            if( $sub1 != null ){
                $output1 = "";
                foreach($sub1 as $menu_2){
                    $is_on = substr($current_code,0,4) == $menu_2->mn_code ? "class='on'" : "";
                    $output1 .= "<li {$is_on}>";
                    $output1 .= ux_menu_item_html($menu_2, $lang);
                    $sub2 = $CI->menu_m->menu_code_sub_list($type, $menu_2->mn_code);

                    $output1 .= "</li>";
                }
                $output .= "<ul class='clear clearfix'>".$output1."</ul>";
            }
            $output .="</li>";
            $i++;
        }
    }
    //log_message("debug", $output);
   return $output;
}

function set_menu_name_by_lang($menu, $lang='ko'){
    switch($lang){
        case "ko" :
            return $menu->mn_name;
        case "en" :
            return $menu->mn_name_en;
        default :
            return $menu->mn_name;
    }
}

function ux_menu_item_html($menu, $lang='ko'){
    $CI = &get_instance();
    $output = "";
    $link = $menu->mn_is_alert == 1 ? "javascript:alert(\"{$CI->lang->line('text_prepare')}\")" : $menu->mn_link;
    $name = set_menu_name_by_lang($menu, $lang);
    $output .= "<a href='{$link}' data-duration='0.5s'><span>{$name}</span></a>";
    return $output;
}

function ux_tail_menu_item_html($menu, $i, $lang='ko'){
    $CI = &get_instance();
    $output = "";
    $link = $menu->mn_is_alert == 1 ? "javascript:alert(\"{$CI->lang->line('text_prepare')}\")" : $menu->mn_link;
    $name = set_menu_name_by_lang($menu, $lang);
    $output .= "<a href='{$link}' data-duration='0.5s'><em>0{$i}</em><span>{$name}</span></a>";
    return $output;
}

//3deth menu 구분을 위해 추가
function ux_menu_item_html2($menu, $lang='ko'){
    $CI = &get_instance();
    $output = "";
    $link = $menu->mn_is_alert == 1 ? "javascript:alert(\"{$CI->lang->line('text_prepare')}\")" : $menu->mn_link;
    $name = set_menu_name_by_lang($menu, $lang);
    $output .= "<a href='{$link}' data-duration='0.5s'><span>{$name}</span></a>";
    return $output;
}


//서브 메뉴
function ux_menu_sub_html($data, $code){
    $CI = &get_instance();
    if( $data == null ) return ""; 
    $output = "";
    foreach ($data as $menu){
        if( $menu -> mn_is_view == '1' ){
            $is_on = $code == $menu -> mn_code ? "class='on'" : "";
            $link = $menu->mn_is_alert == 1 ? "javascript:alert(\"{$CI->lang->line('text_prepare')}\")" : $menu->mn_link;
            $output .= "<li {$is_on}><a href='{$link}' data-duration='0.5s'><span>{$menu -> mn_name}</span></a></li>";
            //log_message("debug", $output);
        }
    }
    //if( $output != "" ) $output = "<ul class='clear clearfix list'>".$output."</ul>";
    return $output;

}

//서브 메뉴
function ux_menu_tab_sub_html($data, $code){
    $CI = &get_instance();
    if( $data == null ) return ""; 
    $output = "";
    foreach ($data as $menu){
        if( $menu -> mn_is_view == '1' ){
            $div_active = $code == $menu -> mn_code ? "active" : "";
            $link = $menu->mn_is_alert == 1 ? "javascript:alert(\"{$CI->lang->line('text_prepare')}\")" : $menu->mn_link;
            $output .= "<div class='TabArea__item {$div_active}'><a href='{$link}'><span>{$menu -> mn_name}</span></a></div>";
            //log_message("debug", $output);
        }
    }
    //if( $output != "" ) $output = "<ul class='clear clearfix list'>".$output."</ul>";
    return $output;

}

function ux_menu_info($data, $type){
    if( $data == null ) return "";
    switch ($type){
        case "code" : return trim($data -> mn_code); break;
        case "name_en" : return trim($data -> mn_name_en); break;
        //case "name_zh" : return trim($data -> mn_name_zh); break;
        case "name" : return trim($data -> mn_name); break;
        case "link" : return trim($data -> mn_link); break;
        default : return trim($data -> mn_name); break;
    }
}

//sns meta tag
function set_metaTag(){
    $output = "";
    $output .='<meta property="og:title" content="제목" />'; 
    $output .='<meta property="og:description" content="설명" />'; 
    $output .='<meta property="og:image" content="대표 이미지" />'; 
    $output .='<meta property="og:url" content="표준 링크(같은 콘텐츠를 가리키는 여러 개의 URL 중 대표 URL)" />'; 
    //트위터 카드 타입은 summary_large_image, summary, photo 중 하나를 선택할 수 있다.
    $output .='<meta name="twitter:card" content="트위터 카드 타입" />';
    return $output;
}

//ux admin helper
function ux_menu_admin_html($data, $is_depth2=true, $current_code=''){
    $depth1 = "";
    $menuLength = 1;
    foreach ($data as $menu){
        if( $menu -> mn_is_view == '1' ){
			if( strlen($menu->mn_code) == 2 ){
                $is_on = substr($current_code, 0, 2) == $menu -> mn_code ? "on" : "";
				$depth1 .= "<li class='main-0{$menuLength} {$is_on}'><span class='line'></span><a href='{$menu -> mn_link}' data-duration='0.5s'>";
                $depth1 .= "<span class='{$menu->mn_icon}'></span> <span>".$menu -> mn_name."</span></a>";
                if( $is_depth2 ){
                    $depth1 .= ux_menu_dpeth_admin_html($data, $menu -> mn_code, $current_code);
                }
                $depth1 .="</li>";
                $menuLength++;
			}
		}
    }
    return $depth1;
}

//ux admin sub menu
function ux_menu_dpeth_admin_html($data, $code, $current_code=''){
    $output = "";
    foreach ($data as $menu){
        if( strlen($menu -> mn_code) > strlen($code) && substr($menu -> mn_code, 0, strlen($code)) == $code ){
            $is_on = $current_code == $menu -> mn_code ? "class='on'" : "";
            $output .= "<li {$is_on}><a href='{$menu -> mn_link}' data-duration='0.5s'><span class='{$menu->mn_icon}'></span> <span>{$menu -> mn_name}</span></a></li>";
        }
    }
    if( $output != "" ) $output = "<ul class='clear clearfix nav'>".$output."</ul>";
    return $output;
}

?>