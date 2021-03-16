<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Adm_Controller {

	public $col_per_page = 10;
	public $query = ""; //쿼리스트링
	public $stx = ""; //검색어
	public $sfl = ""; // 검색필드
	public $sst = ""; //정렬필드
	public $sod = ""; //정렬순
	public $per_page = ""; //페이지
	public $mb_level = ""; //레벨별
	public $total = ""; //전체 리스트
	
	public $list_href = ""; //목록
	public $write_href = ""; //글쓰기
	
	function __construct(){
		parent::__construct();
		
		$this -> mb_level = $this -> input ->get('mb_level');
		$this-> sfl = $this -> input ->get("sfl", FALSE);
		$this-> stx = $this -> input ->get("stx", FALSE);
		$this -> sst = $this->input->get("sst", FALSE);
		$this -> sod = $this->input->get("sod", FALSE);
		$this-> per_page = $this -> input -> get("per_page", FALSE);
		$this -> query = "?per_page=".$this -> per_page."&stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod."&mb_level=".$this->mb_level;

		$this -> load -> model('member/member_m');
		$this -> load -> helper( array('alert','auth','url'));
		$this -> load -> model("member/level_m");
		$this -> load -> model("config/authset_m");
		
		$this -> list_href = "/backS1te/member/lists";
		$this-> write_href = "/backS1te/member/write";
	
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> lists();
	}

	/* 회원 수정 */
	public function update(){
		//회원번호
		$mb_idx = $this -> uri -> segment(4);
		$data['errors'] = array();
		
		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('mb_email', '메일', 'valid_email');
			$this -> form_validation -> set_rules('mb_level', '회원권한', 'required|numeric');
			$this -> form_validation -> set_rules('mb_phone', '전화번호', 'numeric|matches[mb_phone]');
			$this -> form_validation -> set_rules('mb_passwd', '비밀번호', '');
			
			$hash = null;
			if( $this -> input -> post('mb_passwd') != '' ){
				 $hash = password_hash($this -> input -> post('mb_passwd'), PASSWORD_BCRYPT);
			}
			
			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'mb_email' => $this -> input -> post('mb_email', TRUE),
					'mb_is_email' => $this -> input -> post('mb_is_email', TRUE),
					'mb_level' => $this -> input -> post('mb_level', TRUE),
					'mb_phone' => $this -> input -> post('mb_phone', TRUE),
					'mb_is_phone' => $this -> input -> post('mb_is_phone', TRUE),
					'mb_idx' => $mb_idx,
				    'mb_passwd' => $hash
				);

				$result = $this -> member_m -> member_update($write_data);
				if( $result ){
				    array_push($data['errors'], "저장에 성공하였습니다.");
				}else{
				    array_push($data['errors'], "저장에 실패하였습니다.");
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		
		$data['q'] = $this -> query;
		$data['view'] = $this -> member_m -> member_view($mb_idx);
		$data['level'] = $this -> level_m -> get_level_list();

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/member/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}

	/* 회원 입력 */
	public function write(){
	    $data['errors'] = array();
	    $data['view'] = null;
		$this -> load -> helper('form');
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		
		if( $_POST ){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('mb_id', '아이디', 'required|alpha_numeric|matches[mb_id]');
			$this -> form_validation -> set_rules('mb_name', '이름', 'required');
			$this -> form_validation -> set_rules('mb_passwd', '비밀번호', 'required');
			$this -> form_validation -> set_rules('mb_re_passwd', '비밀번호 확인', 'required|matches[mb_passwd]');
			$this -> form_validation -> set_rules('mb_email', '메일', 'valid_email|is_unique[member.mb_email]');
			$this -> form_validation -> set_rules('mb_level', '회원권한', 'required|numeric');
			$this -> form_validation -> set_rules('mb_phone', '전화번호', 'numeric|matches[mb_phone]');
			$this -> form_validation -> set_rules('mb_id_chk', '아이디중복체크', 'required');

			if( $this -> form_validation -> run() == TRUE ){
				$hash = password_hash($this -> input -> post('mb_passwd'), PASSWORD_BCRYPT);
				$write_data = array(
					'mb_name' => $this -> input -> post('mb_name', TRUE),
					'mb_id' => $this -> input -> post('mb_id', TRUE),
				    'mb_passwd' => $hash,
					'mb_email' => $this -> input -> post('mb_email', TRUE),
					'mb_is_email' => $this -> input -> post('mb_is_email', TRUE),
					'mb_level' => $this -> input -> post('mb_level', TRUE),
					'mb_phone' => $this -> input -> post('mb_phone', TRUE),
					'mb_is_phone' => $this -> input -> post('mb_is_phone', TRUE) 
				);

				$result = $this -> member_m -> member_insert($write_data);
				if( $result ){
				    alert("저장하였습니다.", $this->list_href);
				}else{
				    alert("저장에 실패하였습니다.", $this->list_href);
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		
		//회원권한 레벨리스트
		$data['level'] = $this -> level_m -> get_level_list();
		$data['q'] = $this -> query;
		
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/member/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}
	
	/* 회원 권한 관리 */
	public function json_level(){
	    $this -> load -> helper('form');
	    $data['errors'] = array();
	    
	    //폼 검증 라이브러리 로드
	    $this -> load -> library('form_validation');
	    
	    if( $_POST ){
	    
            $write_data = array(
                'ml_idx' => $this -> input -> post('ml_idx', TRUE),
                'ml_name' => $this -> input -> post('ml_name', TRUE)
            );
            
            $result = $this -> level_m -> level_update($write_data);
            if( $result ){
                echo json_encode(array("data"=> "수정하였습니다."));
            }else{
                echo json_encode(array("data"=> "수정실패하였습니다."));
            }
	    }else{
	        
	        //회원권한 리스트
	        $data['level'] = $this -> level_m -> get_level_list();
	        
	        //뷰
	        $this -> load -> view('admin/member/level_v', $data);
	    }
	}


	/* 회원 리스트 */
	public function lists(){

		//회원레벨리스트
		$data['level'] = $this -> level_m -> level_list();
		
		//총회원 수
		$data['count_total_member'] = $this -> member_m -> member_list('count', '', '');
		
		//탈퇴회원수
		$data['count_out_member'] = $this -> member_m -> member_list('count', '', '','0','mb_state');

		$config['total_rows'] = $this -> member_m -> member_list('count', '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod, $this -> mb_level );
		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링
		$data['q'] = "?stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		$data['mb_level'] = $this -> mb_level ;
		
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
		$per_page = $this -> input ->get("per_page", TRUE) == "" ? 1 : $this -> input ->get("per_page", TRUE);
		$data['per_page'] = $per_page;
		if( $per_page > 1 ){
			$start = (($per_page/$config['per_page'])) * $config['per_page'];
		}else{
			$start = ($per_page - 1) * $config['per_page'];
		}
		$data['page'] = floor($per_page/$this->col_per_page)+1;

		$limit = $config['per_page'];
		$data['list'] = $this -> member_m -> member_list( '', $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod , $this -> mb_level );
		
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this->col_per_page )-$i;
			
			//검색시 마크설정
			if( $this -> stx && $this -> sfl == 'mb_id' ){
			$data['list'][$i]->mb_id = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->mb_id );
			}

			if( $this -> stx && $this -> sfl == 'mb_name' ){
			$data['list'][$i]->mb_name = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->mb_name );
			}
		}
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/member/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);	
	}

	/* 회원 삭제 */
	public function delete(){
		if( $_POST ){
			$mb_idx = $this -> input -> post( 'mb_idx', TRUE );
			$mb_id = $this -> input -> post('mb_id', TRUE);
			
			//회원정보삭제
			$this -> member_m -> member_delete($mb_idx);
			
			//회원권한삭제
			$this -> authset_m -> auth_delete($mb_id);
			
			alert("삭제하였습니다.", $this->list_href);
		}
	}

	/* 회원 탈퇴처리 */
	public function state(){
		if( $_POST ){
		    $mb_idx = $this -> input -> post('mb_idx', TRUE);
		    $mb_state = $this -> input -> post('mb_state', TRUE);
			$upate_data = array(
			    'mb_idx' => $mb_idx,
			    'mb_state' => $mb_state
			);
			$result = $this -> member_m -> member_state($upate_data);
			if( $result ){
			    if( $mb_state == 0 ){
			        alert("탈퇴처리되었습니다.", "/backS1te/member/update/".$mb_idx );
			    }else{
			        alert("재가입 처리되었습니다.", "/backS1te/member/update/".$mb_idx );
			    }
			}else{
			    alert("실패하였습니다.", "/backS1te/member/update/".$mb_idx );
			}
		}
	}

}
?>