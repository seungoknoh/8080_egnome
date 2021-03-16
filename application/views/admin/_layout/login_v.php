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
		$menu_list = ux_menu_html($this -> menu_m -> all_menu_list("AD"), false, $current_code);
		$menu_total_list = ux_menu_admin_html($this -> menu_m -> all_menu_list("AD"), true, $current_code);
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
    <div class="header-wrap">
        <header id="header">
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/backS1te">Administrator</a>
                        <button type="button" class="navbar-toggle navbar-setting btn-toggle" id="btn-setting" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="/">Go to Main</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    </div>
    <!-- //header -->
    <!-- container -->
     <div class="container-wrap" id="container">
        <!-- content -->
        <section id="content" class="content-area"> 
        <?php if (isset($page)) $this -> load-> view($page, $data); ?>
        </section>
	</div>
    <!-- //container -->
    <hr>
    <?php $this -> load -> view('admin/_include/footer_v', $data);?>
</div>
<!-- allwrap -->
<?php $this -> load -> view('admin/_include/tail_v', $data);?>