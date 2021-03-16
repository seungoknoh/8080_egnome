<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * datetime_now
 *
 * @param  mixed $_str_datetime
 * @return -1 : 이전 , 0 : 같음 , 1 : 이후
 */
function datetime_now($_str_datetime){
	$now = date("Y-m-d H:i:s"); 
	
	$str_now = strtotime($now);
	$str_target = strtotime($_str_datetime);
	
	if($str_now > $str_target) {
		return -1;
	} elseif($str_now == $str_target) {
		return 0;
	} else {
		return 1;
	}
}

/**
 * datetime_get_format
 *
 * @param  string $_str_datetime 
 * @param  string $_format 포맷형식
 * @return string 결과값 리턴
 */
function datetime_get_format($_str_datetime, $_format='Y-m-d'){
	if($_str_datetime == "") return "";
	$newDate = date($_format, strtotime($_str_datetime)); 
	return $newDate;
}

/* End of file */
?>