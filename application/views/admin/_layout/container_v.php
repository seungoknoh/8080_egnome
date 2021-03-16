<!DOCTYPE HTML>
<html lang="ko">

<head>
    <?php 
		$config = $this -> config_m -> get_config();
		$data['config'] = $config;

		//메뉴
		$current_menu = $this -> menu_m -> get_current_menu("AD");
		$current_code = '';
		if( $current_menu != null){
			$menu_depth1 = $this -> menu_m -> get_menu_info("AD", substr($current_menu -> mn_code, 0, 2));
			$current_code = $current_menu -> mn_code ;
		}
		$menu_list = ux_menu_html($this -> menu_m -> admin_all_menu_list("AD", $this -> session -> userdata('mb_id')), false, $current_code);
		$menu_total_list = ux_menu_admin_html($this -> menu_m -> admin_all_menu_list("AD", $this -> session -> userdata('mb_id')), true, $current_code );

		//메뉴권한체크
		if( $this->session->userdata("mb_id") != $config->cf_admin ){
			$auth_menu = $this -> authSet_m -> auth_view($this->session->userdata("mb_id"));
			$auth_menu_list = explode(",", $auth_menu->mn_idxs);

			if( isset($current_menu->mn_idx) ){
				$is_auth_ok =  array_search($current_menu->mn_idx, $auth_menu_list) === 0 ? true : array_search($current_menu->mn_idx, $auth_menu_list);
				if(!$is_auth_ok){
					alert("접근불가능한 페이지입니다.".array_search($current_menu->mn_idx, $auth_menu_list), "/backS1te/main");
				}
			}
		}
	?>
    <?php $this -> load -> view('admin/_include/head_v', $data);?>
</head>

<body>
    <!-- allwrap -->
    <div class="allwrap bg01">
        <div id="skipToContent">
            <a href="#container">본문으로 바로가기</a>
        </div>
        <!-- header -->
        <?php $this -> load -> view('admin/_include/header_v', $data);?>
        <!-- container -->
        <div class="container-wrap" id="container">
            <!-- leftMenu -->
            <div id="sidebar" class="sidebar">
                <ul class="nav nav-sidebar">
                    <?php echo $menu_total_list ?>
                </ul>
            </div>
            <!-- //leftMenu -->
            <!-- main-area -->
            <div class="main-area">
                <!-- content -->
                <section id="content" class="content-area">
                    <?php if (isset($page)) $this -> load-> view($page, $data); ?>
                </section>
                <hr>
                <?php $this -> load -> view('admin/_include/footer_v', $data);?>
            </div>
            <!-- //main-area -->
        </div>
        <!-- //container -->
    </div>
    <!-- allwrap -->
    <?php $this -> load -> view('admin/_include/tail_v', $data);?>