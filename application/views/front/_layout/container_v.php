<!DOCTYPE HTML>
<html lang="<?php echo $this->html_lang ?>">
<?php
    $config = $this -> config_m -> get_config();
    $data['config'] = $config;
    //메뉴
    $current_menu = $this -> menu_m -> get_front_current_menu("FR");
    $menu_depth1 = null;
    $gubun = $this -> uri -> segment(1);
    $title_gu = $this -> uri -> segment(3);
    //경로 title
    //if( $config -> cf_title != null ) $title .= $config -> cf_title;
    if( $title_gu == "bigdata" ) $title .= "big data";
    //1차 , 2차 메뉴
    if( $current_menu != null ){
        $menu_depth1 = $this -> menu_m -> get_menu_info("FR", substr($current_menu -> mn_code, 0, 2));
        //서브메뉴
        $data['menu_sub_list'] = ux_menu_tab_sub_html($this -> menu_m -> menu_code_sub_list('FR', $menu_depth1 -> mn_code), $current_menu -> mn_code);        
    }
    $data['current_menu'] = $current_menu;
	$data['menu_depth1'] = $menu_depth1; //content_visual 페이지에서 depth1메뉴명을 출력하기 위함.
    if($title_gu == "bigdata"){
        $data['current_code'] = "2030";//big data management board 페이지는 current_code가 없으므로 임의로 설정 필요.
        $data['menu_depth1'] = "마이크로바이옴 이야기";//big data management board 페이지는 current_name가 없으므로 임의로 설정 필요.
    } else {
        $data['current_code'] = $current_menu -> mn_code ;
        $data['menu_depth1'] = $menu_depth1 -> mn_name ;
    }
    
    //$data['current_idx'] = $current_menu -> mn_idx ; 
    //$data['current_menu'] = $current_menu;
    //$data['current_depth1'] = $menu_depth1;
    //$data['title'] = $title;
    //$data['metaTag'] = !isset($metaTag) ? "" : $metaTag;
	// 현재메뉴
	$data['foot_menu_list'] = ux_menu_html($this -> menu_m -> all_menu_list("FR"), false, $data['current_code']);
	$data['menu_total_list'] = ux_menu_html($this -> menu_m -> all_menu_list("FR"), true, $data['current_code']);
?>
<?php $this->load->view('/front/_include/head_v.php', $option); ?>
</head>

<body>
    <!-- skipToContent -->
    <div id="skipToContent">
        <a href="#Container">본문으로 바로가기</a>
        <a href="#MainGnb">메뉴 바로가기</a>
    </div>
    <!-- //skipToContent -->
    <!-- allwrap -->
    <div class="loading" id="loading">
        <div class="img"><span class="skip">Loading...</span></div>
    </div>
    <div class="Layout__Allwrap" id="LayoutAllwrap">
        <div class="Layout__header">
            <?php $this->load->view('/front/_include/header_v.php', $option); ?>
            <?php $this->load->view('/front/_include/tail_v.php'); ?>
        </div>
        <!-- ContainerArea 본문 -->
        <div class="Layout__container">
            <div class="Container" id="Container">
                <?php $this -> load -> view('front/_include/content_visual_v', $data);?>
                <?php if ($gubun == 'pages' || $gubun == 'company') {?>
                <?php $this -> load -> view('front/_include/content_location_v', $data);?>
                <?php }?>
                <?php if (isset($page)) $this->load->view($page, $option); ?>
            </div>
        </div>
        <!-- //ContainerArea 본문-->
        <?php
            // 푸터 include
            $this -> load -> view('front/_include/footer_v', $data);
            $this -> load -> view('front/_include/tail_v', $option);
        ?>
    </div>
    <!-- //allwrap -->
</body>

</html>