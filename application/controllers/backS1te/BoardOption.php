<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BoardOption extends Adm_Controller {

	public $col_per_page = 10;
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
		
		$this -> load -> model('board/board_m');
		$this -> load -> model('board/file_m');
		$this -> load -> model('board/board_option_m');
		$this -> load -> model('board/board_category_m');
		$this -> load -> model('member/level_m');
		$this -> load -> helper( array('alert','editor','thumbnail'));
		
		$this -> list_href = "/backS1te/boardOption/lists";
	}
	
	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
	    $this -> lists();
	}
	
	/* 수정 */
	public function update(){
	    $data['errors'] = array();
	    
		//번호
		$op_idx = $this -> uri -> segment(4);
		
		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('op_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('op_name', '게시판이름', 'required');
			$this -> form_validation -> set_rules('op_skin', '스킨사용자', 'required');
			$this -> form_validation -> set_rules('op_adm_skin', '스킨관리자', 'required');

			$this -> form_validation -> set_rules('op_level_write', '쓰기권한', 'required|numeric');
			$this -> form_validation -> set_rules('op_level_reply', '답변권한', 'required|numeric');
			// $this -> form_validation -> set_rules('op_level_comment', '댓글권한', 'required|numeric');
			$this -> form_validation -> set_rules('op_level_view', '보기권한', 'required|numeric');
			$this -> form_validation -> set_rules('op_level_list', '목록권한', 'required|numeric');

			$this -> form_validation -> set_rules('op_thumb_width', '썸네일넓이', 'numeric');
			$this -> form_validation -> set_rules('op_thumb_height', '썸네일높이', 'numeric');

			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'op_idx' => $this -> input -> post('op_idx', TRUE),
					'op_name' => $this -> input -> post('op_name', TRUE),
					'op_skin' => $this -> input -> post('op_skin', TRUE),
					'op_adm_skin' => $this -> input -> post('op_adm_skin', TRUE),
					'op_is_view_category' => $this -> input -> post('op_is_view_category', TRUE),
					'op_is_view_caption' => $this -> input -> post('op_is_view_caption', TRUE),
					'op_is_category' => $this -> input -> post('op_is_category', TRUE),
					'op_is_secret' => $this -> input -> post('op_is_secret', TRUE),
					'op_is_ip' => $this -> input -> post('op_is_ip', TRUE),
					'op_is_sign' => $this -> input -> post('op_is_sign', TRUE),
					'op_is_preview' => $this -> input -> post('op_is_preview', TRUE),
					'op_is_file' => $this -> input -> post('op_is_file', TRUE) != null ? 1 : 0,
					'op_new_date' => $this -> input -> post('op_new_date', TRUE) ,
					'op_page_rows' => $this -> input -> post('op_page_rows', TRUE) ,
					'op_level_list' => $this -> input -> post('op_level_list', TRUE),
					'op_level_view' => $this -> input -> post('op_level_view', TRUE),
					'op_level_comment' => $this -> input -> post('op_level_comment', TRUE) != null ? 1 : 0,
					'op_level_reply' => $this -> input -> post('op_level_reply', TRUE),
					'op_level_write' => $this -> input -> post('op_level_write', TRUE),
					'op_thumb_width' => $this -> input -> post('op_thumb_width', TRUE),
					'op_thumb_height' => $this -> input -> post('op_thumb_height', TRUE) ,
					'op_is_gallery' => $this -> input -> post('op_is_gallery', TRUE) ,
					'op_img_max_width' => $this -> input -> post('op_img_max_width', TRUE) 
				);

				$this -> board_category_m -> delete(array(
				    'op_table' => $this -> input -> post('op_table', TRUE),
				    'bc_delete' => $this -> input -> post('bc_delete', TRUE)
				));				
				$this -> board_category_m -> insert(array(
				    'op_table' => $this -> input -> post('op_table', TRUE),
				    'bc_name' => $this -> input -> post('bc_name', TRUE) 
				));
				$this -> board_category_m -> update(array(
				    'op_table' => $this -> input -> post('op_table', TRUE),
				    'bc_idx' => $this -> input -> post('bc_idx', TRUE),
				    'bc_update_name' => $this -> input -> post('bc_update_name', TRUE)
				));
				
				$result = $this -> board_option_m -> board_option_update($write_data);
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

		//스킨목록
		$data['skin_list'] = $this -> board_option_m -> board_skin_list();
		$data['adm_skin_list'] = $this -> board_option_m -> board_skin_list("admin");
		$data['q'] = $this -> query;
		$data['view'] = $this -> board_option_m -> board_option_view($op_idx);
		$data['level'] = $this -> level_m -> get_level_list();
		$data['category_list'] = $this -> board_category_m -> list($data['view']->op_table);
		
	
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/boardOption/write_v";
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
			$this -> form_validation -> set_rules('op_table_chk', '테이블명중복확인', 'required');
			$this -> form_validation -> set_rules('op_table', '테이블명', 'required|alpha_numeric');
			$this -> form_validation -> set_rules('op_name', '게시판이름', 'required');
			$this -> form_validation -> set_rules('op_skin', '스킨사용자', 'required');
			$this -> form_validation -> set_rules('op_adm_skin', '스킨관리자', 'required');

			$this -> form_validation -> set_rules('op_level_write', '쓰기권한', 'required|numeric');
			$this -> form_validation -> set_rules('op_level_reply', '답변권한', 'required|numeric');
			// $this -> form_validation -> set_rules('op_level_comment', '댓글권한', 'required|numeric');
			$this -> form_validation -> set_rules('op_level_view', '보기권한', 'required|numeric');
			$this -> form_validation -> set_rules('op_level_list', '목록권한', 'required|numeric');

			$this -> form_validation -> set_rules('op_thumb_width', '썸네일넓이', 'numeric');
			$this -> form_validation -> set_rules('op_thumb_height', '썸네일높이', 'numeric');

			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'op_table' => $this -> input -> post('op_table', TRUE),
					'op_name' => $this -> input -> post('op_name', TRUE),
					'op_skin' => $this -> input -> post('op_skin', TRUE),
					'op_adm_skin' => $this -> input -> post('op_adm_skin', TRUE),
					'op_is_category' => $this -> input -> post('op_is_category', TRUE),
					'op_is_view_category' => $this -> input -> post('op_is_view_category', TRUE),
					'op_is_view_caption' => $this -> input -> post('op_is_view_caption', TRUE),
					'op_is_secret' => $this -> input -> post('op_is_secret', TRUE),
					'op_is_ip' => $this -> input -> post('op_is_ip', TRUE),
					'op_is_sign' => $this -> input -> post('op_is_sign', TRUE),
					'op_is_preview' => $this -> input -> post('op_is_preview', TRUE),
					'op_is_file' => $this -> input -> post('op_is_file', TRUE) != null ? 1 : 0,
					'op_new_date' => $this -> input -> post('op_new_date', TRUE) ,
					'op_page_rows' => $this -> input -> post('op_page_rows', TRUE) ,
					'op_level_list' => $this -> input -> post('op_level_list', TRUE),
					'op_level_view' => $this -> input -> post('op_level_view', TRUE),
					'op_level_comment' => $this -> input -> post('op_level_comment', TRUE) != null ? 1 : 0,
					'op_level_reply' => $this -> input -> post('op_level_reply', TRUE),
					'op_level_write' => $this -> input -> post('op_level_write', TRUE),
					'op_thumb_width' => $this -> input -> post('op_thumb_width', TRUE),
					'op_thumb_height' => $this -> input -> post('op_thumb_height', TRUE) ,
					'op_is_gallery' => $this -> input -> post('op_is_gallery', TRUE) ,
					'op_img_max_width' => $this -> input -> post('op_img_max_width', TRUE) 
				);

				$result = $this -> board_option_m -> board_option_insert($write_data);
				
				$this -> board_category_m -> insert(array(
				    'op_table' => $this -> input -> post('op_table', TRUE),
				    'bc_name' => $this -> input -> post('bc_name', TRUE)
				));
				
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
		
		//스킨목록
		$data['skin_list'] = $this -> board_option_m -> board_skin_list();
		$data['adm_skin_list'] = $this -> board_option_m -> board_skin_list("admin");
		$data['level'] = $this -> level_m -> get_level_list();
		$data['q'] = $this -> query;
		$data['category_list'] = [];

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/boardOption/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}
	
	/* 리스트 */
	public function lists(){

		//총게시물 수
		$data['count_total_member'] = $this -> board_option_m -> board_option_list('count', '', '');
		$config['total_rows'] = $this -> board_option_m -> board_option_list('count', '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod );
		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링
		$data['q'] = "?stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		
		//페이지네이션 설정
		$config['base_url'] = '/backs1te/member/'.$data['q'];

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
		$data['list'] = $this -> board_option_m -> board_option_list( '', $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod );
		
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
		$data['page'] = $this->config->item("admin_dir")."/boardOption/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}

	/* 게시물 전체 삭제 */
	public function delete(){
		if( $_POST ){
		    $op_table = $this -> input -> post( 'op_table', TRUE );
		    
		    //카테고리삭제
		    $result = $this -> board_category_m -> delete_all($op_table); 
		    //게시물삭제
		    $result = $this -> board_m -> board_all_delete($op_table);
			//게시물관리 삭제
		    $result = $this -> board_option_m -> board_option_delete($op_table);

			if( $result ){
			    //게시물 파일 삭제
			    $this -> file_m -> file_all_delete($op_table);
			    alert($this->lang->line("msg_delete_success"), $this->list_href );
			}else{
			    alert($this->lang->line("msg_delete_failed"), $this->list_href );
			}
		}
	}

	/* 테이블명 중복확인 */
	public function check(){
	    $op_table = $this -> input -> get('op_table');
	    $result = $this -> board_option_m -> board_option_chk($op_table);
	    echo $result->cnt == 1 ? "" : "TRUE" ;
	}
}
?>