<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends FRONT_Controller {
	
	function __construct(){
		parent::__construct();
		$this -> load -> model("board/latest_m");
		$this -> load -> model("config/Popup_m");
		$this -> load -> model("config/mainPage_m");
		$this -> load -> helper( array('alert','editor','thumbnail')); 
	}
	
	public function index(){
		$this -> main();
	}

	public function popup(){
		echo json_encode($this -> Popup_m -> popup_open_list(''));
	}
	
	public function main(){
		$this-> mainPage = $this -> mainPage_m -> get_mainPage();
		$this-> notice = $this -> latest_m -> latest_list_by_lang("notice",$this->html_lang, 1);
		$this-> research = $this -> latest_m -> latest_list_by_lang("research",$this->html_lang, 1);
		//$this-> press = $this -> latest_m -> latest_list_by_lang("press",$this->html_lang, 2);
		
		//최근게시물
		$data = array(
			"mp_data" => $this -> mainPage,
			"noticeList" => $this -> notice,
			"researchList" => $this -> research,
			"pressList" => $this -> latest_m -> latest_list_by_lang("press",$this->html_lang, 1),
			"is_main" => true,
		);
		//var_dump($this-> mp_data);
		//메인페이지 데이터
		//$data['mp_data'] = $this -> mainPage_m -> get_mainPage();

		//$data['page'] = "front/main/main_v";
		//$this -> load -> view('front/_layout/main_v', $data);
		//$this->_view("/front/main/main_{$this->html_lang}_v", $data);
		$this->_view("/front/main/main_v", $data);
	}
}
?>