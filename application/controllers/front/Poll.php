<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poll extends FRONT_Controller {

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
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> view();
	}

	/* 설문 보기 */
	public function view(){
		$pl_idx = $this -> uri -> segment(3);
		echo json_encode(array("data" => $this -> poll_m -> poll_view($pl_idx)));
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