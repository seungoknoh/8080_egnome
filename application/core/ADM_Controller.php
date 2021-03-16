<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ADM_Controller extends CI_Controller {
	public $title = null;
	public $html_lang = null;
	public $site_config = null;
	public $admin_menu = null;
	public $current_page_info = null;
	public $main_page = "/backS1te/main";
	public $menu_depth1 = null;

	function __construct(){
		parent::__construct();

		//시간설정
		date_default_timezone_set('Asia/Seoul');

		$this -> load -> helper(array('alert', 'url', 'ux', 'formtag', 'uploader'));
		$this -> load -> model('member/auth_m');
		$this -> load -> model('config/menu_m');
		$this -> load -> model('config/authSet_m');
		$this -> load -> model('config/config_m');

		//기본 설정.
		$this->site_config = $this->config_m->get_config();

		//언어
		$this->lang->load('error');

		//관리자언어는 무조건 korean
		$this->_require_lang('ko');
	}

     //json 뷰
	 function _json_view($result){
        $this->output->set_content_type('text/json');
        $this->output->set_output(json_encode($result));       
	}

	/* 사이트 헤더, 푸터가 자동으로 추가된다. */
	public function _remap($method){
		if( $this -> session -> userdata('logged_in') == TRUE ) {		    
		    //해당 레벨이 아닌경우
		    $result = $this -> auth_m -> getMemberById($this->session->userdata("mb_id"));
		    if($result->mb_level < $this->config->item('admin_level')){
		        alert('접근할 수 없습니다.', $this->config->item('base_url'));
		    }
		    if($result->mb_state != 1){
		        alert('탈퇴되거나 정지된 계정입니다. 계정 복원시 관리자에게 문의해주세요.', $this->config->item('base_url'));
		    }
			if( method_exists($this, $method) ){
				$this->{"{$method}"}(); 
			}
		}else{
		    //로그인 상태가 아닌경우
			if( $login = $this -> uri -> segment(3) == "login"){
				$this->{"{$method}"}();
			}else{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				alert('로그인이 필요합니다.', '/backS1te/auth/login');
			}
		}
	}

	//다국어 관리
    function _require_lang($_lang=''){
        //기본설정 : korean
        $session_lang = $this->session->userdata("language");

        //lang파라미터 값이 없는경우 -> 세션으로판단
        $lang = $this->input->get("lang", true) == null ? $session_lang : $this->input->get("lang", true);

        //세션값도 null인 경우 
        if( $lang == null ){
            $lang = "ko";
		}
		
		//값을 받은경우
		if( $_lang != '' ){
			$lang = $_lang;
		}

        //세션값과 lang 파라미터값이 다른경우에만 세션에 저장.
        if( $session_lang != $lang){
            $this->session->set_userdata("language", $lang);
        }

        //코드로 다국어판단.
        $lang_code = "";
        switch($lang){
            case "ko" :
                $lang_code = "korean";
                break;
            case "en" :
                $lang_code = "english";
                break;
            default :
                $lang_code = "korean";
                break;
        }
		
        //html 문서 코드 위한 값
        $this->html_lang = $lang;

        $this->config->set_item('language', $lang_code);

        //해당 언어의 common_lang.php 파일 불러옴
        $this->lang->load('common', $lang_code);
	}

    //로그인차단
    function _require_login($return_url=''){
        // 로그인이 되어 있지 않다면 로그인 페이지로 리다이렉션
        if($this->session->userdata('logged_in') == null){       
			$msg = "로그인 먼저 해 주세요";
			$url = '/backS1te/auth/login?return_url='.rawurlencode($return_url);            
            redirect($url);
        }
	}
		
	//register 뷰
	function _full_view($page, $option=[]){
		
		$this->title = $this->site_config->cf_title;

		//현재경로 세션에 저장
		$this->session->set_userdata("current_url", $this->uri->uri_string());

		//타이틀설정
		if(array_key_exists("title", $option)){
			$this->title = $option['title']." > ".$this->title;
		}

		$data = array(
			"banner"=> "front/_include/top_banner_v",
			"footer"=> "front/_include/intro_footer_v",
			"page" => $page,
			"option" => $option,
		);

		$this->load->view("/admin/_layout/full_layout_v", $data);
	}

	//메뉴권한체크
	function _require_auth(){
		//최고관리자인경우 패스
		if( $this->session->userdata("mb_id") == $this->site_config->cf_admin ) return false;
		$auth_menu = $this->authSet_m->auth_view($this->session->userdata("mb_id"));
		
		//메인페이지인경우 더이상 확인안함
		if("/".$this->uri->uri_string() == $this->main_page){
			return;
		}

		//메인페이지가 아닌 경우 접근할수있는 페이지가 없을때 메인으로 보냄. 
		if( $auth_menu == null ){
			replace($this->main_page);
			exit;
		}

		//접근가능한페이지 
		$auth_menu_list = explode(",", $auth_menu->mn_idxs);

		if( $this->current_page_info != null && isset($this->current_page_info->mn_idx) ){
			$is_auth_ok =  array_search($this->current_page_info->mn_idx, $auth_menu_list) === 0 ? true : array_search($this->current_page_info->mn_idx, $auth_menu_list);
			if(!$is_auth_ok){
				alert("접근불가능한 페이지입니다.".array_search($this->current_page_info->mn_idx, $auth_menu_list), $this->main_page);
			}
		}

	}

	//메뉴알아내기
	function _require_menu(){
		//메뉴
		$this->current_page_info = $this->menu_m->get_current_menu("AD");
	

		$current_code = '';
		if( $this->current_page_info != null){
			$this->menu_depth1 = $this->menu_m->get_menu_info("AD", substr($this->current_page_info->mn_code, 0, 2));
			$current_code = $this->current_page_info->mn_code;
		}

		$this->admin_menu = ux_menu_admin_html($this->menu_m->admin_all_menu_list("AD", $this->session->userdata('mb_id')), true, $current_code);
		
	}

    //기본 뷰
    function _view($page, $option=[]){

		// 로그인 체크
		$return_url = uri_string();
		$this->_require_login($return_url);
		
		//메뉴 알아내기
		$this->_require_menu();

		//권한체크
		$this->_require_auth();

        $option = array(
            "header"=> "/admin/_include/header_v",
            "footer"=> "/admin/_include/footer_v",
            "sidebar"=>"/admin/_include/sidebar_v",
            "page" => $page,
			"menu_info" => $this->current_page_info,
			"menu_depth1" => $this->menu_depth1,
			"option" => $option,
			"config" => $this->site_config,
			"admin_menu" => $this->admin_menu
        );

        $this->load->view("/admin/_layout/container_v", $option);
	}

    //로그인뷰
    function _login_view($page, $option=[]){
        $option = array(
            "header"=> "/admin/_include/header_v",
            "footer"=> "/admin/_include/footer_v",
            "page" => $page,
            "option" => $option
        );
        $this->load->view("/admin/_layout/layout_login_v", $option);
	}

    //window 뷰
    function _win_view($page, $option=[]){
        // 로그인 체크
		//$return_url = uri_string();
        //$this->_require_login($return_url);
        $option = array(
            "page" => $page,
            "option" => $option
        );
        $this->load->view("/admin/_layout/win_layout_v", $option);
	}	
		
}

/* End of file Adm_Controller.php */
/* Location: ./application/core/Adm_Controller.php */

?>