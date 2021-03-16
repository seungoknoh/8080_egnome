<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popup extends Adm_Controller {

	public $col_per_page = 10;
	public $query = ""; //쿼리스트링
	public $stx = ""; //검색어
	public $sfl = ""; // 검색필드
	public $sst = ""; //정렬필드
	public $sod = ""; //정렬순
	public $per_page = ""; //페이지
	public $total = ""; //전체 리스트
	public $option = "";
	
	public $list_href = "";
	public $write_href = "";

	function __construct(){
		parent::__construct();
		
		$this-> sfl = $this -> input ->get("sfl", FALSE);
		$this-> stx = $this -> input ->get("stx", FALSE);
		$this -> sst = $this->input->get("sst", FALSE);
		$this -> sod = $this->input->get("sod", FALSE);
		$this-> per_page = $this -> input -> get("per_page", FALSE);
		$this -> query = "?per_page=".$this -> per_page."&stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;

		$this -> load -> helper( array('alert', 'editor'));
		$this -> load -> model("config/popup_m");
		
		$this -> list_href = "/backS1te/popup/lists";
		$this -> write_href = "/backS1te/popup/write";
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> lists();
	}

	/* 수정 */
	public function update(){
	    $data['view'] = null;
	    $data['errors'] = array();
		$po_idx = $this -> uri -> segment(4);
		
		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('po_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('po_subject', '제목', 'required');
		
			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'po_idx' => $this -> input -> post('po_idx', TRUE),
					'po_subject' => $this -> input -> post('po_subject', TRUE),
					'po_content' => $this -> input -> post('po_content', FALSE),
					'po_begin_time' => $this -> input -> post('po_begin_time', TRUE),
					'po_end_time' => $this -> input -> post('po_end_time', TRUE),
					'po_hour' => $this -> input -> post('po_hour', TRUE),
					'po_left' => $this -> input -> post('po_left', TRUE),
					'po_top' => $this -> input -> post('po_top', TRUE),
					'po_width' => $this -> input -> post('po_width', TRUE),
					'po_height' => $this -> input -> post('po_height', TRUE),
					'po_is_view' => $this -> input -> post('po_is_view', TRUE) == "1" ? 1 : 0,
					'po_color' => $this -> input -> post('po_color', TRUE),
					'po_is_target' => $this -> input -> post('po_is_target', TRUE) == "1" ? 1 : 0,
					'po_link' => $this -> input -> post('po_link', TRUE)
				);

				$result = $this -> popup_m -> popup_update($write_data);
				if( $result ){
				    array_push($data['errors'], "수정하였습니다.");
					
				}else{
				    array_push($data['errors'], "수정 실패하였습니다.");
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		
		$data['q'] = $this -> query;
		$data['view'] = $this -> popup_m -> popup_view($po_idx);

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/popup/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}

	/* 팝업 입력 */
	public function write(){
	    $data['view'] = null;
	    $data['errors'] = array();
		$this -> load -> helper('form');
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		
		if( $_POST ){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('po_subject', '제목', 'required');
			$this -> form_validation -> set_rules('po_content', '내용', '');
			$this -> form_validation -> set_rules('po_begin_time', '시작시간', 'required');
			$this -> form_validation -> set_rules('po_end_time', '종료시간', 'required');

			if( $this -> form_validation -> run() == TRUE ){
				//글작성
				$write_data = array(
					'po_begin_time' => $this -> input -> post('po_begin_time', TRUE),
					'po_end_time' => $this -> input -> post('po_end_time', TRUE),
					'po_hour' => $this -> input -> post('po_hour', TRUE),
					'po_left' => $this -> input -> post('po_left', TRUE),
					'po_top' => $this -> input -> post('po_top', TRUE),
					'po_width' => $this -> input -> post('po_width', TRUE),
					'po_height' => $this -> input -> post('po_height', TRUE),
					'po_subject' => $this -> input -> post('po_subject', TRUE),
					'po_content' => $this -> input -> post('po_content', TRUE),
					'po_color' => $this -> input -> post('po_color', TRUE),
					'po_is_view' => $this -> input -> post('po_is_view', TRUE) == "1" ? 1 : 0,
					'po_is_target' => $this -> input -> post('po_is_target', TRUE) == "1" ? 1 : 0,
					'po_link' => $this -> input -> post('po_link', TRUE)
				);

				$result = $this -> popup_m -> popup_insert($write_data);
				if( $result ){
					alert("저장하였습니다.", $this->list_href);
				}else{
				    alert("저장 실패 하였습니다.", $this->list_href);
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		
		$data['q'] = $this -> query;
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/popup/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}
	
	/* 리스트 */
	public function lists(){
		$config['total_rows'] = $this -> popup_m -> popup_list('count', '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod );

		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링
		$data['q'] = "?stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		
		//페이지네이션 설정
		$config['base_url'] = '/backs1te/popup/'.$data['q'];

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
		$per_page = $this -> input ->get("per_page", TRUE) == "" ? 1 : $this -> input ->get("per_page", TRUE);
		$data['per_page'] = $per_page;
		if( $per_page > 1 ){
			$start = (($per_page/$config['per_page'])) * $config['per_page'];
		}else{
			$start = ($per_page - 1) * $config['per_page'];
		}
		$data['page'] = floor($per_page/$this->col_per_page)+1;

		$limit = $config['per_page'];
		$data['list'] = $this -> popup_m -> popup_list( '', $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod  );
		
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this->col_per_page )-$i;
			//검색시 마크설정
			if( $this -> stx && $this -> sfl == 'mb_id' ){
			$data['list'][$i]->po_subject = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->po_subject );
			}
		}
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/popup/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}

	/* 팝업 삭제 */
	public function delete(){
		$po_idx = $this -> input -> post('po_idx', TRUE);
		$delete_data = array( "po_idx"=> $po_idx );
		if( $_POST ){
			$result = $this -> popup_m -> popup_delete($delete_data);
			if( $result ){
			    alert("삭제하였습니다.", $this->list_href);
			}else{
			    alert("삭제 실패 하였습니다.", $this->list_href);
			}
		}
	}
	
	/* 팝업 보기 */
	public function json_view(){
		$po_idx = $this -> uri -> segment(4);
		echo json_encode(array("data" => $this -> popup_m -> popup_view($po_idx) ));
	}

}
?>