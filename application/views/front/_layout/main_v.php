<!DOCTYPE html>
<html lang="<?php echo $this->html_lang ?>">

<head>
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
    <!-- <div class="loading" id="loading">
        <div class="img"><span class="skip">Loading...</span></div>
    </div> -->
    <div id="loading" class="loading">
        <div class="img"><span class="skip">Loading...</span></div>
    </div>
    <div class="Layout__Allwrap" id="LayoutAllwrap">
        <!-- //Layout__header -->
        <div class="Layout__header">
            <?php $this->load->view('/front/_include/header_v.php', $option); ?>
            <?php $this->load->view('/front/_include/tail_v.php'); ?>
        </div>
        <!-- //Layout__header -->
        <?php $this->load->view($page, $option); ?>
        <?php $this->load->view($footer, $option); ?>
    </div> <!-- //Layout__Allwrap -->

    <!-- allwrap -->
    <!-- 팝업스크립트 -->
    <!-- <script src="<?php echo JS_DIR?>/ui.popup.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		window.ui.main();
	});
	</script>	 -->

    <body>

</html>