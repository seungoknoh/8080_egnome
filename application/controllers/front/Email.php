<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends FRONT_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('date');
		$this -> load -> model("config/config_m");
	}
	
	//메일발송
	public function send(){
		$this -> load -> library('form_validation');
		if( $_POST ){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('me_content', '내용', 'required');
			$this -> form_validation -> set_rules('me_from_email', '메일주소', 'required|valid_email');

			if( $this -> form_validation -> run() == TRUE ){
				$this -> load -> library('email'); //email 라이브러리
				$config = $this -> config_m -> get_config(); //관리자정보

				$me_content = $this -> input->post("me_content", TRUE);
				$me_from_email = $this -> input->post("me_from_email", TRUE);
				
				$datestring = '%Y년 %m월 %d일 - %h:%i %a';
				$time = time();

				$from_email = $me_from_email == null ? "홈페이지 문의" : $me_from_email;

				$this -> email -> clear();
				$this -> email -> to($config -> cf_admin_email, "{$config -> cf_title} 관리자");
				$this -> email -> from($from_email);
				$this -> email -> subject("".mdate($datestring, $time)." 에 발송된 홈페이지 문의 메일입니다.");
				$this -> email -> message("".mdate($datestring, $time)." 에 발송되었습니다. \n\n문의내용 : \n".$me_content);
				
				//이메일보내기
				$result = $this -> email -> send();
				if( $result ){
					echo json_encode(array("msg" =>"{$from_email} 메일발송되었습니다", "is_default"=> false));
					log_message("debug", "{$config -> cf_admin_email} 메일주소로 {$from_email} 메일발송 되었습니다");
				}else{
					echo json_encode(array("msg" =>"{$config -> cf_admin_email} 메일주소로  {$from_email} 메일발송 실패하였습니다", "is_default"=> true));
					log_message("debug", "{$from_email} 메일발송 실패하였습니다");
				}
				//로그저장
				log_message("debug", $this->email->print_debugger());
			}else{
				echo json_encode(array("msg" =>"내용 혹은 메일주소가 잘못되었습니다. 다시 확인부탁드립니다.", "is_default"=> true ));
			}
		}else{
			echo json_encode(array("msg" =>"메일발송 오류입니다. 관리자에게 문의하세요.", "is_default"=> true));
		}
	}
}