<?php
/**
 * 클래스 한글설명
 *
 * Created on 2018. 4. 30.
 * @package      application
 * @subpackage   controllers
 * @category     backS1te
 * @author       leeminji
 * @link         <a href="http://codeigniter-kr.org" target="_blank">http://codeigniter-kr.org</a>
 * @version      0.1
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Contents extends Adm_Controller {

	public $col_per_page = 10;
	public $query = ""; //쿼리스트링
	public $stx = ""; //검색어
	public $sfl = ""; // 검색필드
	public $sst = ""; //정렬필드
	public $sod = ""; //정렬순
	public $per_page = ""; //페이지
	public $total = ""; //전체 리스트
	public $option = "";
	
	public $write_href = "";
	public $list_href = "";

	function __construct(){
		parent::__construct();
		
		$this-> sfl = $this -> input ->get("sfl", FALSE);
		$this-> stx = $this -> input ->get("stx", FALSE);
		$this -> sst = $this->input->get("sst", FALSE);
		$this -> sod = $this->input->get("sod", FALSE);
		$this-> per_page = $this -> input -> get("per_page", FALSE);
		$this -> query = "?per_page=".$this -> per_page."&stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;

		$this -> load -> helper( array('alert', 'editor'));
		$this -> load -> model("config/contents_m");
		
		$this -> write_href = "/backS1te/contents/write";
		$this -> list_href = "/backS1te/contents/lists";
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> lists();
	}
	
	/* 수정 */
	public function update(){
		$cn_idx = $this -> uri -> segment(4);
		$data['errors'] = array();
		$data['view'] = null;
		
		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('cn_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('cn_subject', '제목', 'required');
		
			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'cn_idx' => $this -> input -> post('cn_idx', TRUE),
					'cn_subject' => $this -> input -> post('cn_subject', TRUE),
					'cn_content' => $this -> input -> post('cn_content', FALSE),
					'cn_is_view' => $this -> input -> post('cn_is_view', TRUE) == "1" ? 1 : 0,
					'cn_sitelink' => $this -> input -> post('cn_sitelink', TRUE)
				);

				$result = $this -> contents_m -> contents_update($write_data);
				if( $result ){
					//성공
					array_push($data['errors'], $this->lang->line("error_save_success"));
				}else{
					//실패
					array_push($data['errors'], $this->lang->line("error_save_failed"));
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		$data['q'] = $this -> query;
		$data['view'] = $this -> contents_m -> contents_view($cn_idx);
		if( $data['view'] == null ){
			alert("잘못된 접근입니다.", $this->list_href);
		}

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/contents/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);		
	}

	/* 입력 */
	public function write(){
		$this -> load -> helper('form');
		$data['errors'] = array();
		$data['view'] = null;
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		
		if( $_POST ){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('cn_subject', '제목', 'required');
			$this -> form_validation -> set_rules('cn_content', '내용', '');

			if( $this -> form_validation -> run() == TRUE ){
				//글작성
				$write_data = array(
					'cn_subject' => $this -> input -> post('cn_subject', TRUE),
					'cn_content' => $this -> input -> post('cn_content', TRUE),
					'cn_is_view' => $this -> input -> post('cn_is_view', TRUE) == "1" ? 1 : 0,
					'cn_sitelink' => $this -> input -> post('cn_sitelink', TRUE)
				);
				$result = $this -> contents_m -> contents_insert($write_data);
				if( $result ){
					alert($this->lang->line("msg_save_success"), $this -> list_href );
				}else{
					alert($this->lang->line("msg_save_failed"), $this -> list_href );
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}

		$data['q'] = $this -> query;
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/contents/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	

	}
	
	/* 리스트 */
	public function lists(){
	    $config['total_rows'] = $this -> contents_m -> contents_list('count', '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod );
		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링
		$data['q'] = "?stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		
		//페이지네이션 설정
		$config['base_url'] = '/backs1te/contents/'.$data['q'];

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
		$data['list'] = $this -> contents_m -> contents_list( '', $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod  );
		
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this->col_per_page )-$i;
			//검색시 마크설정
			if( $this -> stx && $this -> sfl == 'cn_subject' ){
			$data['list'][$i]->cn_subject = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->cn_subject );
			}
		}
	
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/contents/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}
  
	/* 컨텐츠 삭제 */
	public function delete(){
		$cn_idx = $this -> input -> post('cn_idx', TRUE);
		if( $_POST ){
    		$view = $this-> contents_m -> contents_view($cn_idx);
    		if( $view != null ){
    		    delete_editor_image( $view->cn_content );
    		    $result = $this -> contents_m -> contents_delete($cn_idx);
				alert($this->lang->line("msg_delete_success"), $this -> list_href );
		    }else{
		        alert($this->lang->line("msg_delete_failed"), $this -> list_href );
		    }
		}
	}
	
	/* 팝업 보기 */
	public function view(){
		$cn_idx = $this -> uri -> segment(4);
		echo json_encode(array("data" => $this -> contents_m -> contents_view($cn_idx) ));
	}

}

/* End of file Contents.php */
/* Location: /application/controllers/backS1te/Contents.php */
?>