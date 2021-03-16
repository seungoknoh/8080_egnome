<?php

/**
 * 클래스 한글설명
 *
 * Created on 2018. 4. 30.
 * @package      application
 * @subpackage   controllers
 * @category     backS1te
 * @author       leeminji25 <leeminji25@wixon.co.kr>
 * @link         <a href="http://codeigniter-kr.org" target="_blank">http://codeigniter-kr.org</a>
 * @version      0.1
 */


defined('BASEPATH') OR exit('No direct script access allowed');

class AuthSet extends ADM_Controller {
    /**
     * 권한관리클래스
     */

    public $stx = null;
    public $sfl = null;
    public $per_page = null;
    public $query = null;
    public $col_per_page = 10;
    
    public $list_href = "";
    public $write_href = "";
    
	function __construct(){
		parent::__construct();
		$this-> sfl = $this -> input ->get("sfl", FALSE);
		$this-> stx = $this -> input ->get("stx", FALSE);
		$this-> per_page = $this -> input -> get("per_page", FALSE);
		$this-> query = "?per_page=".$this -> per_page."&stx=".$this -> stx."&sfl=".$this -> sfl;
		
		$this -> load -> model('config/authSet_m');
		$this -> load -> model('config/menu_m');
		$this -> load -> model('member/member_m');
		
		$this->list_href = "/backS1te/authSet";
		$this->write_href = "/backS1te/authSet/write";
	}
	
	function index(){
	    $this -> lists();
	}

	function write(){
	    $data['view'] = null;
	    $data['errors'] = array();
	    $this -> load -> helper('form');
	    
	    //메뉴리스트
	    $data['menu_list'] = $this -> menu_m -> all_menu_list("AD");
	    
	    //관리자 리스트
	    $data['admin_list'] = $this -> authSet_m -> admin_list($this->config->item("admin_level"), $this->session->userdata("mb_id"));
	    
	    //폼 검증 라이브러리 로드
	    $this -> load -> library('form_validation');

	    if( $_POST ){
	        //폼 검증할 필드와 규칙 사전 정의
	        $this -> form_validation -> set_rules('mb_id', '관리자아이디', 'required');
	        if( $this -> form_validation -> run() == TRUE ){
	            //글작성
	            $write_data = array(
	                'au_id' => $this->session->userdata("mb_id"),
	                'mb_id' => $this -> input -> post('mb_id', TRUE),
	                'mn_idx' => $this -> input -> post('mn_idx', TRUE)
	            );
	            $result = $this -> authSet_m -> auth_insert($write_data);
	            
	            if( $result ){
	                alert("저장에 성공하였습니다.", $this->list_href);
	            }else{
	                alert("저장에 실패하였습니다.", $this->list_href);
	            }
	        }else{
	            $this->form_validation->set_error_delimiters();
	            $data['errors'] = $this->form_validation->error_array();
	        }
	    }
	 
		
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/authSet/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);			
	}
	
	
	function update(){
	    $data['errors'] = array();
	    //폼 검증 라이브러리 로드
	    $this -> load -> library('form_validation');
	    $mb_id = $this -> uri -> segment(4);

	    if( $_POST ){
	        //글작성
	        $write_data = array(
	            'au_id'   => $this -> session -> userdata("mb_id"),
	            'mb_id'   => $this -> input -> post('mb_id', TRUE),
	            'mn_idx'  => $this -> input-> post('mn_idx', TRUE)
	        );
	        $result = $this -> authSet_m -> auth_update($write_data);
	        
	        if( $result ){
	            array_push($data['errors'], "저장에 성공하였습니다.");
	        }else{
	            array_push($data['errors'], "저장에 실패하였습니다.");
	        }
	    }
	    
	    //메뉴리스트
	    $data['view'] = $this -> authSet_m -> auth_view($mb_id);
	    $data['menu_list'] = $this -> menu_m -> all_menu_list("AD");
	    
		//스킨경로
		//$this->_view($this->config->item("admin_dir")."/authSet/write_v", $data);			
		$data['page'] = $this->config->item("admin_dir")."/authSet/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}
	
	
	function lists(){
	    //페이지네이션 설정
	    $config['total_rows'] = $this -> authSet_m -> auth_list('count','','',$this -> stx, $this -> sfl);
	    $data['total'] = $config['total_rows'];
	    
	    //페이지네이션 라이브러리 로딩 추가
	    $this -> load -> library('pagination');
	    
	    //페이지네이션 설정
	    $config['base_url'] = ADM_DIR.'/authSet/';
	    
	    //페이지당 보여줄 갯수
	    $config['per_page'] = $this-> col_per_page ;
	    $config['page_query_string'] = TRUE;

	    //페이지네이션 초기화
	    $this -> pagination -> initialize($config);
	    
	    //페이징 링크를 생성하여 view 에서 사용할 변수에 할당.
	    $data['pagination'] = $this -> pagination -> create_links();
	    
	    //게시물 목록을 불러오기 위한 offset, limit 값 가져오기
	    $per_page = $this -> input ->get("per_page", TRUE) == "" ? 1 : $this -> input ->get("per_page", TRUE);
	    $data['per_page'] = $per_page;
	    if( $per_page > 1 ){
	        $start = (($per_page/$config['per_page'])) * $config['per_page'];
	    }else{
	        $start = ($per_page - 1) * $config['per_page'];
	    }
	    $data['page'] = floor($per_page/$this->col_per_page)+1;
	    $limit = $config['per_page'];
	    
	    //인증 리스트 받아서 메뉴 이름 가져오기
		$auth_list = $this -> authSet_m -> auth_list('', $start, $limit, $this -> stx, $this -> sfl);
		
		//var_dump($auth_list);
	    $i = 0;
		$data['list'] = null;
	    if( is_countable($auth_list) && count($auth_list) > 0 ){
    	    foreach ($auth_list as $list){
    	        $au_menu = array();
    	        $mn_idx_arr = explode(",", $list->mn_idxs);
	
                for( $j=0;$j<count($mn_idx_arr);$j++){
					$mn_idx = $mn_idx_arr[$j];					
					$menu = $this->menu_m->get_menu_idx($mn_idx);
					if( $menu != null ){
						$au_menu[$j] = $menu->mn_name."[{$menu->mn_type}{$menu->mn_code}]";
					}
                }
        
				//var_dump($data['list'][$i]);
                $data['list'][$i] = $list;
                $data['list'][$i]->mn_name_list = implode("<br>", $au_menu);
    	        $i++;
			}
		}
		
		//스킨경로
		//$this->_view($this->config->item("admin_dir")."/authSet/list_v", $data);
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/authSet/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}
	
	//삭제
	function delete(){
	    if( $_POST ){
	        $au_idx = $this -> input -> post( 'au_idx', TRUE );
	        $result = $this -> authSet_m -> auth_delete_idx($au_idx);
	        
	        if( $result ){
	             alert("삭제하였습니다.", $this->list_href);
	        }else{
	             alert("삭제실패 하였습니다.", $this->list_href);
	        }
	    }
	}
}

/* End of file AuthSet.php */
/* Location: /application/controllers/backS1te/AuthSet.php */

?>