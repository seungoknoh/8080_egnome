<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 권한검사
function auth_check($mn_type, $mn_code, $return=false){
    $CI = &get_instance();
    $CI -> load -> model('config/config_m');
    $CI -> load -> model('config/menu_m');
    
    $mb_id = $CI -> session -> userdata("mb_id");
    $config = $CI -> config_m -> get_config();
    
    //최고관리자인 경우 지나감.
    if( $mb_id == $config->cf_admin ) return;
    
    $msg = "이 메뉴에는 접근권한이 없습니다. 접근권한은 최고관리자 혹은 접근권한 관리자가 부여합니다.";
    alert($msg);
}

?>
