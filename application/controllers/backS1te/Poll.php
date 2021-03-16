<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poll extends Adm_Controller {

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
		$this -> load -> model("config/poll_m");
		
		$this -> list_href = "/backS1te/poll/lists";
		$this -> write_href = "/backS1te/poll/write";
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> lists();
	}

	/* 수정 */
	public function update(){
	    $data['view'] = null;
	    $data['errors'] = array();
		
		$pl_idx = $this -> uri -> segment(4);
		
		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('pl_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('pl_subject', '제목', 'required');
			
			//배열로 받아서 저장
			$pl_question = "";
			if( $_POST['pl_question'] && count($_POST['pl_question']) > 0){
				$pl_question = implode($_POST['pl_question'], ";");
			}

			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'pl_idx' => $this -> input -> post('pl_idx', TRUE),
					'pl_subject' => $this -> input -> post('pl_subject', TRUE),
					'pl_question' => $pl_question,
					'pl_content' => $this -> input -> post('pl_content', TRUE),
					'pl_is_view' => $this -> input -> post('pl_is_view', TRUE) == null ? 0 : 1
				);

				$result = $this -> poll_m -> poll_update($write_data);
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
		$data['view'] = $this -> poll_m -> poll_view($pl_idx);
		
		//설문리스트
		$question_list = explode(";", $data['view']-> pl_question);
		
		//설문결과
		$poll_result = $this -> poll_m -> poll_view_result($pl_idx);

		//설문투표수
		$poll_total =array_sum(array_column($poll_result, "total"));
		$data['poll_total'] = $poll_total;

		$question_result = array();
		$i = 0;
		foreach($question_list as $question){
			if( trim($question) != "" ){
				//인덱스값찾기
				$result_index = array_search($i,array_column($poll_result, "pr_check"));
				$item = new class{};
				$item -> num = $i;
				$total = 0;
				if( $result_index > -1 ){
					$total = $poll_result[$result_index] -> total;
				}
				$item -> total = $total;
				$item -> percent = $total == 0 ? 0 : ( $total / $poll_total ) * 100;
				$item -> question = $question;
				array_push($question_result, $item);
				$i++;
			}
		}
		$data['question_list'] = $question_list;
		$data['question_result'] = $question_result;

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/poll/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}

	/* 입력 */
	public function write(){
	    $data['view'] = null;
	    $data['errors'] = array();
		$data['question_list'] = array();
		$this -> load -> helper('form');
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		
		if( $_POST ){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('pl_subject', '제목', 'required');

			$pl_question = "";
			if( $_POST['pl_question'] && count($_POST['pl_question']) > 0){
				$pl_question = implode($_POST['pl_question'], ";");
			}

			if( $this -> form_validation -> run() == TRUE ){
				//글작성
				$write_data = array(
					'pl_subject' => $this -> input -> post('pl_subject', TRUE),
					'pl_content' => $this -> input -> post('pl_content', TRUE),
					'pl_question' => $pl_question,
					'pl_is_view' => $this -> input -> post('pl_is_view', TRUE) == null ? 0 : 1,
					'pl_writer' => $this -> session -> userdata('mb_name'),
				);

				$result = $this -> poll_m -> poll_insert($write_data);
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
		$data['page'] = $this->config->item("admin_dir")."/poll/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}
	
	/* 리스트  */
	public function lists(){
		$config['total_rows'] = $this -> poll_m -> poll_list('count', '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod );
		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링
		$data['q'] = "?stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		
		//페이지네이션 설정
		$config['base_url'] = '/backs1te/poll/'.$data['q'];

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
		$data['list'] = $this -> poll_m -> poll_list( '', $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod  );
		
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this->col_per_page )-$i;
			//검색시 마크설정
			if( $this -> stx && $this -> sfl == 'mb_id' ){
			$data['list'][$i]->pl_subject = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->pl_subject );
			}
			//설문수
			$question_count=0;
			$question_list = explode(";", $data['list'][$i]->pl_question);
			foreach($question_list as $question){
				if(trim($question) && trim($question) != "") $question_count++;	
			}

			$data['list'][$i]->pl_question_count = $question_count;
		}
	
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/poll/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}

	/* 설문 삭제 */
	public function delete(){
		$pl_idx = $this -> input -> post('pl_idx', TRUE);
		$delete_data = array( "pl_idx"=> $pl_idx );
		if( $_POST ){
			$result = $this -> poll_m -> poll_delete($delete_data);
			if( $result ){
			    alert("삭제하였습니다.", $this->list_href);
			}else{
			    alert("삭제 실패 하였습니다.", $this->list_href);
			}
		}
	}
	
	/* 설문 보기 */
	public function json_view(){
		$pl_idx = $this -> uri -> segment(4);
		echo json_encode(array("data" => $this -> poll_m -> poll_view($pl_idx) ));
	}

	/* 설문 결과*/
	public function result(){
		$this -> load -> helper('form');
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('pr_check', '선택번호', 'required');
			$this -> form_validation -> set_rules('pl_idx', '설문 idx', 'required');
			if( $this -> form_validation -> run() == TRUE ){
				//글작성
				$write_data = array(
					'pl_idx' => $this -> input -> post('pl_idx', TRUE),
					'pr_check' => $this -> input -> post('pr_check', TRUE),
					'mb_id' =>  $this -> session -> userdata('mb_id'),
				);
				$poll_result = $this -> poll_m -> poll_result_view($write_data);
				if($poll_result != null){
					$data['msg'] = "이미 참여하였습니다.";
					
				}else{
					$result = $this -> poll_m -> poll_result_insert($write_data);
					if($result){
						$data['msg'] = "설문에 성공적으로 참여하셨습니다.";
					}else{
						$data['msg'] = "실패하였습니다.";
					}
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
			echo json_encode($data);
		}
	}
}
?>