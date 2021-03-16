<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends FRONT_Controller {
	public $op_table = "";
	public $col_per_page = 5;
	public $query = ""; //쿼리스트링
	public $stx = ""; //검색어
	public $sfl = ""; // 검색필드
	public $sst = ""; //정렬필드
	public $bo_yearmd = ""; // 년도검색필드
	public $bo_year = ""; // 년도검색필드
	public $bo_scf1 = ""; //분석타입검색필드	
	public $bo_scf2 = ""; //종검색필드
	public $sca = ""; //카테고리
	public $per_page = ""; //페이지
	public $total = ""; //전체 리스트
	public $option = "";
	public $category_list = "";
	public $board_title = "";

	public $list_href = "";
	public $write_href = "";
	public $reply_href = "";
	public $delete_href = "";
	public $update_href = "";
	public $view_href = "";
	public $member = null;
	public $board_member_level = 1;
	public $is_admin = false;
	public $action;
	public $bo_type = null;

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
		$this -> bo_type = $this -> input -> get("bo_type", FALSE);
		$this -> query = "?";
		$this -> op_table = $this -> uri -> segment(3);
		if( $this -> bo_type != null ){
			$this -> query .= "bo_type={$this->bo_type}&amp;";
		}
		if($this -> op_table == "research" || $this -> op_table == "press"){
			$this -> query .= "sca={$this->sca}&amp;stx={$this -> stx}&amp;sfl={$this -> sfl}&amp;sst={$this->sst}&amp;sod={$this->sod}&amp;bo_yearmd={$this->bo_year}&amp;bo_scf1={$this->bo_scf1}&amp;bo_scf2={$this->bo_scf2}";
		} else {
			$this -> query .= "sca={$this->sca}&amp;stx={$this -> stx}&amp;sfl={$this -> sfl}&amp;sst={$this->sst}&amp;sod={$this->sod}";
		}
		
		
		//헬퍼로딩
		$this -> load -> helper( array('alert','editor','thumbnail','uploader', 'common', 'error', 'token')); 
		
		//모델로딩
		$this -> load -> model("member/auth_m");
		$this -> load -> model("board/file_m");
		$this -> load -> model("board/board_m");
		$this -> load -> model("board/board_file_m");
		$this -> load -> model("board/board_option_m");
		$this -> load -> model("board/board_category_m");
		$this -> load -> model("board/board_SearchField_m");		
		
		//이미지 썸네일
		$this -> load -> library("image_lib");
		//$this -> op_table = $this -> uri -> segment(3);
		$this -> option = $this -> board_option_m -> get_board_option($this -> op_table);
		
		//게시판 존재여부
		if( $this -> board_option_m -> get_board_option($this -> op_table) == null){
			alert($this -> lang -> line('error_board_missing'));
		}

		//카테고리리스트
		$this -> category_list = $this -> board_category_m -> list($this -> op_table);
		$this -> col_per_page = $this -> option -> op_page_rows;

		//링크
		$this -> list_href = "/board/lists/{$this -> op_table}";		
		$this -> delete_href = "/board/delete/{$this -> op_table}";			
		$this -> write_href = "/board/write/{$this -> op_table}";
		$this -> view_href = "/board/view/{$this -> op_table}/";
		$this -> update_href = "/board/update/{$this -> op_table}";
		
		$this -> action = $this -> uri -> segment(2);
		
		//멤버
		$this -> member = $this -> auth_m -> getMemberById($this -> session -> userdata("mb_id"));
			
		//접근가능 여부레벨
		$this -> board_member_level = $this -> member == null ? 1 : $this -> member -> mb_level;
		$this -> is_admin = $this -> member == null ? FALSE : $this -> member -> mb_level > 7;	

		//var_dump( $this -> member );
		//var_dump( $this -> is_admin );
	}

	/* 주소에서 메서드가 생략되었을때 실행되는 기본 메서드 */
	public function index(){
		$this -> lists();
	}

	public function download(){
		$ie = isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false);

		$this -> load -> helper('download');
		$bf_idx = $this -> input ->get("bf_idx", TRUE);

		$file = $this -> file_m -> get_file_idx( $bf_idx );
		if( $file ){
			$data = file_get_contents(UPLOAD_FILE_DIR."/{$this->op_table}/{$file->bf_filename}"); // Read the file's contents
			$file_name = $file->bf_source;
			if($ie) {
			   // UTF-8에서 EUC-KR로 캐릭터셋 변경
			   $file_name = iconv('utf-8', 'euc-kr', $file_name);
			   // IE인 경우 헤더 변경
			   header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			   header('Pragma: public');
			}else{
			   // IE가 아닌 경우 일반 헤더 적용
			   header("Cache-Control: no-cache, must-revalidate"); 
			   header('Pragma: no-cache');
			}
			force_download($file_name, $data);
		}
		else{
			alert($this -> lang -> line('error_file_missing'));
		}
	}

	/* 뷰 */
	public function view(){

		$this -> load -> library('form_validation');
		$this -> op_table = $this -> uri -> segment(3);

		$data['token'] = get_token(); //토큰생성
		$bo_idx = $this -> uri -> segment(4);

		$view_data = array(
			'op_table' => $this -> op_table,
			'bo_idx' => $bo_idx
		);

		//게시물 보냄
		if($this -> op_table == "bigdata"){
			$data['view'] = $this -> board_m -> board_big_view($view_data);
			$big_file = UPLOAD_FILE_URL."/{$this->op_table}/".$data['view'] ->bf_filename;
			//log_message("DEBUG", "big_file-{$big_file}");		
			$data['big_file'] = $big_file;			
		} else {
			$data['view'] = $this -> board_m -> board_view($view_data);
		}
		
		if( $data['view'] == null ) alert($this -> lang -> line("error_fail_view"), $this -> list_href);

		if( !$this -> is_admin ){
			if( $this -> session -> userdata('ss_is_view') == null || $this -> session -> userdata('ss_is_view') != $data['view'] -> bo_idx ){
				//게시판 리스트 접근 가능여부
				if( ($data['view'] -> bo_is_secret && $this -> member != null && $this -> member -> mb_id != $data['view'] -> mb_id) || ($this -> option -> op_level_view > $this -> board_member_level) ){
					alert($this -> lang -> line('error_fail_view'));
				}

				//비회원 && 비밀글인경우
				if( $this -> member == null && $data['view'] -> bo_is_secret ){
					if( $data['view'] -> mb_id != null ) alert($this -> lang -> line('error_fail_view'));
					alert($this -> lang -> line('msg_secret_guest_confirm'), "/board/confirm/{$this -> op_table}/{$bo_idx}?token=".$data['token']."&action=view");
				}
			}else{
				$this -> session -> unset_userdata('ss_is_view');
			}
		}
		
		//링크
		$data['write_href'] = $this -> option -> op_level_write > $this -> board_member_level ? "" : $this -> write_href;
		$data['delete_href'] = $this -> option -> op_level_write > $this -> board_member_level ? "" : $this -> delete_href."/".$bo_idx."?token=".$data['token'];
		$data['update_href'] =  $this -> option -> op_level_write > $this -> board_member_level ? "" : $this -> update_href."/".$bo_idx;

		$data['list_href'] = $this -> option -> op_level_list > $this -> board_member_level ? "" : $this -> list_href;
		$data['view_href'] = $this -> option -> op_level_view > $this -> board_member_level ? "" : $this -> view_href;
		$data['reply_href'] = $this -> option -> op_level_reply > $this -> board_member_level ? "" : $this -> write_href."/".$bo_idx;


		$view_data['bo_ref'] = $data['view'] -> bo_ref;
		$view_data['sca'] = $this->sca;

		//게시물리스트 뷰
		// if($this -> op_table == "bigdata"){
		// 	$data['view'] = $this -> board_m -> board_big_view($view_data);
		// } else {
		// 	$data['view'] = $this -> board_m -> board_view($view_data);
		// }		
		$data['view_list_prev'] = $this -> board_m -> board_view_list($view_data, "prev", $this->html_lang);
		$data['view_list_next'] = $this -> board_m -> board_view_list($view_data, "next", $this->html_lang);


		$metaImage = is_thumbnail($data['view'] -> bo_content, 600, 315, '');

		//타이틀- 메타데이터
		$meta_item = array(
			'mt_title' => $data['view'] -> bo_subject,
			'mt_image' => $metaImage['src'],
			'mt_description' => trim(strip_tags($data['view'] -> bo_content))
		);

		//$this -> session -> set_userdata($meta_item);
		
		$data['metaTag'] = $meta_item;

		//파일리스트
		//$big_file = UPLOAD_FILE_URL."/{$this->op_table}/".$data['view'] ->bf_filename;
//log_message("DEBUG", "big_file-{$big_file}");		
		//$data['big_file'] = $big_file;
		$data['fileList'] = $this -> file_m -> list( "", array( 'bo_idx' => $bo_idx ) ); //list
// log_message("DEBUG", "fileList-{$data['view'] ->bf_filename}");
		$data['link_file'] = "/board/download/{$this->op_table}/";


		//세션으로 게시물증가 - 한번 읽은글은 브라우저를 닫기전까지는 카운트를 증가시키지 않음
		$ss_view_name = 'ss_view_'.$this -> op_table.'_'.$bo_idx;
//log_message("DEBUG", "ss_view_name-{$ss_view_name}");
//log_message("DEBUG", "bo_idx-{$bo_idx}");
		if( !$this -> session -> userdata($ss_view_name) ){
			$update_data = array(
				'bo_idx' => $bo_idx,
				'op_table' => $this->op_table
			);
			$this -> board_m -> board_update_hit($update_data);//조회수를 증가시킴.
			//세션이름을 준다.
			$this -> session -> set_userdata($ss_view_name, true);
		} 

		//$data['page'] = 'front/board/skin/'.$this -> option -> op_skin.'/view_v';
		//$this -> load -> view('front/_layout/container_v', $data);
		$this->_view('front/board/skin/'.$this->option->op_skin.'/view_v', $data);
	}

	public function category(){
		$data = array();
		$data['category_list'] = $this->category_list;
		$config['total_rows'] = $this -> board_m -> board_front_big_list('count', $this->op_table, '', '', '', '', '', '', $this->sca);
		$data['total'] = $config['total_rows'];
		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		//페이지당 보여줄 갯수
		//$config['per_page'] = $this-> col_per_page ;
		$config['per_page'] = 9;
		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;
		$config['num_links'] = 2;
		$data['link'] = "/board/category/{$this->op_table}";
		$data['link_file'] = "/board/download/{$this->op_table}/"; //sonoh 20210125
		//페이지네이션 설정
		$config['base_url'] = "/board/category/{$this->op_table}{$this -> query}";
		//페이지네이션 초기화
		$this -> pagination -> initialize($config);
		//페이징 링크를 생성하여 view 에서 사용할 변수에 할당.
		$data['pagination'] = $this -> pagination -> create_links();
		//게시물 목록을 불러오기 위한 offset, limit 값 가져오기
		$per_page = $this -> input ->get("per_page", TRUE) == "" ? 0 : $this -> input ->get("per_page", TRUE);
		$limit = $config['per_page'];
		$start = $per_page;
		$data['page'] = floor($per_page/$this->col_per_page)+1;
		$data['options'] = $this -> option;
		$data['per_page'] = $per_page;
//log_message("DEBUG", "bigdataCategoryLIST-{$this -> board_m -> board_front_big_list('count', $this->op_table, '', '', '', '', '', '', $this->sca)}");
		//갤러리 썸네일
		if( $this->option->op_is_gallery != null && $this->option->op_is_gallery == 1){
			$config['width'] = $this->option->op_thumb_width;
			$config['height'] = $this->option->op_thumb_height;
			$config['image_library'] = 'gd2';
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = "_thumb{$config['width']}x{$config['height']}";
		}

		//( $type='' , $op_table='', $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC', $sca=''){
		//foreach($this->category_list as $cate){
			$data['list'] = $this->board_m->board_front_big_list('', $this->op_table, $start, $limit, '', '', '', '', $this->sca);
			//$list = $this->board_m->board_front_big_list('', $this->op_table, '', '', '', '', '', '', $cate->bc_idx, $this->html_lang);
			//log_message("DEBUG", "bigdataCategoryLIST-{count($list)}");
			for( $i = 0; $i < count($data['list']); $i++){
				//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
				$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this -> col_per_page )-$i;
				//링크
				$data['list'][$i] -> link = "/board/view/{$data['list'][$i] -> op_table}/{$data['list'][$i] -> bo_idx}";
				//$data['list'][$i] -> link .= $this->view_href."{$data['list'][$i]->bo_idx}";
				//비밀글여부
				$data['list'][$i] -> is_secret = $data['list'][$i] -> bo_is_secret == 1 ? true : false;
				//새글유무
				$data['list'][$i] -> is_new = strtotime("{$data['list'][$i] -> bo_regdate} +{$this -> option -> op_new_date} hours") > strtotime("Now");
				//파일유무
				$data['list'][$i] -> is_file = $data['list'][$i] -> file_count > 0;
			
				//갤러리 썸네일
				if( $this -> option -> op_is_gallery == 1){
					if( $data['list'][$i] -> bo_is_file_thumb == 1){
						$fileList = $this -> board_file_m -> list("", array( 'bo_idx' =>$data['list'][$i] -> bo_idx ));
						$data['list'][$i] -> thumbnail = is_file_thumbnail($fileList, $this->option->op_thumb_width, $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);
					}else{
						$data['list'][$i] -> thumbnail = is_thumbnail($data['list'][$i] -> bo_content, $this->option->op_thumb_width,  $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);
					}
				}			
				//내용
				if( $this -> option -> op_is_preview != 1){
					$data['list'][$i] -> subject = common_content($data['list'][$i] -> bo_subject, 140);
				}
				$data['list'][$i] -> bc_name = $data['list'][$i] -> bc_name;

			}			

			//foreach($list as $ls){
			//링크
			//$ls->link = $this->view_href."{$ls->bo_idx}";
			//갤러리 썸네일 
			// 	if( $this->option->op_is_gallery == 1){
			// 		if( $ls->bo_is_file_thumb == 1){
			// 			$fileList = $this->board_file_m -> list("", array( 'bo_idx' =>$ls->bo_idx ));
			// 			$ls->thumbnail = is_file_thumbnail($fileList, $this->option->op_thumb_width, $this->option->op_thumb_height, $ls->bo_subject, true);
			// 		}else{
			// 			$ls->thumbnail = is_thumbnail($ls->bo_content, $this->option->op_thumb_width,  $this->option->op_thumb_height, $ls->bo_subject, false);
			// 		}
			// 	}
			// 	$ls->subject = "{$ls->bo_subject}";
			// 	$ls->bc_name = "{$ls->bc_name}";
			// }
			// //$cate->board_list = $list;
			// $data['list'] = $list;
		//}
		
		$this->_view('front/board/skin/'.$this->option->op_skin.'/category_v', $data);
	}	

	/* 리스트 */
	public function lists(){
		//게시판유무 여부
		if( $this -> op_table == null ){
			alert($this -> lang -> line('error_board_missing'));
		}
		//게시판 리스트 접근 가능여부
		if( $this -> option -> op_level_list > $this -> board_member_level ){
			alert($this -> lang -> line('error_fail_list'));
		}
		//글쓰기레벨여부
		$data['write_href'] = $this -> option -> op_level_write > $this -> board_member_level ? "" : $this -> write_href;
		$data['category_list'] = $this-> category_list;
		$config['total_rows'] = $this -> board_m -> board_front_list('count', $this -> op_table, '', '', $this -> stx, $this -> sfl, $this -> sst, $this -> sod, $this -> sca,$this -> bo_year,$this ->bo_scf1,$this ->bo_scf2, $this->html_lang );
		$data['total'] = $config['total_rows'];
		//페이지네이션 라이브러리 로딩 추가
		$this -> load -> library('pagination');
		//페이지당 보여줄 갯수
		//$config['per_page'] = $this-> col_per_page ;
		if($this -> op_table == 'research'){
			$config['per_page'] = 2;
		} elseif($this -> op_table == 'press') {
			$config['per_page'] = 9;
		} elseif($this -> op_table == 'notice'){
			$config['per_page'] = 5;
		} else {
			$config['per_page'] = $this-> col_per_page ;
		}
		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;
		$config['num_links'] = 2;
		$data['link'] = "/board/lists/{$this->op_table}";
		$data['link_file'] = "/board/download/{$this->op_table}/"; //sonoh 20210125
		//페이지네이션 설정
		$config['base_url'] = "/board/lists/{$this->op_table}{$this -> query}";
		//페이지네이션 초기화
		$this -> pagination -> initialize($config);
		//페이징 링크를 생성하여 view 에서 사용할 변수에 할당.
		$data['pagination'] = $this -> pagination -> create_links();
		//게시물 목록을 불러오기 위한 offset, limit 값 가져오기
		$per_page = $this -> input ->get("per_page", TRUE) == "" ? 0 : $this -> input ->get("per_page", TRUE);
		$limit = $config['per_page'];
		$start = $per_page;
		$data['page'] = floor($per_page/$this->col_per_page)+1;
		$data['options'] = $this -> option;
		$data['per_page'] = $per_page;

		if( $this -> op_table == 'gallery' ){
			$data['list'] = $this -> board_m -> board_front_list( '', $this->op_table, $start, $limit, $this -> stx, $this -> sfl, 'cate', $this->sod, $this->sca,'','','',$this->html_lang );
		}elseif( $this -> op_table == 'research'){
			$data['list'] = $this -> board_m -> board_front_list( '', $this->op_table, $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod , $this->sca,$this -> bo_year,$this ->bo_scf1,$this ->bo_scf2,$this->html_lang );
		}elseif( $this -> op_table == 'press'){
			$data['list'] = $this -> board_m -> board_front_list( '', $this->op_table, $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod , $this->sca,$this -> bo_year,$this ->bo_scf1,$this ->bo_scf2,$this->html_lang );			
		}else{
			$data['list'] = $this -> board_m -> board_front_list( '', $this->op_table, $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod , $this->sca,'','','',$this->html_lang );
		}

		//갤러리 썸네일
		if( $this -> option -> op_is_gallery != null && $this -> option -> op_is_gallery == 1){
			$config['width'] = $this -> option -> op_thumb_width;
			$config['height'] = $this -> option -> op_thumb_height;
			$config['image_library'] = 'gd2';
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = "_thumb{$config['width']}x{$config['height']}";
		}
		for( $i = 0; $i < count($data['list']); $i++){
			//번호매기기 ( 총갯수 - (페이지번호-1 * 페이지당 데이터갯수)- 순차)
			$data['list'][$i]->num = $config['total_rows'] - (($data['page']-1) * $this -> col_per_page )-$i;
			//링크
			$data['list'][$i] -> link = "/board/view/{$data['list'][$i] -> op_table}/{$data['list'][$i] -> bo_idx}";
			$data['list'][$i] -> link .= $this -> query;
			//비밀글여부
			$data['list'][$i] -> is_secret = $data['list'][$i] -> bo_is_secret == 1 ? true : false;
			//새글유무
			$data['list'][$i] -> is_new = strtotime("{$data['list'][$i] -> bo_regdate} +{$this -> option -> op_new_date} hours") > strtotime("Now");
			//파일유무
			$data['list'][$i] -> is_file = $data['list'][$i] -> file_count > 0;
			//log_message("DEBUG",  strtotime("{$data['list'][$i] -> bo_regdate}")."-".strtotime("{$data['list'][$i] -> bo_regdate} +{$this->option->op_new_date} hours").">".strtotime("Now"));
			//검색시 마크설정
			//if( $this -> stx && $this -> sfl == 'bo_subject' ){
			//	$data['list'][$i] -> bo_subject = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i] -> bo_subject );
			//}
			//검색시 검색타입과 상관없이 전체선택시에도 제목란에 마크설정			
			if( $this -> stx ){
				$data['list'][$i] -> bo_subject = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i] -> bo_subject );
			}	
			//작성자 검색설정
			if( $this -> stx && $this -> sfl == 'mb_id' ){
				$data['list'][$i] -> mb_id = str_replace( $this -> stx, "<mark>".$this -> stx."</mark>", $data['list'][$i] -> mb_id );
			}
			//갤러리 썸네일
			// 첨부파일이 등록 되어 있고, 썸네일 체크가 된 경우 출력, 그 외는 본문의 내용으로 출력
			if( $this -> option -> op_is_gallery == 1){
				if( $data['list'][$i] -> file_count > 0 && $data['list'][$i] -> bo_is_file_thumb == 1 ) {
					$fileList = $this -> file_m -> list("", array( 'bo_idx' =>$data['list'][$i] -> bo_idx ));
					$data['list'][$i] -> thumbnail = is_file_thumbnail($fileList, $this->option->op_thumb_width, $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);
					//log_message("DEBUG",  "첨부파일이 등록 되어 있고, 썸네일 체크가 된 경우");
				}
				else {
					$data['list'][$i] -> thumbnail = is_thumbnail($data['list'][$i] -> bo_content, $this->option->op_thumb_width, $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);				
					//log_message("DEBUG",  "본문내용 출력");					
				}
			}
			else if( $this -> op_table == 'research'){
				if( $data['list'][$i] -> file_count > 0 && $data['list'][$i] -> bo_is_file_thumb == 1 ) {
					$fileList = $this -> file_m -> list("", array( 'bo_idx' =>$data['list'][$i] -> bo_idx ));
					$data['list'][$i] -> thumbnail = is_file_thumbnail($fileList, $this->option->op_thumb_width, $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);
				}
				else {
					$data['list'][$i] -> thumbnail = is_thumbnail($data['list'][$i] -> bo_content, $this->option->op_thumb_width,  $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);				
				}
			}
			
			// if( $this -> op_table == 'press'){
			// 	if( $data['list'][$i] -> file_count > 0 && $data['list'][$i] -> bo_is_file_thumb == 1 ) {
			// 		$fileList = $this -> file_m -> list("", array( 'bo_idx' =>$data['list'][$i] -> bo_idx ));
			// 		$data['list'][$i] -> thumbnail = is_file_thumbnail($fileList, $this->option->op_thumb_width, $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);
			// 	}
			// 	else {
			// 		$data['list'][$i] -> thumbnail = is_thumbnail($data['list'][$i] -> bo_content, $this->option->op_thumb_width, $this->option->op_thumb_height, $data['list'][$i] -> bo_subject, false);				
			// 	}
			// }

			// if( $this -> op_table == 'press'){
			// 	$data['list'][$i] -> thumbnail = is_thumbnail($data['list'][$i] -> bo_content, 416,  288, $data['list'][$i] -> bo_subject, false);
			// }		

			//내용
			if( $this -> option -> op_is_preview != 1){
				$data['list'][$i] -> bo_content = common_content($data['list'][$i] -> bo_content, 140);
			}
			//$data['list'][$i] -> bo_year = substr($this -> bo_year,0,4);
			//var_dump($data['list'][$i] -> bo_year);
		}
		$data['analtype'] = $this -> board_SearchField_m -> get_board_search_list("research","Analysis type");//검색필드 select 값 출력위함
		$data['year'] = $this -> board_SearchField_m -> get_board_search_list("research","year");//검색필드 select 값 출력위함
		$data['species'] = $this -> board_SearchField_m -> get_board_search_list("research","species");//검색필드 select 값 출력위함
				
		//$data['page'] = 'front/board/skin/'.$this -> option -> op_skin.'/list_v';
		//$this -> load -> view('front/_layout/container_v', $data);
		$this->_view('front/board/skin/'.$this->option->op_skin.'/list_v', $data);

	}

	/* 입력 */
	public function write(){
	    $data['errors'] = array();
		$data['fileList'] = array();
		
		//접근권한 확인
		if( $this -> option -> op_level_write > $this -> board_member_level ){
			alert($this -> lang -> line('error_fail_write'));
		}

		$bo_idx = $this -> uri -> segment(4);
		$data['write_href'] = $this -> option -> op_level_write > $this -> board_member_level ? "" : $this -> write_href;
		$data['delete_href'] = $this -> option -> op_level_write > $this -> board_member_level ? "" : $this -> delete_href;
		$data['update_href'] =  $this -> option -> op_level_write > $this -> board_member_level ? "" : $this -> update_href."/".$bo_idx;
		
		//답변
		$data['is_reply'] = false;
		if( $this -> op_table != null && $bo_idx != null ){
			$data['is_reply'] = true;
			$data['reply'] = $this -> board_m -> board_view(array("bo_idx"=> $bo_idx, "op_table" => $this -> op_table ));
			$data['bo_parent'] = $bo_idx;
		}

		//폼 헬퍼 로드
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		//캡챠 라이브러리 로드
		include_once(CAPTCHA_PATH.'/kcaptcha.php');
		$data['captcha_html'] = '';
		$data['captcha_js']   = '';
		if( $this -> member == null ){ //비회원인경우
			$data['captcha_html'] = captcha_html();
			$data['captcha_js'] = chk_captcha_js();
		}

		$data['view'] = null;
		
		if( $_POST ){
			//캡챠
			if ($this -> member == null && !chk_captcha()) {
				alert('자동등록방지 숫자가 틀렸습니다.');	
			}

			//토큰확인
			check_token();

		    //카테고리
		    if( $this -> option -> op_is_category == 1 ){
		        $this -> form_validation -> set_rules('bc_idx', '카테고리', 'required');
		    }
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('op_table', '테이블명', 'required');
			$this -> form_validation -> set_rules('bo_subject', '제목', 'required');
			$this -> form_validation -> set_rules('bo_content', '내용', '');
			$this -> form_validation -> set_rules('bo_is_secret', '비밀글', ''); 
			if( $this -> member == null){
				$this -> form_validation -> set_rules('bo_writer', '작성자', 'required'); 
				$this -> form_validation -> set_rules('bo_passwd', '비밀번호', 'required'); 
			}

			if( $this -> form_validation -> run() == TRUE ){

				//글작성
				$write_data = array(
					'op_table' => $this -> input -> post('op_table', TRUE),
					'bo_subject' => $this -> input -> post('bo_subject', TRUE),
					'bo_content' => $this -> input -> post('bo_content', FALSE),
					'bo_caption' => $this -> input -> post('bo_caption', TRUE),
					'bo_writer' => $this -> member == null ? $this -> input -> post('bo_writer', TRUE) : $this -> member -> mb_name,
				    'bc_idx' => $this -> input -> post('bc_idx', TRUE),
					'bo_ref' => null,
					'bo_is_view' => 1,
					'bo_is_secret' => $this -> input -> post('bo_is_secret', TRUE) == null ? 0 : 1,
					'mb_id' => $this -> member != null ? $this -> member -> mb_id : null, 
					'bo_passwd' => $this -> input -> post('bo_passwd', TRUE) == null ? "" : $this -> input -> post('bo_passwd', TRUE)
				);

				//답변인경우
				if( $this -> input -> post('bo_ref', TRUE) != null ){
					$write_data['bo_parent'] = $this -> input -> post('bo_parent', TRUE);
					$write_data['bo_level'] = $this -> input -> post('bo_level', TRUE);
					$write_data['bo_ref'] = $this -> input -> post('bo_ref', TRUE);
				}

				$result = $this -> board_m -> board_insert($write_data);
	
				if( $result['bo_idx'] ){
					
				    makeDir($this -> op_table);
				    
				    // 가변 파일 업로드
				    $file_upload_msg = '';
				    $upload = array();
				    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

				    for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
				        $upload[$i]['file']     = '';
				        $upload[$i]['source']   = '';
				        $upload[$i]['filesize'] = 0;
				        $upload[$i]['image']    = array();
				        $upload[$i]['image'][0] = '';
				        $upload[$i]['image'][1] = '';
				        $upload[$i]['image'][2] = '';
				        
				        $file_data = array(
				            'op_table' => $this -> op_table,
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
				            $dest_file = UPLOAD_FILE_DIR."/{$this -> op_table}/".$upload[$i]['file'];
				            
				            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
				            $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);
				            
				            // 올라간 파일의 퍼미션을 변경합니다.
				            chmod($dest_file, 0644);
				            
				            //DB저장
				            $bf_num = $this -> file_m -> get_file_num($file_data);
				            $upload_data = array(
				                'op_table' => $this -> op_table,
				                'bo_idx' => $result['bo_idx'],
				                'bf_num' => $bf_num,
				                'bf_filename' => $upload[$i]['file'],
				                'bf_source' => $upload[$i]['source'],
				                'bf_type' => $upload[$i]['image']['2'],
				                'bf_filesize' => $upload[$i]['filesize']
				            );
				            $this -> file_m -> insert($upload_data);
				        }
				    }
				    goto_url($this -> list_href);
				}else{
					alert($this -> lang -> line('msg_save_failed'), $this -> list_href);
				}
			}else{
			    $this -> form_validation -> set_error_delimiters();
			    $data['errors'] = $this -> form_validation -> error_array();
			}
		}

		$data['token'] = get_token(); //토큰생성

		//$data['page'] = 'front/board/skin/'.$this -> option -> op_skin.'/write_v';
		//$this -> load -> view('front/_layout/container_v', $data);
		$this->_view('front/board/skin/'.$this->option->op_skin.'/write_v', $data);		
	}	

	/* 수정 */
	public function update(){
	    $data['is_reply'] = false;
		$data['errors'] = array();
		
		if(!$_POST){
			$data['token'] = get_token(); //토큰생성
		}
		$bo_idx = $this -> uri -> segment(4);
		$view_data = array(
			'op_table' => $this -> op_table,
			'bo_idx' => $bo_idx
		);
		$data['view'] = $this -> board_m -> board_view($view_data);
		if( $data['view'] == null ){
		    alert("잘못된 접근입니다.", $this -> list_href);
		}
		if(!$this -> is_admin){
			if( $this -> session -> userdata('ss_is_update') == null || $this -> session -> userdata('ss_is_update') != $data['view'] -> bo_idx ){
				//접근권한 확인
				if( ($this -> member != null && $this -> member -> mb_id != $data['view'] -> mb_id ) || $this -> option -> op_level_write > $this -> board_member_level ){
					alert($this -> lang -> line('error_fail_update'), $this -> view_href."/".$bo_idx.$this -> query );
				}
	
				//비회원 && 비밀글인경우(비회원 -> 비밀글 아닌거 수정 - 바로 넘어가는거 해결해야함...)
				if( $this -> member == null && $data['view'] -> mb_id == null ){
					alert($this -> lang -> line('msg_guest_confirm'), "/board/confirm/{$this -> op_table}/{$bo_idx}?token=".$data['token']."&action=update");
				}
			}
		}
		$data['list_href'] = $this -> list_href;


		//폼 헬퍼 로드
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');

		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('bo_idx', 'idx', 'required');
			$this -> form_validation -> set_rules('bo_subject', '제목', 'required');
			$this -> form_validation -> set_rules('bo_content', '내용', 'required');
			
			if( $this -> form_validation -> run() == TRUE ){
				$write_data = array(
					'bo_subject' => $this -> input -> post('bo_subject', TRUE),
					'bo_content' => $this -> input -> post('bo_content', FALSE),
					'bo_caption' => $this -> input -> post('bo_caption', TRUE),
				    'bc_idx' => $this -> input -> post('bc_idx', TRUE),
					'bo_ref' => $this -> input -> post('bo_ref', TRUE),
					'op_table' => $this -> op_table,
					'bo_idx' => $this -> input -> post('bo_idx', TRUE),
					'bo_is_view' => 1,
					'bo_is_secret' => $this -> input -> post('bo_is_secret', TRUE) == null ? 0 : 1
				);
				$result = $this -> board_m -> board_update($write_data);
				if( $result ){
					makeDir($this -> op_table);
					
					// 가변 파일 업로드
					$file_upload_msg = '';
					$upload = array();
					$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

					$bf_delete = isset($_POST['bf_delete']) ? $_POST['bf_delete'] : null;
					if( isset($bf_delete) ){
						for( $i=0;$i<count($bf_delete);$i++){
							//삭제체크시 파일삭제
							if( isset( $bf_delete[$i]) ){
								$upload[$i]['del_check'] = true;
								$file = $this -> file_m -> get_file_idx($bf_delete[$i]);
								//파일삭제
								@unlink(UPLOAD_FILE_DIR.'/'.$file -> op_table.'/'.$file -> bf_filename);
								//DB삭제
								$file = $this -> file_m -> delete_idx($bf_delete[$i]);
							}else{
								$upload[$i]['del_check'] = false;
							}
						}
					}
				    for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
				        $upload[$i]['file']     = '';
				        $upload[$i]['source']   = '';
				        $upload[$i]['filesize'] = 0;
				        $upload[$i]['image']    = array();
				        $upload[$i]['image'][0] = '';
				        $upload[$i]['image'][1] = '';
				        $upload[$i]['image'][2] = '';
				        
				        $file_data = array(
				            'op_table' => $this -> op_table,
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
				            $dest_file = UPLOAD_FILE_DIR."/{$this -> op_table}/".$upload[$i]['file'];
				            
				            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
				            $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);
				            
				            // 올라간 파일의 퍼미션을 변경합니다.
				            chmod($dest_file, 0644);
				            
				            //DB저장
				            $bf_num = $this -> file_m -> get_file_num($file_data);
				            $upload_data = array(
				                'op_table' => $this -> op_table,
				                'bo_idx' => $bo_idx,
				                'bf_num' => $bf_num,
				                'bf_filename' => $upload[$i]['file'],
				                'bf_source' => $upload[$i]['source'],
				                'bf_type' => $upload[$i]['image']['2'],
				                'bf_filesize' => $upload[$i]['filesize']
				            );
				            $this -> file_m -> insert($upload_data);
				        }
					}
					$this -> session -> unset_userdata('ss_is_update');
					alert($this -> lang -> line('msg_save_success'), $this -> list_href);
					
				}else{
					array_push($data['errors'],  $this -> lang -> line('msg_save_failed'));
				}
			}else{
				$this -> form_validation -> set_error_delimiters();
				$data['errors'] = $this -> form_validation -> error_array();
			}
		}
	
		//파일리스트 (저장 때문에 뒤에다가 둬야 제대로 작동함)
		$data['fileList'] = $this -> file_m -> list( "", array( 'bo_idx' => $bo_idx ) ); //list
		
	
		//스킨
		//$data['page'] = 'front/board/skin/'.$this -> option -> op_skin.'/write_v';
		//$this -> load -> view('front/_layout/container_v', $data);	
		$this->_view('front/board/skin/'.$this->option->op_skin.'/write_v', $data);		
	}

	/* 삭제 */
	public function delete(){
		$bo_idx = $this -> uri -> segment(4);
		$delete_data = array("bo_idx"=> $bo_idx, "op_table" => $this -> op_table );
		$view_data = $this -> board_m -> board_view($delete_data);		
		
		if(!$this -> is_admin){
			//비밀번호 확인 뒤 - 세션으로 삭제가능하면 토큰검사해서 삭제
			if( $this -> session -> userdata('ss_is_delete') == null || $this -> session -> userdata('ss_is_delete') != $bo_idx ){

				//권한확인(회원이면서 회원의 아이디가 아닌경우, 권한이 작은경우)
				if( ($this -> member != null && $this -> member -> mb_id != $view_data -> mb_id) || ($this -> option -> op_level_write > $this -> board_member_level) ){
					alert("삭제할 수 없습니다.");
				}

				//비회원확인
				if( $this -> member == null && $view_data -> mb_id == null ){
					alert($this -> lang -> line('msg_guest_confirm'), "/board/confirm/{$this -> op_table}/{$bo_idx}?token=".get_token()."&action=delete");
				}
				//토큰확인
				check_token();		
			}else{
				//토큰확인
				check_token();
				$this -> session -> unset_userdata('ss_is_delete');
			}
		}

		if( $view_data -> bo_child == 0 ){
			$this -> db -> trans_begin();
			
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
			alert("삭제하였습니다.", $this -> list_href );
		}else{
			alert("하위 글이 있는 경우 삭제할수 없습니다.", $this -> view_href."/".$bo_idx );
		}
		
	}

	/* 게시물 확인(비밀번호) */
	public function confirm(){
		//토큰확인
		check_token();

		$bo_idx = $this -> uri -> segment(4);
		$data['errors'] = array();
		$data['action'] = null;
		if( $_REQUEST['action'] ){
			$data['action'] = $_REQUEST['action'];
		}

		//있는 글인지 확인
		$view_data = array(
			'op_table' => $this -> op_table,
			'bo_idx' => $bo_idx
		);

		//게시글확인
		$view = $this -> board_m -> board_view($view_data);
		if( $view == null ) alert("존재하지 않는 게시물입니다.", $this -> list_href );
		
		//폼 헬퍼 로드
		$this -> load -> helper('form');

		//폼 검증 라이브러리 로드
		$this -> load -> library('form_validation');		
		if($_POST){
			//폼 검증할 필드와 규칙 사전 정의
			$this -> form_validation -> set_rules('bo_passwd', '비밀번호', 'required');
			
			if( $this -> form_validation -> run() == TRUE ){
				if( $view -> bo_passwd == $this -> input -> post("bo_passwd") ){
					$action = $this -> input -> post('action');
					$this -> session -> set_userdata("ss_is_{$action}", $bo_idx );
					
					redirect("/board/{$action}/{$this -> op_table}/{$bo_idx}?token=".get_token());
				}else{
					array_push($data['errors'], "틀린 비밀번호입니다. 다시 확인해주세요.");
				}
			}else{
				$this -> form_validation -> set_error_delimiters();
				$data['errors'] = $this -> form_validation -> error_array();				
			}			
		}

		//스킨
		//$data['page'] = 'front/board/confirm_v';
		//$this -> load -> view('front/_layout/container_v', $data);			
		$this->_view('front/board/confirm_v', $data);
	}

	public function contents(){
		$bo_idx = $this -> uri -> segment(4);
		if( $bo_idx == null ){
			$bo_idx = $this -> board_m -> board_view_min_idx($this -> op_table) -> bo_min_idx;
			if( $bo_idx ){
				goto_url("/board/contents/{$this -> op_table}/{$bo_idx}");
			}else{
				alert($this -> lang -> line('error_fail_view'));
			}
		}
		//$data['page'] = 'front/board/skin/'.$this -> option -> op_skin.'/contents_v';
		//$this -> load -> view('front/_layout/container_v', $data);
		$this->_view('front/board/skin/'.$this->option->op_skin.'/contents_v', $data);		
	}
}
?>