<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends Adm_Controller{

	function __construct(){
		parent::__construct();
		$this -> load -> helper( array('alert'));
		$this -> load -> model('config/config_m');
		$this -> load -> model('member/member_m');
		$this -> load -> model('board/board_m');
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> update();
	}

	/* 환경설정 수정 */
	public function update(){
		$this -> load -> helper('form');
		if( $_POST ){
			$data = array(
				'cf_title' => $this -> input -> post('cf_title'),
				'cf_admin' => $this -> input -> post('cf_admin'),
				'cf_admin_email' => $this -> input -> post('cf_admin_email'),
				'cf_admin_name' => $this -> input -> post('cf_admin_name'),
				'cf_addr1' => $this -> input -> post('cf_addr1'),
				'cf_addr2' => $this -> input -> post('cf_addr2'),
				'cf_zip' => $this -> input -> post('cf_zip'),
				'cf_tel' => $this -> input -> post('cf_tel'),
				'cf_fax' => $this -> input -> post('cf_fax'),
			    'cf_email_auth' => $this -> input -> post('cf_email_auth') == 1 ? 1 : 0,
				'cf_is_phone' => $this -> input -> post('cf_is_phone') == 1 ? 1 : 0,
				'cf_privacy' => $this -> input -> post('cf_privacy'),
				'cf_service' =>  $this -> input -> post('cf_service'),
			    'cf_url'=> $this -> input -> post('cf_url')
			);

			$result = $this -> config_m -> config_update($data);
			if( $result ){
				alert( '수정하였습니다.', "/backS1te/config");
			}
		}

		$data['view'] = $this -> config_m -> get_config();

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/config/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);			
	}
	
	/* 컨텐츠 이미지 경로 수정 */
	public function json_urlUpdate(){
	    $cf_url_prev = $this -> input -> get('cf_url_prev');
	    $cf_url_next = $this -> input -> get('cf_url_next');
	    $data = array(
	        'url_prev' => $cf_url_prev,
	        'url_next' => $cf_url_next
	    );
	    $result = $this -> board_m -> board_update_url($data);
	    if( $result ){
	        echo json_encode(array("msg" => "성공하였습니다." ));
	    }else{
	        echo json_encode(array("msg" => "실패하였습니다." ));
	    }
	}
}
?>