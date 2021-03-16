<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends FRONT_Controller {
    private $is_login = false;
    private $member = null;

    function __construct(){
        parent::__construct();
        $this -> load -> model("member/auth_m");
        $this -> load -> model("member/member_m");
        $this -> load -> helper(array('alert','error'));

        $this -> load -> library('form_validation');
        $this -> load -> library('user_agent');

		//로그인여부
        $this -> is_login = $this -> session -> userdata("mb_id");
        if( $this -> is_login ){
            $this -> member = $this -> auth_m -> login(array("mb_id" => $this -> is_login));
        }
    }

    public function index(){
        $this -> login();
    }
    
    public function join(){
        //$this -> load -> view('front/member/join_v');
    }

    //마이페이지
    public function mypage(){
        $data['errors'] = array();

        $data['token'] = get_token();
        $data['page'] = 'front/member/mypage_v';
		$this -> load -> view('front/_layout/edu_container_v', $data);     
    }

    //비밀번호변경
    public function change_pw(){

        if( !$this -> is_login || $this -> member == null ){
            alert("로그인해주세요.","/eduMain?login_url=/member/change_pw");
            exit;
        }

        $data['errors'] = array();
        
        //폼검증
        $this -> form_validation -> set_rules('pre_mb_passwd', '기존 비밀번호', "trim|required|callback_passwd_check");

        //새로운 비밀번호
        $this -> form_validation -> set_rules('mb_passwd', '새로운 비밀번호', 'trim|required|min_length[6]|max_length[20]|regex_match[/^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/]'); 

        $this -> form_validation -> set_rules('re_mb_passwd', '새로운 비밀번호 확인', 'trim|required|min_length[6]|max_length[20]|matches[mb_passwd]|regex_match[/^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/]');
       

        if ($this -> form_validation -> run() == TRUE) {
             //token값 체크
             check_token();

             //비밀번호해쉬값
             $hash =  password_hash($this -> input -> post('mb_passwd', TRUE), PASSWORD_BCRYPT);
             $member = $this -> auth_m -> getMemberById($this -> is_login);
             $changepw_data = array(
                'mb_idx' =>  $member -> mb_idx,
                'mb_passwd' => $hash
            );
            $result = $this -> member_m -> member_passwd_update($changepw_data);
            if( $result ){
                $this -> session -> sess_destroy(); 
            
                alert("비밀번호가 변경 되었습니다. 변경하신 비밀번호로 다시 로그인해주세요.", "/eduMain?login_url=".SITE_URL."/eduMain");
                exit;
            }else{
                alert("다시 시도 해주세요.", "");
     
            }
        }else{
            $this -> form_validation -> set_error_delimiters();   
            //폼검증에러출력
            $data['errors'] = $this -> form_validation -> error_array();            
        }

        $data['token'] = get_token();
        $data['page'] = 'front/member/change_password_v';
		$this -> load -> view('front/_layout/edu_container_v', $data);     
    }

    //비밀번호체크
    public function passwd_check($str){
       //멤버 비밀번호해시
       $result = $this -> member;
        if ( $result!= null && !password_verify($str, $result -> mb_passwd)){
            $this -> form_validation -> set_message('passwd_check', '입력한 기존 비밀번호가 다릅니다.<br> 다시 입력해주세요.');
            return FALSE;
        }else{
            return TRUE;
        }
    }

}