<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

	function alert($msg = '이동합니다', $url = '') {
		$CI = &get_instance();
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=" . $CI -> config -> item('charset') . "\">";
		if( $url != '' ){
		echo "
			<script type='text/javascript'>
				alert('" . $msg . "');
				location.replace('" . $url . "');
			</script>
		";
		}else{
		    echo "
			<script type='text/javascript'>
				alert('" . $msg . "');
                if( document.referrer ){
				    history.back();
                }else{
                    location.href = '".$CI-> config->item("base_url")."';
                }
			</script>
		";
		}
		exit ;
	}
	 
	// 창 닫기
	function alert_close($msg) {
		$CI = &get_instance();
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=" . $CI -> config -> item('charset') . "\">";
		echo "<script type='text/javascript'> alert('" . $msg . "'); window.close(); </script>";
		exit ;
	}
	 
	function alert_only($msg, $exit = TRUE) {
		$CI = &get_instance();
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=" . $CI -> config -> item('charset') . "\">";
		echo "<script type='text/javascript'> alert('" . $msg . "'); </script>";
		if ($exit)
			exit ;
	}
	 
	function replace($url = '/') {
		echo "<script type='text/javascript'>";
		if ($url)
			echo "window.location.replace('" . $url . "');";
		echo "</script>";
		exit ;
	}

	function back() {
		$CI = &get_instance();
		echo "<script type='text/javascript'>
                if( document.referrer ){
				    history.back();
                }else{
                    location.href = '".$CI-> config->item("base_url")."';
                }
			  </script>";
		exit ;
	}
	
	// 메타태그를 이용한 URL 이동
	// header("location:URL") 을 대체
	function goto_url($url){
        $url = str_replace("&amp;", "&", $url);
        
        if (!headers_sent()){
            header('Location: '.$url);
        } else {
            echo '<script>';
            echo 'location.replace("'.$url.'");';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
            echo '</noscript>';
        }
        exit;
	}
?>