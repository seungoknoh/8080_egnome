<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends ADM_Controller {
    //메뉴타입
    public $mn_type = "AD";

	function __construct(){
		parent::__construct();
		
		//메뉴타입
		$this -> mn_type = $this -> input -> get("mn_type");
		
		$this -> load -> helper( array('alert','editor','url','form'));
		$this -> load -> model('config/menu_m');
		$this -> load -> model('config/authSet_m');
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> main();
	}
	
	/* 메인 */
	public function main(){
		$data["mn_type"] = $this -> input -> get('mn_type');

		//스킨경로
		$data['page'] = $this -> config -> item("admin_dir")."/menu/main_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);		
	}

	/* 메뉴리스트저장 */
	public function json_listSave(){
		if($_POST){
			$mn_idx_arr = $this->input->post('mn_idx');
			$mn_code_arr = $this->input->post('mn_code');
			$mn_type = $this->input->post('mn_tpye');
			
			$result_arr = array();
			for($i=0; $i<count($mn_code_arr);$i++){
				$data = array(
					'mn_code' => $mn_code_arr[$i],
					'mn_idx' => $mn_idx_arr[$i]
				);
				$result = $this -> menu_m -> menu_list_update($data);
				if( $result != null){
				    array_push($result_arr, $result);
				}
			}
			
			//권한 코드변경(변경전코드)
			//$this -> authSet_m -> auth_code_change($result_arr);
			
			echo json_encode(array("result"=>$result_arr ));
		}
	}

	/* 메뉴리스트 */
	public function json_lists(){
		$this-> mn_type = $this -> input -> get('mn_type');
		echo json_encode(array("lists" => $this -> menu_m -> menu_list($this-> mn_type), "MN_TYPE"=> $this-> mn_type) );
	}

	/* 메뉴리스트 */
	public function json_listSubMenu(){
		$mn_code = $this -> input -> get('mn_code');
		echo json_encode(array("parent" => $mn_code, "lists" => $this -> menu_m -> menu_code_list($this-> mn_type, $mn_code)));
	}
	
	/* 메뉴 상세 */
	public function json_view(){
		$this-> mn_type = $this -> input -> get('mn_type');
		$mn_idx = $this -> uri -> segment(4);
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('mn_name', '메뉴명(국문)', 'required');
			$this -> form_validation -> set_rules('mn_name_en', '메뉴명(영문)', 'required');
			$this -> form_validation -> set_rules('mn_link', '링크', 'required');
		
			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'mn_code' => $this -> input -> post('mn_code', TRUE),
					'mn_name' => $this -> input -> post('mn_name', TRUE),
					'mn_name_en' => $this -> input -> post('mn_name_en', TRUE),
					'mn_icon' => $this -> input -> post('mn_icon', TRUE),
					'mn_link' => $this -> input -> post('mn_link', TRUE),
					'mn_level' => $this -> input -> post('mn_level', TRUE),
					'mn_is_view' => $this->input -> post("mn_is_view") == "1" ? 1 : 0,
					'mn_is_alert' => $this->input -> post("mn_is_alert") == "1" ? 1 : 0,
					'mn_idx' => $mn_idx
				);
				$result = $this -> menu_m -> menu_update($write_data);
				if( $result ){
					echo json_encode(array("data" => "success"));
				}else{
					echo json_encode(array("data" => "failed"));
				}
			}else{
				$this->form_validation->set_error_delimiters();
				echo json_encode(array("data" => "failed", "error" => $this->form_validation->error_array() ));
			}
		}else{
			$data['view'] = $this -> menu_m -> menu_view($this-> mn_type, $mn_idx);

			$this -> load -> view($this->config->item("admin_dir").'/menu/modify_v', $data);				
		}
	}

	/* 메뉴 상세 */
	public function json_write(){

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('mn_name', '메뉴명(국문)', 'required');
			$this -> form_validation -> set_rules('mn_name_en', '메뉴명(영문)', 'required');
			$this -> form_validation -> set_rules('mn_link', '링크', 'required');
		
			if( $this -> form_validation -> run() == TRUE ){
				
				//메뉴 다음 코드
			    //$mn_code = $this-> menu_m -> menu_next_code( $this -> input -> post('mn_type', TRUE) , $this -> input -> post('mn_code') );

				$write_data = array(
				    'mn_type' => $this -> input -> post('mn_type', TRUE),
					'mn_code' => $this -> input -> post('mn_code', TRUE),
					'mn_name' => $this -> input -> post('mn_name', TRUE),
					'mn_name_en' => $this -> input -> post('mn_name_en', TRUE),
					'mn_link' => $this -> input -> post('mn_link', TRUE),
					'mn_level' => $this -> input -> post('mn_level', TRUE),
					'mn_icon' => $this -> input -> post('mn_icon', TRUE),
					'mn_is_view' => $this->input -> post("mn_is_view") == "1" ? 1 : 0 ,
					'mn_is_alert' => $this->input -> post("mn_is_alert") == "1" ? 1 : 0 
				);
				$result = $this -> menu_m -> menu_insert($write_data);
				if( $result ){
					echo json_encode(array("data" => "success"));
				}else{
					echo json_encode(array("data" => "failed"));
				}
			}else{
				$this->form_validation->set_error_delimiters();
				echo json_encode(array("data" => "failed", "error" => $this->form_validation->error_array() ));
			}
		}else{
			$mn_idx = $this -> input -> get('mn_idx');
			
			if( $mn_idx != null ){
				$mn = $this->menu_m->menu_view($this->mn_type, $mn_idx);
				$mn_code = $mn->mn_code;
			}else{
			    $mn_code = 0;
			}
			
			$data['mn_type'] = $this -> mn_type;
			//메뉴 다음 코드
			$data['mn_next_code'] = $this-> menu_m -> menu_next_code( $this -> mn_type, $mn_code );
			
			$this -> load -> view('admin/menu/write_v', $data);
		}
	}
	
	/* 메뉴 삭제 */
	public function json_delete(){
		if($_POST){
			$mn_idx = $this -> input -> post('mn_idx');
			$mn_type = $this -> input -> post('mn_type');
			$view_data = $this -> menu_m -> menu_view( $mn_type, $mn_idx );
			if( $view_data != null ){
			    $this -> menu_m -> menu_delete($mn_idx);
				echo json_encode(array("result"=> "success", "msg" => "삭제하였습니다."));
			}else{
				echo json_encode(array("result"=> "failed", "msg" => "삭제할 수 없습니다."));
			}
		}
	}

	/* json menu */
	public function json_list(){
		$stx = $this -> input -> get("stx");
		$all_menu_list = $this -> menu_m -> all_menu_list("AD");
		$menu_list = array();
		foreach($all_menu_list as $al){
			if( strlen($al -> mn_code) == 2 ){ continue; } 
				
			if( $stx != null ){
				if( strpos($al -> mn_name , $stx) > -1 ){
					array_push($menu_list, $al);
				}
			}else{
				array_push($menu_list, $al);
			}		
		}
		$data['list'] = $menu_list;
		Header('Content-Type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
}
?>