<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//에러출력
function error_output($errors_arr){
    $output = "<div class='ErrorData'>";
    foreach($errors_arr as $key => $vaule){
        $output .= "<div class='ErrorData__item'><span>{$vaule}</span></div>";
    }
    $output .= "</div>";
    return $output;
}

?>