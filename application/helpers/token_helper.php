<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// GET/POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_token(){
    $CI = &get_instance();
    $token = false;
    if( $CI -> session -> userdata('ss_token') != null ){
        $token = $CI -> session -> userdata('ss_token');
    }
    $req_token = false;
    if( isset($_REQUEST['token']) ){
        $req_token = $_REQUEST['token'];
    }

    if( !$token || !$req_token || $token != $req_token){
        alert($CI -> config -> item('base_url').'올바른 방법으로 이용해 주십시오.');
    }
    $CI -> session -> unset_userdata('ss_token');
    return true;
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_token(){
    $CI = &get_instance();
    $token = md5(uniqid(rand(), true));
    $CI -> session -> set_userdata('ss_token', $token);
    return $token;
}

?>
