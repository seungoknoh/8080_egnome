<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends Adm_Controller {
	public $op_table = "";
	public $col_per_page = 10;
	public $query = ""; //쿼리스트링
	public $stx = ""; //검색어
	public $sfl = ""; // 검색필드
	public $sst = ""; //정렬필드
	public $sod = ""; //정렬순
	public $sca = ""; //카테고리
	public $bo_year = ""; // 년도검색필드
	public $bo_yearmd = ""; // 년도검색필드
	public $bo_scf1 = ""; //분석타입검색필드	
	public $bo_scf2 = ""; //종검색필드
	public $per_page = ""; //페이지
	public $total = ""; //전체 리스트
	public $option = "";
	public $category_list = "";
	
	public $list_href = "";
	public $write_href = "";
	public $delete_href = "";
    public $action = "";
	public $lang_list = array(
		"ko" => "국문",
		"en" => "영문",
	);    
	function __construct(){
		parent::__construct();
		
		$this -> sfl = $this -> input ->get("sfl", FALSE);
		$this -> stx = $this -> input ->get("stx", FALSE);
		$this -> sst = $this-> input ->get("sst", FALSE);
		$this -> sod = $this-> input ->get("sod", FALSE);
		$this -> sca = $this -> input -> get("sca", False);
		$this -> bo_year = $this -> input -> get("bo_year", FALSE); // 년도검색필드
		$this -> bo_scf1 = $this -> input -> get("bo_scf1", FALSE);; //분석타입검색필드	
		$this -> bo_scf2 = $this -> input -> get("bo_scf2", FALSE); //종검색필드
		$this -> per_page = $this -> input -> get("per_page", FALSE);
		$this -> query = "?per_page={$this -> per_page}&sca={$this->sca}&stx={$this -> stx}&sfl={$this -> sfl}&sst={$this->sst}&sod={$this->sod}&bo_yearmd={$this->bo_year}&bo_scf1={$this->bo_scf1}&bo_scf2={$this->bo_scf2}";

		$this -> load -> helper( array('alert','editor','thumbnail','uploader')); 
		$this -> load -> model("board/file_m");
		$this -> load -> model("board/board_m");
		$this -> load -> model("board/board_option_m");
		$this -> load -> model("board/board_category_m");
		$this -> load -> model("board/board_SearchField_m");		

		//이미지 썸네일
		$this -> load -> library("image_lib");
		$this -> op_table = $this -> uri -> segment(4);
		$this -> option = $this -> board_option_m -> get_board_option($this -> op_table);

		//게시판 존재여부
		if( $this -> board_option_m -> get_board_option($this -> op_table) == null){
			alert($this -> lang -> line('error_board_missing'));
		}

		//카테고리리스트
		$this -> category_list = $this -> board_category_m -> list($this -> op_table);
		
		//링크
		$this -> list_href =  "/backS1te/board/lists/{$this -> option -> op_table}";
		$this -> write_href = "/backS1te/board/write/{$this -> option -> op_table}";
		$this -> delete_href = "/backS1te/board/delete/{$this -> option -> op_table}";
		
		//메서드
		$this -> action = $this -> uri -> segment(3);
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> lists();
	}

	/* 수정 */
	public function update(){
	    $data['is_reply'] = false;
		$data['errors'] = array();
		
	    $op_table = $this -> uri -> segment(4);
		$bo_idx = $this -> uri -> segment(5);

		$data['delete_href'] = $this -> delete_href;
		$data['reply_href'] = $this -> write_href."/".$bo_idx;
		$data['list_href'] = $this -> list_href;

		//URL 헬퍼 로드
		$this->load->helper('url');
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			$this->db->trans_start();
			$this->db->trans_begin();
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('bo_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('bo_subject', '제목', 'required');
			$this -> form_validation -> set_rules('bo_content', '내용', 'required');
			//$this -> form_validation -> set_rules('bo_lang', '언어선택', 'required');
			
			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'bo_subject' => $this -> input -> post('bo_subject', TRUE),
					'bo_content' => $this -> input -> post('bo_content', FALSE),
					'bo_caption' => $this -> input -> post('bo_caption', TRUE),
					//'bo_lang' => $this -> input -> post('bo_lang', TRUE),
					'bo_client' => $this -> input -> post('bo_client', TRUE),
					'bo_company' => $this -> input -> post('bo_company', TRUE),					
				    'bc_idx' => $this -> input -> post('bc_idx', TRUE),
					'bo_ref' => $this -> input -> post('bo_ref', TRUE),
					'bo_is_secret' => 0,
					'op_table' => $op_table,
					'bo_idx' => $this -> input -> post('bo_idx', TRUE),
					'bo_is_view' => $this -> input -> post('bo_is_view', TRUE) == null ? 0 : $this -> input -> post('bo_is_view', TRUE),
					'bo_is_file_thumb' => $this -> input -> post('bo_is_file_thumb', TRUE) == null ? 0 : $this -> input -> post('bo_is_file_thumb', TRUE),
					'bo_url' => $this -> input -> post('bo_url', TRUE),
					'bo_url2' => $this -> input -> post('bo_url2', TRUE),
					'bo_yearmd' => $this -> input -> post('bo_yearmd', TRUE) == null ? 'none' : $this -> input -> post('bo_yearmd', FALSE),
					'bo_yearmd2' => $this -> input -> post('bo_yearmd2', TRUE) == null ? 'none' : $this -> input -> post('bo_yearmd2', FALSE),
					'bo_scf1' => $this -> input -> post('bo_scf1', TRUE),
					'bo_scf2' => $this -> input -> post('bo_scf2', TRUE),
					'bo_subtitle' => $this -> input -> post('bo_subtitle', TRUE)
				);
				$result = $this -> board_m -> board_update($write_data);
				if( $result ){
	
				    $bf_delete = $this->input ->post("bf_delete");
				    makeDir($op_table);
				    
				    // 가변 파일 업로드
				    $file_upload_msg = '';
				    $upload = array();
				    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
				    $cnt = is_countable($bf_delete) && count($bf_delete);
				    for( $i=0;$i<$cnt;$i++){
				        //삭제체크시 파일삭제
				        if( isset( $bf_delete[$i]) ){
				            $upload[$i]['del_check'] = true;
							$file = $this -> file_m -> get_file_idx($bf_delete[$i]);
							
							//썸네일있는경우 썸네일삭제
							delete_file_thumbnail($file->op_table, $file->bf_filename);

				            //파일삭제
				            @unlink(UPLOAD_FILE_DIR.'/'.$file->op_table.'/'.$file->bf_filename);
				            //DB삭제
				            $file = $this -> file_m -> delete_idx($bf_delete[$i]);
				        }else{
				            //log_message("debug", "//삭제체크안함");
				            $upload[$i]['del_check'] = false;
				        }
				    }
				    $cnt = is_countable($_FILES['bf_file']['name']) && count($_FILES['bf_file']['name']);
				    for ($i=0; $i<$cnt; $i++) {
				        $upload[$i]['file']     = '';
				        $upload[$i]['source']   = '';
				        $upload[$i]['filesize'] = 0;
				        $upload[$i]['image']    = array();
				        
				        $file_data = array(
				            'op_table' => $op_table,
				            'bo_idx' => $bo_idx,
				            'bf_num' => $i
				        );
				        
				        $tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
				        $filesize  = $_FILES['bf_file']['size'][$i];
				        $filename  = $_FILES['bf_file']['name'][$i];
				        $filename  = get_safe_filename($filename);
				        
				        if (is_uploaded_file($tmp_file)) {
				            $timg = @getimagesize($tmp_file);
				            $upload[$i]['image'] = $timg;
				            
				            // 프로그램 원래 파일명
				            $upload[$i]['source'] = $filename;
				            $upload[$i]['filesize'] = $filesize;
				            
				            // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
				            $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);
				            
				            shuffle($chars_array);
				            $shuffle = implode('', $chars_array);
				            
				            // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
				            $upload[$i]['file'] = time().'_'.substr($shuffle,0,8).'_'.trim($filename);
				            $dest_file = UPLOAD_FILE_DIR."/{$op_table}/".$upload[$i]['file'];
				            
				            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
				            $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);
				            
				            // 올라간 파일의 퍼미션을 변경합니다.
				            chmod($dest_file, 0644);
				            
				            //DB저장
				            $bf_num = $this -> file_m -> get_file_num($file_data);
				            $upload_data = array(
				                'op_table' => $op_table,
				                'bo_idx' => $bo_idx,
				                'bf_num' => $bf_num,
				                'bf_filename' => $upload[$i]['file'],
				                'bf_source' => $upload[$i]['source'],
				                'bf_type' => $upload[$i]['image']['mime'],// ??= '',
				                'bf_filesize' => $upload[$i]['filesize']
				            );
				            $this -> file_m -> insert($upload_data);
				        }
				    }
					//array_push($data['errors'], "저장에 성공하였습니다.");
					$this->db->trans_complete();
					$this->db->trans_commit();

				    alert("저장하였습니다.", "/backS1te/board/update/{$op_table}/$bo_idx");
				}else{
					//array_push($data['errors'], "저장에 실패하였습니다.");
					alert("저장에 실패하였습니다.", "/backS1te/board/update/{$op_table}/$bo_idx");
				}
			}else{
				$this->form_validation->set_error_delimiters();
				$data['errors'] = $this->form_validation->error_array();
			}
		}
	
		//파일리스트 (저장 때문에 뒤에다가 둬야 제대로 작동함)
		$data['fileList'] = $this -> file_m -> list( "", array( 'bo_idx' => $bo_idx ) ); //list

		$data['q'] = $this -> query;
		$view_data = array(
			'op_table' => $op_table,
			'bo_idx' => $bo_idx
		);
		
		$data['view'] = $this -> board_m -> board_view($view_data);
		if( $data['view'] == null ){
		    alert("잘못된 접근입니다.", $this->list_href);
		}

		$data['analtype'] = $this -> board_SearchField_m -> get_board_search_list("research","Analysis type");//검색필드 select 값 출력위함
		//$data['year'] = $this -> board_SearchField_m -> get_board_search_list("research","year");//검색필드 select 값 출력위함
		$data['species'] = $this -> board_SearchField_m -> get_board_search_list("research","species");//검색필드 select 값 출력위함
				
		//스킨경로
		$data['page'] = $this->config->item("admin_dir").'/board/skin/'.$this -> option -> op_adm_skin.'/write_v';
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);
	}

	public function category(){
		$data = array();
		$data['category_list'] = $this->category_list;
		
		//갤러리 썸네일
		if( $this->option->op_is_gallery != null && $this->option->op_is_gallery == 1){
			$config['width'] = $this->option->op_thumb_width;
			$config['height'] = $this->option->op_thumb_height;
			$config['image_library'] = 'gd2';
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = "_thumb{$config['width']}x{$config['height']}";
		}

		foreach($this->category_list as $cate){
			$list = $this->board_m->board_front_list('', $this->op_table, '', '', '', '', '', '', $cate->bc_idx, $this->html_lang);

			foreach($list as $ls){
				//링크
				$ls->link = $this->view_href."{$ls->bo_idx}";
				//갤러리 썸네일 
				if( $this->option->op_is_gallery == 1){
					if( $ls->bo_is_file_thumb == 1){
						$fileList = $this->board_file_m -> list("", array( 'bo_idx' =>$ls->bo_idx ));
						$ls->thumbnail = is_file_thumbnail($fileList, $this->option->op_thumb_width, $this->option->op_thumb_height, $ls->bo_subject, true);
					}else{
						$ls->thumbnail = is_thumbnail($ls->bo_content, $this->option->op_thumb_width,  $this->option->op_thumb_height, $ls->bo_subject, false);
					}
				}		
			}
			$cate->board_list = $list;
		}
		
		$this->_view('front/board/skin/'.$this->option->op_skin.'/category_v', $data);


		
	}

	/* 입력 */
	public function write(){
	    $data['errors'] = array();
		$data['fileList'] = array();
		$data['write_href'] = $this -> write_href;

		$op_table = $this -> uri -> segment(4);
		$bo_idx = $this -> uri -> segment(5);
		
		$data['is_reply'] = false;
		if( $op_table != null && $bo_idx != null ){
			$data['is_reply'] = true;
			$data['reply'] = $this -> board_m -> board_view(array("bo_idx"=> $bo_idx, "op_table" => $op_table ));
			$data['bo_parent'] = $bo_idx;
		}
		
		$this -> load -> helper('form');
		
		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');
		$data['view'] = null;
		
		if( $_POST ){
		    //카테고리
		    if( $this->option->op_is_category == 1 ){
		        $this -> form_validation -> set_rules('bc_idx', '카테고리', 'required');
		    }
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('op_table', '테이블명', 'required');
			$this -> form_validation -> set_rules('bo_subject', '제목', 'required');
			$this -> form_validation -> set_rules('bo_content', '내용', '');

			if( $this -> form_validation -> run() == TRUE ){
				//글작성
				$write_data = array(
					'op_table' => $this -> input -> post('op_table', TRUE),
					'bo_subject' => $this -> input -> post('bo_subject', TRUE),
					'bo_content' => $this -> input -> post('bo_content', FALSE),
					'bo_caption' => $this -> input -> post('bo_caption', TRUE),
					//'bo_lang' => $this -> input -> post('bo_lang', TRUE),
					'bo_client' => $this -> input -> post('bo_client', TRUE),
					'bo_company' => $this -> input -> post('bo_company', TRUE),
					'bc_idx' => $this -> input -> post('bc_idx', TRUE),
					'bo_is_secret' => 0,
					'bo_is_file_thumb' => $this -> input -> post('bo_is_file_thumb', TRUE) == null ? 0 : $this -> input -> post('bo_is_file_thumb', TRUE),
					'bo_ref' => null,
					'bo_is_view' => $this -> input -> post('bo_is_view', TRUE) == null ? 0 : $this -> input -> post('bo_is_view', TRUE),
					'mb_id' => $this -> session -> userdata('mb_id'),
					'bo_url' => $this -> input -> post('bo_url', TRUE),
					'bo_url2' => $this -> input -> post('bo_url2', TRUE),
					'bo_yearmd' => $this -> input -> post('bo_yearmd', TRUE) == null ? 'none' : $this -> input -> post('bo_yearmd', FALSE),
					'bo_yearmd2' => $this -> input -> post('bo_yearmd2', TRUE) == null ? 'none' : $this -> input -> post('bo_yearmd2', FALSE),
					'bo_scf1' => $this -> input -> post('bo_scf1', TRUE),
					'bo_scf2' => $this -> input -> post('bo_scf2', TRUE),					
					'bo_subtitle' => $this -> input -> post('bo_subtitle', TRUE)
				);
				//답변인경우
				if( $this -> input -> post('bo_ref', TRUE) != null ){
					$write_data['bo_parent'] = $this -> input -> post('bo_parent', TRUE);
					$write_data['bo_level'] = $this -> input -> post('bo_level', TRUE);
					$write_data['bo_ref'] = $this -> input -> post('bo_ref', TRUE);
				}

				$result = $this -> board_m -> board_insert($write_data);
	
				if( $result['bo_idx'] ){
					//log_message("debug", "insert-".$result['bo_idx']);
					
				    makeDir($op_table);
				    
				    // 가변 파일 업로드
				    $file_upload_msg = '';
				    $upload = array();
				    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
					//$cnt = is_countable($_FILES['bf_file']['name']) && count($_FILES['bf_file']['name']);
				    for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
				        $upload[$i]['file']     = '';
				        $upload[$i]['source']   = '';
				        $upload[$i]['filesize'] = 0;
				        $upload[$i]['image']    = array();
	        
				        $file_data = array(
				            'op_table' => $op_table,
				            'bo_idx' => $result['bo_idx'],
				            'bf_num' => $i
				        );
				        
				        $tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
				        $filesize  = $_FILES['bf_file']['size'][$i];
				        $filename  = $_FILES['bf_file']['name'][$i];
				        $filename  = get_safe_filename($filename);
				        
				        if (is_uploaded_file($tmp_file)) {
				            $timg = @getimagesize($tmp_file);
				            $upload[$i]['image'] = $timg;
				            
				            // 프로그램 원래 파일명
				            $upload[$i]['source'] = $filename;
				            $upload[$i]['filesize'] = $filesize;
				            
				            // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
				            $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);
				            
				            shuffle($chars_array);
				            $shuffle = implode('', $chars_array);
				            
				            // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
				            $upload[$i]['file'] = time().'_'.substr($shuffle,0,8).'_'.trim($filename);
				            $dest_file = UPLOAD_FILE_DIR."/{$op_table}/".$upload[$i]['file'];
				            
				            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
				            $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);
				            
				            // 올라간 파일의 퍼미션을 변경합니다.
				            chmod($dest_file, 0644);
				            
				            //DB저장
				            $bf_num = $this -> file_m -> get_file_num($file_data);
				            $upload_data = array(
				                'op_table' => $op_table,
				                'bo_idx' => $result['bo_idx'],
				                'bf_num' => $bf_num,
				                'bf_filename' => $upload[$i]['file'],
				                'bf_source' => $upload[$i]['source'],
				                'bf_type' => $upload[$i]['image']['mime'],
				                'bf_filesize' => $upload[$i]['filesize']
				            );
				            $this -> file_m -> insert($upload_data);
				        }
				    }
				    goto_url($this->list_href);
				}else{
				    array_push($data['errors'], "저장에 실패하였습니다.");
				}
			}else{
			    $this->form_validation->set_error_delimiters();
			    $data['errors'] = $this->form_validation->error_array();
			}
		}
		$data['q'] = $this -> query;

		$data['analtype'] = $this -> board_SearchField_m -> get_board_search_list("research","Analysis type");//검색필드 select 값 출력위함
		//$data['year'] = $this -> board_SearchField_m -> get_board_search_list("research","year");//검색필드 select 값 출력위함
		$data['species'] = $this -> board_SearchField_m -> get_board_search_list("research","species");//검색필드 select 값 출력위함

		//스킨경로
		$data['page'] = $this->config->item("admin_dir").'/board/skin/'.$this -> option -> op_adm_skin.'/write_v';
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);

	}
	
	/* 리스트 */
	public function lists(){
	    $data['list_href'] = $this -> list_href;
        $data['category_list'] = $this-> category_list;
        $config['total_rows'] = $this -> board_m -> board_list('count', $this -> op_table, '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod, $this -> sca,$this->bo_year,$this->bo_scf1,$this->bo_scf2 );

		$data['total'] = $config['total_rows'];

		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		
		//쿼리스트링 $this -> bo_yearmd,$this ->bo_scf1,$this ->bo_scf2
		$data['q'] = "?stx={$this -> stx}&sfl={$this -> sfl}&sst={$this->sst}&sod={$this->sod}&sca={$this->sca}&bo_yearmd={$this->bo_year}&bo_scf1={$this->bo_scf1}&bo_scf2={$this->bo_scf2}";
		
		//페이지네이션 설정
		$config['base_url'] = "/backS1te/board/index/{$this->op_table}{$data['q']}";

		//페이지당 보여줄 갯수
		//$config['per_page'] = $this-> col_per_page ;
		if($this -> op_table == 'bigdata'){
			$config['per_page'] = 8;
		} else {
			$config['per_page'] = $this-> col_per_page ;
		}
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
		$data['list'] = $this -> board_m -> board_list( '', $this->op_table, $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod , $this->sca,$this -> bo_year,$this ->bo_scf1,$this ->bo_scf2 );
		
		
		//갤러리 썸네일
		if( $this -> option -> op_is_gallery == 1){
		    $config['width'] = $this->option->op_thumb_width;
		    $config['height'] = $this->option->op_thumb_height;
		    $config['image_library'] = 'gd2';
		    $config['create_thumb'] = TRUE;
		    $config['thumb_marker'] = "_thumb{$config['width']}x{$config['height']}";
		}
		
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this->col_per_page )-$i;
			
			//링크 //&bo_yearmd={$this -> bo_yearmd}&bo_scf1={$this -> bo_scf1}&bo_scf2={$this -> bo_scf2}
			$data['list'][$i]->link = "/backS1te/board/update/{$data['list'][$i]->op_table}/{$data['list'][$i]->bo_idx}";
			$data['list'][$i]->link .="?stx={$this -> stx}&sfl={$this -> sfl}&sst={$this->sst}&sca={$data['list'][$i]->bc_idx}&bo_yearmd={$this -> bo_yearmd}&bo_scf1={$this -> bo_scf1}&bo_scf2={$this -> bo_scf2}";

			//검색시 마크설정
			if( $this -> stx && $this -> sfl == 'bo_subject' ){
			    $data['list'][$i]->bo_subject = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->bo_subject );
			}
			
            //작성자 검색설정
			if( $this -> stx && $this -> sfl == 'mb_id' ){
			    $data['list'][$i]->mb_id = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i]->mb_id );
			}

            //갤러리 썸네일
			//if( $this -> option -> op_is_gallery == 1){
			//    $data['list'][$i]->thumbnail = is_thumbnail($data['list'][$i]->bo_content, 300, 300, $data['list'][$i]->bo_subject);
			//}
			if( $this -> option -> op_is_gallery == 1){
				if( $data['list'][$i] -> bo_is_file_thumb == 1){
					$fileList = $this -> file_m -> list("", array( 'bo_idx' =>$data['list'][$i] -> bo_idx ));
					$data['list'][$i] -> thumbnail = is_file_thumbnail($fileList, $this->option->op_thumb_width, $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);
				}else{
					$data['list'][$i] -> thumbnail = is_thumbnail($data['list'][$i] -> bo_content, $this->option->op_thumb_width,  $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);
				}
			}			
		}
			//스킨경로
		$data['page'] = $this->config->item("admin_dir").'/board/skin/'.$this -> option -> op_adm_skin.'/list_v';
		$this -> load -> view($this->config->item("admin_dir").'/_layout/container_v', $data);		
	}

	/* 게시물 삭제 */
	public function delete(){
		$op_table = $this -> input -> post('op_table', TRUE);
		$bo_idx = $this -> input -> post('bo_idx', TRUE);
		$delete_data = array("bo_idx"=> $bo_idx, "op_table" => $op_table );

		if( $_POST ){
			$view_data = $this -> board_m -> board_view($delete_data);
			if( $view_data -> bo_child == 0 ){
			    
			    $this->db->trans_begin();
			    
			    //파일삭제
			    $this -> file_m -> delete($view_data);
				
				//게시물삭제
			    $this -> board_m -> board_delete($view_data);
			    
			    if ($this->db->trans_status() === FALSE){
			        $this->db->trans_rollback();
			    }
			    else{
			        $this->db->trans_commit();
			    }
			    alert("삭제하였습니다.", $this->list_href );
			}else{
			    alert("하위 글이 있는 경우 삭제할수 없습니다.", $this->list_href );
			}
		}
	}

	/* 카테고리수정 */
	public function categoryUpdate(){
		$op_table = $this -> input -> post('op_table', TRUE);
		$bo_idxs = $this -> input -> post('chk_bo_idxs', TRUE);
		$bc_idx = $this -> input -> post('bc_idx', TRUE);

		if( $_POST ){
			$update_data = array(
				'op_table' => $op_table,
				'bo_idxs' => $bo_idxs,
				'bc_idx' => $bc_idx
			);
			if( $this -> board_m -> board_update_category($update_data) ){
				alert( "수정하였습니다", $this -> list_href );
			}
		}
	}
}
?>