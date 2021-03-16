<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BoardSearchField extends Adm_Controller {

	public $col_per_page = 20;
	public $query = ""; //쿼리스트링
	public $stx = ""; //검색어
	public $sfl = ""; // 검색필드
	public $sst = ""; //정렬필드
	public $sod = ""; //정렬순
	public $per_page = ""; //페이지
	
	public $total = ""; //전체 리스트
    public $list_href = ""; //목록 경로
    
	function __construct(){
		parent::__construct();
		$this-> sfl = $this -> input ->get("sfl", FALSE);
		$this-> stx = $this -> input ->get("stx", FALSE);
		$this -> sst = $this->input->get("sst", FALSE);
		$this -> sod = $this->input->get("sod", FALSE);
		$this-> per_page = $this -> input -> get("per_page", FALSE);
		$this -> query = "?per_page=".$this -> per_page."&stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		
		$this -> load -> model('board/board_option_m');
		$this -> load -> model('board/Board_SearchField_m');
		$this -> load -> helper( array('alert','editor','thumbnail'));
		
		$this -> list_href = "/backS1te/boardSearchField/lists";
	}
	
	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
	    $this -> lists();
	}
	
	/* 수정 */
	public function update(){
	    $data['errors'] = array();
	    
		//번호
		$bs_idx = $this -> uri -> segment(4);	
		
		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('bs_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('op_table', '게시판 이름', 'required');
			 $this -> form_validation -> set_rules('bs_type', '검색필드 타입', 'required');
			// $this -> form_validation -> set_rules('bs_code', '검색필드코드', 'required');
			$this -> form_validation -> set_rules('bs_name', '검색필드명', 'required');

			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'bs_idx' => $this -> input -> post('bs_idx', TRUE),
					'op_table' => $this -> input -> post('op_table', TRUE),
					'bs_type' => $this -> input -> post('bs_type', TRUE),
					// 'bs_code' => $this -> input -> post('bs_code', TRUE),
					'bs_name' => $this -> input -> post('bs_name', TRUE)
				);

				$result = $this -> Board_SearchField_m -> board_searchField_update($write_data);
				if( $result ){
				    array_push($data['errors'], "수정되었습니다.");
				}else{
				    array_push($data['errors'], "수정에 실패하였습니다. 다시 시도해주세요.");
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		$data['view'] = $this -> Board_SearchField_m -> board_searchField_view($bs_idx);
		$data['list'] = $this -> board_option_m -> board_option_list('','','','','','','');
		$data['typelist'] = $this -> Board_SearchField_m -> search_type_list($data['view']->op_table);
		log_message("DEBUG", "search_type_list_bs_type - {$data['typelist']->bs_type}");
		$data['page'] = $this->config->item("admin_dir")."/boardSearchField/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);			
	}

	/* 입력 */
	public function write(){
		$this -> load -> helper('form');
		$data['errors'] = null;
		$data['view'] = [];
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		
		if( $_POST ){
			header('X-XSS-Protection:0');

			//폼 검증할 필드와 규칙 사전 정의
			// $this -> form_validation -> set_rules('bs_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('op_table', '게시판명', 'required');
			$this -> form_validation -> set_rules('bs_type', '검색필드타입', 'required');
			// $this -> form_validation -> set_rules('bs_code', '검색필드코드', 'required');
			$this -> form_validation -> set_rules('bs_name', '검색필드값', 'required');

			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					// 'bs_idx' => $this -> input -> post('bs_idx', TRUE),
					'op_table' => $this -> input -> post('op_table', TRUE),
					 'bs_type' => $this -> input -> post('bs_type', TRUE),
					// 'bs_code' => $this -> input -> post('bs_code', TRUE),
					'bs_name' => $this -> input -> post('bs_name', TRUE)
				);

				$result = $this -> Board_SearchField_m -> board_searchField_insert($write_data);
				
				if( $result ){
				    alert($this->lang->line("msg_save_success"), $this->list_href);
				}else{
				    alert($this->lang->line("msg_save_failed"), $this->list_href);
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}

		$data['list'] = $this -> board_option_m -> board_option_list('','','','','','','');
		$data['typelist'] = $this -> Board_SearchField_m -> search_type_list('research');
		//log_message("DEBUG", "search_type_list_bs_type - {$data['typelist']->bs_type}");
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/boardSearchField/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}
	
	/* 리스트 */
	public function lists(){

		//총게시물 수
		$data['count_total_search'] = $this -> Board_SearchField_m -> board_searchField_list('count', '', '');
		$config['total_rows'] = $this -> Board_SearchField_m -> board_searchField_list('count', '','','', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod );
		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링
		$data['q'] = "?stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		
		//페이지네이션 설정
		$config['base_url'] = '/backS1te/boardSearchField/'.$data['q'];

		//페이지당 보여줄 갯수
		$config['per_page'] = $this-> col_per_page ;
		$config['page_query_string'] = TRUE;
		
		//쿼리스트링
		$data['q'] = $this -> query;

		//페이지네이션 초기화
		$this -> pagination -> initialize($config);

		//페이징 링크를 생성하여 view 에서 사용할 변수에 할당.
		$data['pagination'] = $this -> pagination -> create_links();
		
		//게시물 목록을 불러오기 위한 offset, limit 값 가져오기
		$per_page = $this -> input ->get("per_page", TRUE) == "" ? 0 : $this -> input ->get("per_page", TRUE);
		$data['per_page'] = $per_page;
		$start = $per_page;
		$data['page'] = floor($per_page/$this->col_per_page)+1;

		$limit = $config['per_page'];
		$data['list'] = $this -> Board_SearchField_m -> board_searchField_list( '', $op_table='', $bs_type='', $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod );
		
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this->col_per_page )-$i;
			
			//검색시 마크설정
			if( $this -> stx && $this -> sfl == 'op_name' ){
			$data['list'][$i]->op_name = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->op_name );
			}

			if( $this -> stx && $this -> sfl == 'op_table' ){
			$data['list'][$i]->op_table = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->op_table );
			}
		}

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/boardSearchField/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}

	/* 게시물 전체 삭제 */
	public function delete(){
		if( $_POST ){
		    $bs_idx = $this -> input -> post( 'bs_idx', TRUE );
		    
			//게시물관리 삭제
		    $result = $this -> Board_SearchField_m -> board_searchField_delete($bs_idx);

			if( $result ){
			    alert($this->lang->line("msg_delete_success"), $this->list_href );
			}else{
			    alert($this->lang->line("msg_delete_failed"), $this->list_href );
			}
		}
	}

	/* 테이블명 중복확인 */
	public function check(){
	    $bs_name = $this -> input -> get('bs_name');
	    $result = $this -> Board_SearchField_m -> board_searchField_chk($bs_name);
	    echo $result->cnt == 1 ? "" : "TRUE" ;
	}
}
?>