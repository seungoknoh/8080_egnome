<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends FRONT_Controller {
    private $is_login = false;
    private $login_url = null;

    function __construct(){
        parent::__construct();
        $this -> load -> model("member/auth_m");
        $this -> load -> model('member/login_log_m');
        $this -> load -> helper(array('alert','error'));

        $this -> load -> library('form_validation');
        $this -> load -> library('user_agent');

        $this -> login_url = $this -> input -> get("login_url");
        //로그인여부
        $this -> is_login = $this -> session -> userdata("mb_id");
    }

    public function index(){
        $this -> login();
    }
    
    /* 로그아웃 */
    public function logout() {
        
        $this -> session -> sess_destroy();
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        alert('로그아웃 되었습니다.', '/');
        
        exit;
    }
    
    /* 네이버로그인 */
    public function naverLogin(){
        // 네이버 로그인 콜백 예제
        $client_id = "6Vq92bFhPdIdao25cyyH";
        $client_secret = "9lslddaKDM";
        
        $code = $this->input->get("code");
        if( $code != null ){
            $state =$this->input->get("state");
            $redirectURI = urlencode("http://pandanet.cafe24.com/auth/naverLogin");
            $url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $login_res = curl_exec ($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $login_obj = json_decode($login_res);
            
            curl_close ($ch);
            
            if($status_code == 200) {
                //네이버 프로필 정보 가져오기
                $headers = array();
                $headers[] = 'Content-length: 0';
                $headers[] = 'Content-type: application/json';
                $headers[] = 'Authorization:bearer '.$login_obj->access_token;
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL , "https://openapi.naver.com/v1/nid/me");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //리턴값 존재 없으면 그냥 뿌려줌
                
                $profile_res = curl_exec($ch);
                $profile_obj = json_decode($profile_res);
                
                //print_r($profile_obj->response);
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                $newdata = array(
                    'mb_level' => "1",
                    'access_token' => $login_obj->access_token,
                    'token_type' => $login_obj->token_type,
                    'refresh_token' => $login_obj->refresh_token,
                    'logged_in' => FALSE,
                    'mb_id' => $profile_obj -> response-> id,
                    'mb_name' => $profile_obj -> response-> name,
                    'mb_email' => $profile_obj -> response-> email
                );
                $this -> session -> set_userdata($newdata);
                alert("로그인성공하였습니다.", "/");
                curl_close ($ch);
            }
        }
    }
    
    /* 로그인 */
    public function login(){
        if( $this -> is_login ){
            back();
            exit;
        }

        $data['login_text'] = "로그인";
        $data['errors'] = array();
        $data['login_url'] = $this -> input -> get("login_url");

        if( $this -> session -> userdata('member')['logged_in'] == TRUE ) {
            redirect('/', 'refresh');
            exit;
        }
        
        $this -> form_validation -> set_rules('mb_id', '아이디', 'required|alpha_numeric');
        $this -> form_validation -> set_rules('mb_passwd', '비밀번호', 'required|min_length[4]|max_length[20]');
        
        if ($this -> form_validation -> run() == TRUE) {
            //token값 체크
            check_token();
            
            $auth_data = array(
                'mb_id' => $this -> input -> post('mb_id', TRUE),
                'mb_passwd' => $this -> input -> post('mb_passwd', TRUE)
            );
            
            $result = $this -> auth_m -> login($auth_data);
            if( !$result ){
                $msg = "아이디나 패스워드 확인이 필요합니다.";
                array_push($data['errors'], $msg);
            }else{
                $logdata = array(
                    "mll_ip" => $this -> input -> ip_address(),
                    "mll_useragent" => $this -> agent -> agent_string(),
                    "mb_id" => $result->mb_id,
                    "mb_idx" => $result->mb_idx,
                    "mll_url" => $data['login_url']
                );
                            
                if ( $auth_data['mb_id'] == $result -> mb_id && password_verify( $auth_data['mb_passwd'], $result -> mb_passwd )) {
                    if($result -> mb_state != 1){
                        $msg = "탈퇴되거나 정지된 계정입니다. 계정 복원시 관리자에게 문의해주세요";
                    
                        $logdata['mll_success'] = 0;
                        $logdata["mll_msg"] = $msg;
                        
                        $this -> login_log_m -> login_log_insert($logdata);

                        array_push($data['errors'], $msg); 
                    }else{
                        $newdata = array(
                            'mb_level' => $result -> mb_level,
                            'mb_id' => $result -> mb_id,
                            'mb_name' => $result -> mb_name,
                            'mb_email' => $result -> mb_email,
                            'is_admin' => $result -> mb_level > 7 ? TRUE : FALSE,
                            'logged_in' => TRUE
                        );
                        $this -> session -> set_userdata($newdata);
                        
                        //로그인 시간 저장
                        $this -> auth_m -> setLatestDateById($result->mb_id);
                        $msg = "로그인 되었습니다"; 

                        //로그저장
                        $logdata["mll_msg"] = $msg;
                        $logdata['mll_success'] = 1;
                        $this -> login_log_m -> login_log_insert($logdata);
                        
                        alert($msg, $data['login_url']);
                    }
                } else {
                    $msg = "아이디나 비밀번호를 확인해 주세요.";
                    //로그저장
                    $logdata["mll_msg"] = $msg;
                    $logdata['mll_success'] = 0;
                    
                    $this -> login_log_m -> login_log_insert($logdata);
                    array_push($data['errors'], $msg);
                }
            }
        } else {
            $this -> form_validation -> set_error_delimiters();   
            //폼검증에러출력
            $data['error'] = $this -> form_validation -> error_array();
        }

        $data['token'] = get_token();
        $data['page'] = 'front/auth/login_v';
		$this -> load -> view('front/_layout/container_v', $data);        
    }

    /* 레이어로그인  */
    public function layerLogin(){
        $data = [];
        $data['state'] = false;
        
        if( $_POST ){
            $this -> form_validation -> set_rules('mb_id', '아이디', 'required|alpha_numeric');
            $this -> form_validation -> set_rules('mb_passwd', '비밀번호', 'required|min_length[4]|max_length[20]');
            if ($this -> form_validation -> run() == TRUE) {
                $data['login_url'] = $this -> input -> post('login_url');
                $auth_data = array(
                    'mb_id' => $this -> input -> post('mb_id', TRUE),
                    'mb_passwd' => $this -> input -> post('mb_passwd', TRUE)
                );
                //아이디가 있는지 판단
                $result_id = $this -> auth_m -> login($auth_data);
                if($result_id){
                    $logdata = array(
                        "mll_ip" => $this -> input -> ip_address(),
                        "mll_useragent" => $this -> agent -> agent_string(),
                        "mb_id" => $result_id->mb_id,
                        "mb_idx" => $result_id->mb_idx,
                        "mll_url" => $data['login_url']
                    );  
                    if ( $auth_data['mb_id'] == $result_id->mb_id && password_verify( $auth_data['mb_passwd'], $result_id->mb_passwd )) {
                        if($result_id -> mb_state != 1){
                            $data['msg'] = "탈퇴되거나 정지된 계정입니다. 계정 복원시 관리자에게 문의해주세요";
                                            
                            $logdata['mll_success'] = 0;
                            $logdata["mll_msg"] = $data['msg'];
                        }else{
                            //로그인 세션 데이터
                            $newdata = array(
                                'mb_level' => $result_id -> mb_level,
                                'mb_id' => $result_id -> mb_id,
                                'mb_name' => $result_id -> mb_name,
                                'mb_email' => $result_id -> mb_email,
                                'is_admin' => $result_id -> mb_level > 7 ? TRUE : FALSE,
                                'logged_in' => TRUE
                            );
                            $this -> session -> set_userdata($newdata);
                            
                            //로그인 시간 저장
                            $this -> auth_m -> setLatestDateById($result_id->mb_id);

                            //로그인성공
                            $data['state'] = true;
                            $data['msg'] = "로그인 되었습니다.";

                            //로그저장
                            $logdata["mll_msg"] = $data['msg'];
                            $logdata['mll_success'] = 1;
                        }   
                    }else{
                        $data['msg'] = "아이디, 비밀번호를 확인해주세요.";
                        //로그저장
                        $logdata["mll_msg"] = $data['msg'];
                        $logdata['mll_success'] = 0;
                    }
                    $this -> login_log_m -> login_log_insert($logdata); //로그저장  
                }else{
                    $data['msg'] = "등록된 아이디가 아닙니다.";
                }          
            }else{
                $this -> form_validation -> set_error_delimiters();  
                $data['errors'] = $this -> form_validation -> error_array();
            }
            //내보내기
            echo json_encode($data);
        }else{            
            $data['login_url'] = $this -> input -> get("login_url");
            $data['token'] = get_token();
            $this -> load -> view('front/auth/layer_login_v', $data);
        }   
    }
}
?>