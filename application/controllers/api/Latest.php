<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Latest extends REST_Controller {

    function __construct(){
        parent::__construct();
        
        $this -> load -> model("board/board_m");
        

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        
    }
    
    //리스트
    function lists_get(){
        $op_table = $this -> input ->get("op_table", FALSE);
        $size = $this -> input ->get("size", FALSE);

        //$latest_list = $this -> latest_m -> list($op_table, $size);
        $latest_list = $this -> latest_m -> latest_list_by_lang($op_table, $this->html_lang, $size);
        if ($latest_list){
            $this->response($latest_list, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No users were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
    
}

?>