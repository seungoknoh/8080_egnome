<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function makeDir($folder){
    @mkdir(UPLOAD_FILE_DIR."/{$folder}", 0755);
    @chmod(UPLOAD_FILE_DIR."/{$folder}", 0755);
}

// 파일명에서 특수문자 제거
function get_safe_filename($name){
    $pattern = '/["\'<>=#&!%\\\\(\)\*\+\?]/';
    $name = preg_replace($pattern, '', $name);
    
    return $name;
}

function get_microtime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}


?>
