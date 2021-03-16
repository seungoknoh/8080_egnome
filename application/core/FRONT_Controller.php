<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FRONT_Controller extends CI_Controller {
	public $footer_inquery = true;
	public $title = null;
	public $html_lang = null;
	public $site_config = null;
	public $front_menu = null;
    public $front_tail_menu = null;
    public $front_depth1_menu = null;
    public $front_current_menu = null;
    public $current_page_info = null;
    public $depth1_page_info = null;
    public $metaTag = null; 
    public $current_code = null;
    //public $menu_sub_list = null;

	public $global = array(
		"ko" => "KOR",
		"en" => "ENG",
    );
	function __construct(){
		parent::__construct();
		$this -> load ->  helper(array('common','alert', 'ux', 'uploader'));
		$this -> load -> model('member/auth_m');
		$this -> load -> model('config/menu_m');
		$this -> load -> model('config/authSet_m');
		$this -> load -> model('config/config_m');
		
		//기본 설정.
		$this->site_config = $this->config_m->get_config();

		//언어.
        $this->_require_lang();

        if($this->session->userdata("user_info") != null){
            $this->user_info = $this->session->userdata("user_info");
        }
        
	}

	    /**
     * _set_timezone 서버시간설정.
     *
     * @return void
     */
    public function _set_timezone() {
        date_default_timezone_set("Asia/Seoul");
    }
	   /**
     * _require_lang 언어설정 
     * 세션설정
     *
     * @return void
     */
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
    
    /**
     * _require_ip 아이피설정
     *
     * @return void
     */
    function _require_ip(){
        $client_ip = get_client_ip();
        if( !in_array($client_ip, $this->config->item('access_ips')) ){
            alert_back('접근 불가능한 IP 주소입니다.'.$client_ip);
        }
    }

	//로그인차단
    // function _require_login($return_url, $msg='')
	// {
    //     // 로그인이 되어 있지 않다면 로그인 페이지로 리다이렉션
    //     if($this->user_info == null){       
	// 		$msg = $this->lang->line("text_required_login");
    //         $url = '/auth/login?return_url='.rawurlencode($return_url);
    //         if( $msg != ''){
    //             alert($msg, $url);
    //         }else{
    //             redirect($url);
    //         }
    //     }
	// }
    
    //기본 뷰
    function _view($page, $data=[]){
		//메뉴
		$this->_get_menu();
		
		//타이틀
        $this->_get_title();
        
        //타이틀설정
        if(array_key_exists("title", $data)){
            $this->title = $data['title']." - ".$this->title;
        }
        if($page == "front/board/skin/bigdata/category_v" || $page == "front/board/skin/bigdata/view_v" ){
            $this->title = "Big Data Management - 사업분야 | egnome";
        }
        $metaTag = !isset($metaTag) ? "" : $metaTag;
        //현재경로 세션에 저장
        $this->session->set_userdata("current_url", $this->uri->uri_string());
        $option = array(
            "header"=> "/front/_include/header_v",
            "footer"=> "/front/_include/footer_v",
			"menu_list" => $this->front_menu,
            "menu_tail_list" => $this->front_tail_menu,
            //"menu_sub_list" => $this->menu_sub_list,
			"current_code" => $this->current_code,
            "page_info" => $this->current_page_info,
			"config" => $this->site_config,
            "title" => $this->title,
            "metaTag" => $metaTag,
            "page" => $page,
			"option" => $data,
        );

		if( array_key_exists("is_main", $data) && $data['is_main'] == true ){
			$this->load->view("front/_layout/main_v", $option);
        }else{
            $this->load->view("front/_layout/container_v", $option);
        }
    }
        
    //기본 bw full 뷰(content)
    function _full_view($page, $data=[]){

        //메뉴
        $this->_get_menu();
        
        //타이틀
        $this->_get_title();
        
        //타이틀설정
        if(array_key_exists("title", $data)){
            $this->title = $data['title']." - ".$this->title;
        }

        $metaTag = !isset($metaTag) ? "" : $metaTag;
        //현재경로 세션에 저장
        $this->session->set_userdata("current_url", $this->uri->uri_string());

        $option = array(
            "header"=> "/front/_include/header_v",
            "footer"=> "/front/_include/footer_v",
            "menu_list" => $this->front_menu,
            "menu_tail_list" => $this->front_tail_menu,            
            //"depth1_menu_list" => $this->front_depth1_menu,
            "page_info" => $this->current_page_info,
            "config" => $this->site_config,
            "title" => $this->title,
            "metaTag" => $metaTag,
            "page" => $page,
            "option" => $data,
        );
        
        $this->load->view("/front/_layout/container_v", $option);
    }


	//브라우저 경로 타이틀
	function _get_title(){
		if( $this->site_config != null ){
			$this->title .=  $this->site_config->cf_title;
		}
	}

	//메뉴설정
	function _get_menu($code="FR"){
		//현재메뉴
        $this->current_page_info = $this->menu_m->get_front_current_menu($code);
        if($this->current_page_info != null){
            //언어셋팅
            if($this->html_lang == "en"){
                $this->current_page_info->name = $this->current_page_info->mn_name_en;
            }
            if($this->html_lang == "ko"){
                $this->current_page_info->name = $this->current_page_info->mn_name;
            }
        }

		$menu_depth1 = null;
        $current_code = "";

        //메뉴가 존재하는경우
		if( $this->current_page_info != null ){
            $current_code = $this->current_page_info->mn_code;
            
            $this->front_current_menu = $this->menu_m->menu_sub_list($code, substr($this->current_page_info->mn_code,0,2));
            $this->depth3_page_info = $this->menu_m->menu_code_sub_list($code, substr($this->current_page_info->mn_code,0,4));

			//대메뉴
            $this->depth1_page_info = $this->menu_m->get_menu_info($code, substr($this->current_page_info->mn_code, 0, 2));

            if( $this->depth1_page_info != null ){
                //언어셋팅
                if($this->html_lang == "en"){
                    $this->depth1_page_info->name = $this->depth1_page_info->mn_name_en;
                }
                if($this->html_lang == "ko"){
                    $this->depth1_page_info->name = $this->depth1_page_info->mn_name;
                }
            }

			//중메뉴
            $this->depth2_page_info = $this->menu_m->get_menu_info($code, substr($this->current_page_info->mn_code, 0, 4));

            if( $this->depth2_page_info != null ){
                //언어셋팅
                if($this->html_lang == "en"){
                    $this->depth2_page_info->name = $this->depth2_page_info->mn_name_en;
                }
                if($this->html_lang == "ko"){
                    $this->depth2_page_info->name = $this->depth2_page_info->mn_name;
                }
            }
                        
            $this->title .= $this->current_page_info->name;

            //if( $this->depth1_page_info != null ){
            // depth3 mene처리를 위해 분리
            if(strlen($current_code) == 4) {
                $this->title .= " - ".$this->depth1_page_info->name." | ";
            } elseif(strlen($current_code) == 6){
                $this->title .= " - ".$this->depth2_page_info->name." - ".$this->depth1_page_info->name." | ";
            } else {
                $this->title .= " - ".$this->depth1_page_info->name." | ";
            }
		}
        
        //$this->front_menu = ux_total_menu_html($code, '0', $current_code, $this->html_lang); // 모든메뉴 불러올때
        $this->front_menu = ux_main_menu_html($code, '0', $current_code, $this->html_lang); // main(depth2까지 모든메뉴)) 메뉴 불러올때
        
        $this->front_tail_menu = ux_total_menu_html($code, '1', $current_code, $this->html_lang);//tail(depth3까지 모든메뉴) 메뉴
        //$this->front_depth1_menu = ux_menu_html($this->menu_m->menu_list($code), false, $current_code, $this->html_lang);
        //$this->menu_sub_list = ux_menu_tab_sub_html($this -> menu_m -> menu_code_sub_list('FR', $current_code), $current_code);
	}

    //json 뷰
    function _json_view($result){
        $this->output->set_content_type('text/json');
        $this->output->set_output(json_encode($result, JSON_UNESCAPED_UNICODE));       
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
        $this->load->view("/front/_layout/win_layout_v", $option);
	}	
	

	/* 사이트 헤더, 푸터가 자동으로 추가된다. */
	public function _remap($method){
		//로그인상태 판별
		if( $this -> session -> userdata('logged_in') == TRUE ) {
			$result = $this -> auth_m -> getMemberById($this->session->userdata("mb_id"));
		    //if($result->mb_level < $this->config->item('admin_level')){
		    //    alert('접근할 수 없습니다.', $this->config->item('base_url'));
			//var_dump($result);
		}
		if( method_exists($this, $method) ){
			$this->{"{$method}"}();
		}
	}
}

/* End of file FRONT_Controller.php */
/* Location: ./application/core/FRONT_Controller.php */

?>