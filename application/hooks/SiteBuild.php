<?php
	class SiteBuild extends CI_Controller{
		private $title = "";
		private $menu = null;

		function __construct(){

			$this-> CI =& get_instance();
		
			//Check if session lib is loaded or not
			if(!isset($this->CI->session)){
				//If not loaded, then load it here
				$this->CI->load->library('session');
			}

			$this-> CI-> load -> helper(array('alert', 'ux'));
			$this-> CI-> load -> model('member/auth_m');
			$this-> CI-> load -> model('config/menu_m');
			$this-> CI-> load -> model('config/authSet_m');
			$this-> CI-> load -> model('config/config_m');
		}

		//header
		public function loadHead(){

			$config = $this-> CI-> config_m -> get_config();
			$data['config'] = $config;
			
			//메뉴
			$this -> menu = $this-> CI-> menu_m -> get_front_current_menu();
			$data['current_menu'] = $this -> menu;
			$data['current_code'] = "";
			$data['$menu_depth1'] = "";
			$menu_depth1 = null;
			
			//경로 title
			if( $config->cf_title != null ) $this -> title .= $config->cf_title;
			
			//1차 , 2차 메뉴
			if( $this -> menu != null ){
				$data['current_code'] = $this -> menu -> mn_code ;
				$data['current_idx'] = $this -> menu -> mn_idx ;
				
				$menu_depth1 = $this-> CI->  menu_m -> get_menu_info("FR", substr($data['current_code'], 0, 2));
				
				if( $menu_depth1 != null ) $this->title = $menu_depth1->mn_name." | ".$this->title;
				if( $this -> menu != null ) $this->title = $this -> menu->mn_name." < ".$this->title;
			}

			$data['current_depth1'] = $menu_depth1;
			$data['title'] = $this->title;

			// 헤더 include
			$this -> CI -> load -> view('front/_include/head_v', $data);

		}

		public function loadHeader(){
			// 현재메뉴
			$data['current_code'] = $this -> menu -> mn_code ;
			$data['current_idx'] = $this -> menu -> mn_idx ;
			$data['menu_list'] = ux_menu_html($this -> CI -> menu_m -> all_menu_list("FR"), $this -> menu -> mn_code );

			$this -> CI -> load -> view('front/_include/header_v', $data);
			
			if( $this -> menu != null ){
				//위치안내
				$this -> CI -> load -> view('front/_include/location_v', $data);
			}
		}

		//footer
		public function loadFooter(){
			// 푸터 include
			$this -> CI -> load -> view('front/_include/footer_v');
			$this -> CI -> load -> view('front/_include/tail_v');
		}

		//메타태그 넣기
		public function loadMetaTag(){


		}
	}
?>