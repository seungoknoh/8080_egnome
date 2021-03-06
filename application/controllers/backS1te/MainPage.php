<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainPage extends Adm_Controller{

	function __construct(){
		parent::__construct();
		$this -> load -> helper( array('alert','form'));
        $this -> load -> model('config/mainPage_m');		
        $this -> load -> model('config/poll_m');	
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> update();
	}

	/* 메인페이지 수정 */
	public function update(){
		//설문리스트
		$data['poll_list'] = $this -> poll_m -> poll_view_list();
	
		if( $_POST ){
			$data = array(
				'mp_youtube' => $this -> input -> post('mp_youtube', TRUE),
				'mp_visual' => $this -> input -> post('mp_visual', TRUE),
				'mp_poll' => $this -> input -> post('mp_poll', TRUE),
			);
			$result = $this -> mainPage_m -> update_mainPage($data);
			if( $result ){
				alert( '수정하였습니다.', "/backS1te/mainPage");
			}
		}
		$data['view'] = $this -> mainPage_m -> get_mainPage();


		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/mainPage/modify_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);			
	}
}
?>