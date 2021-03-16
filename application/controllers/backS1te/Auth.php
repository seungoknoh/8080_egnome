<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends ADM_Controller {

	function __construct(){
		parent::__construct();
		$this -> load -> model('member/auth_m');
		$this -> load -> model('member/login_log_m');
		$this -> load -> helper(array('form', 'url'));
			
	}
	
	/* 로그인 처리 */
	public function login(){
	    $this-> config -> set_item("title", "로그인");
		if( $this -> session -> userdata('logged_in') == TRUE ) {
			redirect('/backs1te/main', 'refresh');
			exit;
		}
		$this -> load -> library('form_validation');
		$this -> load -> helper('alert');
		$this -> load -> library('user_agent');
		
		$this -> form_validation -> set_rules('mb_id', '아이디', 'required|alpha_numeric');
		$this -> form_validation -> set_rules('mb_passwd', '비밀번호', 'required|min_length[4]|max_length[20]');
        $msg = "";
		if ($this -> form_validation -> run() == TRUE) {
		    //token값 체크
		    check_token();

			$auth_data = array(
				'mb_id' => $this -> input -> post('mb_id', TRUE),
			    'mb_passwd' => $this -> input -> post('mb_passwd', TRUE)
			);
			
			$result = $this -> auth_m -> login($auth_data);
			
			$logdata = array(
			    "mll_ip" => $this -> input -> ip_address(),
			    "mll_useragent" => $this -> agent -> agent_string(),
			    "mb_id" => $result->mb_id,
				"mb_idx" => $result->mb_idx,
				"mll_url" => ""
			);

			if ( $auth_data['mb_id'] == $result -> mb_id && password_verify( $auth_data['mb_passwd'], $result -> mb_passwd )) {
				if($result->mb_state != 1){
				    $msg = "탈퇴되거나 정지된 계정입니다. 계정 복원시 관리자에게 문의해주세요";
				    
				    $logdata['mll_success'] = 0;
				    $logdata["mll_msg"] = $msg;
				    $this -> login_log_m -> login_log_insert($logdata);
				    
				    alert($msg, '/backS1te/main');
				}else{
				    $newdata = array(
				        'mb_level' => $result -> mb_level,
				        'mb_id' => $result -> mb_id,
				        'mb_name' => $result -> mb_name,
				        'mb_email' => $result -> mb_email,
				        'logged_in' => TRUE
				    );
				    
				    //세션 저장
    				$this -> session -> set_userdata($newdata);
    				//로그인 시간 저장
    				$this->auth_m -> setLatestDateById($result->mb_id);
    				$msg = "로그인 되었습니다";
    				
    				//로그저장
    				$logdata["mll_msg"] = $msg;
    				$logdata['mll_success'] = 1;
    				$this -> login_log_m -> login_log_insert($logdata);
    				
    				alert($msg, '/backS1te/main');
				}
			} else {
				
			    $msg = "아이디나 비밀번호를 확인해 주세요.";
			    //로그저장
			    $logdata["mll_msg"] = $msg;
			    $logdata['mll_success'] = 0;
			    $this -> login_log_m -> login_log_insert($logdata);
			    alert($msg, '/backS1te/auth/login');
				exit;
			}
		} else {
		    $this->form_validation->set_error_delimiters();
            //토큰생성
		    $data['token'] = get_token();
            
            //폼검증에러출력
            $data['error'] = $this->form_validation->error_array();
			
			//스킨경로
			$data['page'] = $this->config->item("admin_dir")."/auth/login_v";
			$this -> load -> view($this->config->item("admin_dir").'/_layout/login_v', $data);				
		}
	}

	/* 로그아웃 */
	public function logout() {
		$this -> load -> helper('alert');
		$this -> session -> sess_destroy();
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		alert('로그아웃 되었습니다.', '/backS1te/auth/login');
		exit;
	}

}
?>