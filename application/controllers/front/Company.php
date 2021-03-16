<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends FRONT_Controller {
    
    private $page_idx = null;
    private $page_info = null;
    
	function __construct(){
		parent::__construct();
		
		$this -> load -> helper('path');
		$this->load->model("config/menu_m");
		
		//페이지값
		$this -> page_idx = urldecode($this -> uri -> segment(2));
	}

	public function index(){
		$data = array();
		//$page_view = "front/company/{$this -> page_idx}_{$this->html_lang}_v";
		$page_view = "front/company/{$this -> page_idx}_v";
		$this->_view($page_view, $data);
	}
}
?>