<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function common_content($str, $length=100){
    
    $output = strip_tags($str);
    //잘라내기
    if( strlen($output) > $length ){
        $output = str_replace("&nbsp;", "", trim(iconv_substr($output, 0, $length,"utf-8")));

        if( strlen($output) > $length ) $output .="...";
    }
    
    return $output;
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function get_month($_month){
    $monthString = array("Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec");
    return $monthString[$_month];
}

?>