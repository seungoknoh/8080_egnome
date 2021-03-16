<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends Adm_Controller {

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

    //@TODO 메일 발송
	function __construct(){
		parent::__construct();
		
		$this-> sfl = $this -> input ->get("sfl", FALSE);
		$this-> stx = $this -> input ->get("stx", FALSE);
		$this -> sst = $this->input->get("sst", FALSE);
		$this -> sod = $this->input->get("sod", FALSE);
		$this-> per_page = $this -> input -> get("per_page", FALSE);
		$this -> query = "?per_page=".$this -> per_page."&stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;

		$this -> load -> helper( array('alert', 'editor'));
		$this -> load -> model("member/email_m");
		$this -> load -> model("member/member_m");
		$this -> load -> model("member/level_m");
		$this -> load -> model("config/config_m");
		
		$this->list_href = "/backS1te/email/lists";
		$this->write_href = "/backS1te/email/write";
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> lists();
	}

	/* 수정 */
	public function update(){
	    $data['view'] = null;
	    $data['errors'] = array();
		$me_idx = $this -> uri -> segment(4);
		
		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('me_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('me_subject', '제목', 'required');
		
			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'me_idx' => $this -> input -> post('me_idx', TRUE),
					'me_subject' => $this -> input -> post('me_subject', TRUE),
					'me_content' => $this -> input -> post('me_content', TRUE)
				);
				$result = $this -> email_m -> mail_update($write_data);
				if( $result ){
				    array_push($data['errors'], "수정하였습니다.");
					
				}else{
				    array_push($data['errors'], "수정 실패 하였습니다.");
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		
		$data['q'] = $this -> query;
		$data['view'] = $this -> email_m -> mail_view($me_idx);

		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/email/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}

	/* 메일 입력 */
	public function write(){
	    $data['view'] = null;
	    $data['errors'] = array();
	    
		$this -> load -> helper('form');
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		
		if( $_POST ){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('me_subject', '제목', 'required');
			$this -> form_validation -> set_rules('me_content', '내용', 'required');

			if( $this -> form_validation -> run() == TRUE ){
				//글작성
				$write_data = array(
					'me_subject' => $this -> input -> post('me_subject', TRUE),
					'me_content' => $this -> input -> post('me_content', TRUE)
				);
				$result = $this -> email_m -> mail_insert($write_data);
				if( $result ){
				    alert("저장하였습니다.", $this->list_href);
				}else{
				    alert("저장 실패하였습니다.", $this->list_href);
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
		
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/email/write_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}
	
	/* 리스트 */
	public function lists(){
	    $config['total_rows'] = $this -> email_m -> mail_list('count', '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod );
		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링
		$data['q'] = "?stx=".$this -> stx."&sfl=".$this -> sfl."&sst=".$this->sst."&sod=".$this->sod;
		
		//페이지네이션 설정
		$config['base_url'] = '/backs1te/email/'.$data['q'];

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
		$data['list'] = $this -> email_m -> mail_list( '', $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod  );
		
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this->col_per_page )-$i;
			//검색시 마크설정
			if( $this -> stx && $this -> sfl == 'me_subject' ){
			$data['list'][$i]->me_subject = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->me_subject );
			}
		}
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/email/list_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}

	/* 메일 삭제 */
	public function delete(){
	    $me_idx = $this -> input -> post('me_idx', TRUE);
	    $delete_data = array( "me_idx"=> $me_idx );
		if( $_POST ){
			$result = $this -> email_m -> mail_delete($delete_data);
			if($result == 1){
			    alert("삭제하였습니다.", $this->list_href);
			}else{
			    alert("삭제 실패하였습니다.", $this->list_href);
			}
		}
	}
	
	/* 메일 전송 */
	public function send(){
	    
	    $data['errors'] = array();
	    $data['me_idx'] = $this -> uri -> segment(4);
	    $data['email_result'] = array();
	    
	    $this -> load -> helper('form');
	    
	    //폼 검증 라이브러리 로드
	    $this -> load -> library('form_validation');
	    
	    if( $data['me_idx'] == null){
	        alert("잘못된 접근입니다.", $this->list_href);
	        exit;
	    }

	    if( $_POST ){
	        
	        //폼 검증할 필드와 규칙 사전 정의
	        $this -> form_validation -> set_rules('me_idx', 'idx', 'required');
	        
	        if( $this -> form_validation -> run() == TRUE ){
	            //email 라이브러리
	            $this -> load -> library('email');

	            $me_idx = $this -> input->post("me_idx", TRUE);
	            $me_select = $this -> input -> post("me_select", TRUE);
	            $mb_is_email = $this -> input -> post("mb_is_email") == null ? "0" : $this -> input -> post("mb_is_email");
	            $me_add_email = $this -> input -> post("me_add_email", TRUE);
	            
	            $mb_level_start = $this -> input -> post("mb_level_start", TRUE) == null ? "2" : $this -> input -> post("mb_level_start", TRUE);
	            $mb_level_last = $this -> input -> post("mb_level_last", TRUE) == null ? "10" : $this -> input -> post("mb_level_last", TRUE);
	            
	            //config 환경설정 정보
	            $config = $this -> config_m -> get_config();
	            $email = $this -> email_m -> mail_view($me_idx);
	            $member_list = array();
	            
	            if( $me_select == "all" ){
                    $select_data = array(
                        "mb_is_email" => $mb_is_email,
                        "mb_level_start" => $mb_level_start,
                        "mb_level_last" => $mb_level_last
                    );
                    $member_list = $this-> email_m -> member_email_list($select_data);
	            }else if( $me_select == "part" ){
	                $member_list[0] = array("mb_email" => "{$me_add_email}");
	            }
	            
	            if( $config != null && $email != null ){
	                foreach ($member_list as $ml){
	                    $from_email = $me_select == "all" ? "{$ml->mb_email}" : "{$ml['mb_email']}" ;
    	                
	                    $this -> email -> clear();
    	                $this -> email -> from($config -> cf_admin_email, "{$config -> cf_title} 관리자");
    	                $this -> email -> to($from_email);
    	                
    	                $this -> email -> subject($email != null ? $email->me_subject : "Email_test!");
    	                $this -> email -> message($email != null ? $email->me_content : "Testing the email");
    	                
    	                $result = $this -> email -> send();
    	                if( $result ){
    	                    array_push($data['email_result'], "{$from_email} 메일발송되었습니다");
    	                }else{
    	                    array_push($data['email_result'], "{$from_email} 메일발송 실패하였습니다");
    	                }
    	                
    	                log_message("debug", $this->email->print_debugger());
	                }
	            }else{
	                array_push($data['email_result'], "환경설정 메일 이나 메일 내용이 잘못되었습니다. 관리자에게 문의하세요.");
	            }
	        }else{
	            $this->form_validation->set_error_delimiters();
	            $data['errors'] = $this->form_validation->error_array();
	        }
	    }
	    
	    $data['member_count'] = $this-> member_m -> member_list('count');

	    //회원권한
	    $data['level_list'] = $this -> level_m -> get_level_list();
	    
	    //폼 검증 라이브러리 로드
	    $this -> load -> library('form_validation');
	    
		//스킨경로
		$data['page'] = $this->config->item("admin_dir")."/email/send_v";
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}
	
	//메일발송테스트
	public function json_send_email(){
	    //email 라이브러리 
	    $this -> load -> library('email');
	    $me_idx = $this -> input -> get("me_idx");
	    
	    //config 환경설정 정보
	    $config = $this -> config_m -> get_config();
	    $email = $this -> email_m -> mail_view($me_idx);
	    if( $config != null && $email != null ){
    	    $this -> email -> clear();
    	    $this -> email -> from($config -> cf_admin_email, "{$config -> cf_title} 관리자");
    	    $this -> email -> to($config -> cf_admin_email);
    	    
    	    $this -> email -> subject($email != null ? $email->me_subject : "Email_test!");
    	    $this -> email -> message($email != null ? $email->me_content : "Testing the email");
    	    
    	    if( $this -> email -> send()){
    	        echo json_encode(array("msg" =>"{$config -> cf_admin_email} 메일주소로 메일이 전송되었습니다.", "debug" =>$this->email->print_debugger() ));
    	    }else{
    	        echo json_encode(array("msg" =>"메일 전송 실패하였습니다.", "debug" =>$this->email->print_debugger() ));
    	    }
	    }else{
	        echo json_encode(array("msg" =>"환경설정 메일 이나 메일 내용이 잘못되었습니다. 관리자에게 문의하세요."));
	    }
	}

}
?>