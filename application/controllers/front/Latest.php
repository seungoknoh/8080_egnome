<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Latest extends FRONT_Controller {
    function __construct(){
        parent::__construct();
        $this -> load -> model("board/latest_m");
        $this -> load -> helper( array('alert','editor','thumbnail'));
    }
    
    public function list(){    
        $op_table = $this -> uri -> segment(3);
        $length = $this -> input -> get("length") == null ? 5 : $this -> input -> get("length");
        $is_reply = $this -> input -> get("is_reply") == null ? false : true;
        if( $op_table == null ){
            $data['msg'] = "테이블값이 없습니다.";
        }else{
            //$data['list'] = $this -> latest_m -> list($op_table, $length, 100, 100 , $is_reply); 
            $data['list'] = $this -> latest_m -> latest_list_by_lang($op_table, $this->html_lang, $length, 100, 100 , $is_reply); 
        }
        Header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
?>