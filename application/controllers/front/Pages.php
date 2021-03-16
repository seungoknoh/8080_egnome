<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends FRONT_Controller {
    
    private $page_idx = null;
    private $page_info = null;
    
	function __construct(){
		parent::__construct();
		
		$this -> load -> helper('path');
		$this->load->model("config/menu_m");
		$this -> load -> model("board/latest_m");
		
		//페이지값
		$this -> page_idx = urldecode($this -> uri -> segment(3));
	}

	public function index(){
		$op_table = $this -> uri -> segment(3);
		$page_gu = $this -> uri -> segment(2);
		$data = array();
		if( $op_table == "BIGDATA" ){
            $data['list'] =  $this -> latest_m -> latest_list_by_lang("bigdata",$this->html_lang, 3);
        }
		
		$page_view =""; //"front/pages/{$this -> page_idx}_{$this->html_lang}_v";
		if( $page_gu == "story" ){
            $page_view = "front/pages/story/{$this -> page_idx}_v";
        } else {
			$page_view = "front/pages/business/{$this -> page_idx}_v";
		} 

		//$page_view = "front/pages/{$this -> page_idx}_v";
		$this->_view($page_view, $data);
	}
}
?>