    //메뉴
    $current_menu = $this -> menu_m -> get_front_current_menu("FR");
    $menu_depth1 = null;

    //경로 title
    if( $config -> cf_title != null ) $title .= $config -> cf_title;
    //1차 , 2차 메뉴
    if( $current_menu != null ){
		$menu_depth1 = $this -> menu_m -> get_menu_info("FR", substr($current_menu -> mn_code, 0, 2));
        if( $menu_depth1 != null ) $title = $menu_depth1 -> mn_name." | ".$title;
        if( $current_menu != null ) $title = $current_menu -> mn_name." < ".$title;

        //서브메뉴
        $data['menu_sub_list'] = ux_menu_sub_html($this -> menu_m -> menu_sub_list('FR', $menu_depth1 -> mn_code), $current_menu -> mn_code);        
    }else{
    //기타메뉴
        $current_menu = $this -> menu_m -> get_front_current_menu("ETC");
     
        //서브메뉴
        $data['menu_sub_list'] = ux_menu_sub_html($this -> menu_m -> menu_sub_list('ETC', 99),  $current_menu -> mn_code);   
    }
    $data['current_code'] = $current_menu -> mn_code ;
    $data['current_idx'] = $current_menu -> mn_idx ; 
    $data['current_menu'] = $current_menu;
    $data['current_depth1'] = $menu_depth1;
    $data['title'] = $title;
    $data['metaTag'] = !isset($metaTag) ? "" : $metaTag;

    // // 헤더 include
     $this -> load -> view('front/_include/head_v', $data);
	// // 현재메뉴
	$data['menu_list'] = ux_menu_html($this -> menu_m -> all_menu_list("FR"), false, $data['current_code'], $this->html_lang);
	$data['menu_total_list'] = ux_menu_html($this -> menu_m -> all_menu_list("FR"), true, $data['current_code'], $this->html_lang);