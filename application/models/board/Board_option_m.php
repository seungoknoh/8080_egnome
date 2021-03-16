<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Board_option_m extends CI_Model{
	private $table = "board_option";
	function __construct(){
		parent::__construct();
	}
	
	/* board_option 리스트 */
	function board_option_list( $type='' , $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC'){
		
		$search_query = "";
		if( $stx != "" && $sfl != "" ){
		    $search_query = " WHERE a.{$sfl} LIKE '%{$stx}%' ";
		}
		
		$order_query = " ORDER BY a.op_idx DESC ";
		if( $sst != ""){
			$order_query = " ORDER BY a.{$sst} {$sod}";
		}

		$limit_query = '';
		if( $limit != '' OR $offset != ''){
			$limit_query = " LIMIT ".$offset.','.$limit;
		}

		$sql = "SELECT a.*, IFNULL(count, 0) as op_count FROM {$this->table} a LEFT JOIN 
                ( SELECT op_table, count(*) as count FROM board GROUP BY op_table ) b 
                ON a.op_table = b.op_table
                {$search_query}
                {$order_query}
                {$limit_query} ";

		$query = $this -> db -> query($sql);
		log_message("DEBUG", "board_option_list - {$sql}");
		$result = null;
		if( $type == 'count' ){
			//전체 게시물 갯수반환
			$result = $query -> num_rows();
		}else{
			//게시물 리스트 변환
			$result = $query -> result();
		}	
		return $result;
	}

	/* board_option 조회 */
	function get_board_option($op_table= ''){
		$sql = "SELECT * FROM ".$this->table." WHERE op_table='{$op_table}'";
		$query = $this->db->query($sql);
		$result = $query -> row();
		
		return $result;
	}

	/* board_option 조회 */
	function board_option_view($op_idx= ''){
		$sql = "SELECT * FROM ".$this->table." WHERE op_idx={$op_idx}";
		$query = $this->db->query($sql);
		$result = $query -> row();
		
		return $result;
	}

	/* board_option 조회 */
	function get_info($op_idx= '', $field){
		$sql = "SELECT ".$field." FROM ".$this->table." WHERE op_idx={$op_idx}";
		$query = $this->db->query($sql);
		$result = $query -> row();
		
		return $result -> $field;
	}

	/* board_skin 조회 */
	function board_skin_list($type=''){
		if( $type == 'admin' ){
			$path = APPPATH."/views/admin/board/skin"; // 오픈하고자 하는 폴더 
		}else{
			$path = APPPATH."/views/front/board/skin"; // 오픈하고자 하는 폴더 
		}
		$entrys = array(); // 폴더내 Entry를 저장하기 위한 배열 
		$dirs = dir($path); // 오픈하기 
		while(false !== ($entry = $dirs->read())){ // 읽기 
			if( $entry != "." && $entry != ".." ){
			$entrys[] = $entry; 
			}
		} 
		$dirs->close(); // 닫기 
		return $entrys;
	}
	
	//중복체크
	function board_option_chk($op_table){
		$sql = "SELECT COUNT(op_idx) as cnt FROM {$this -> table} WHERE op_table='{$op_table}'";
		$query = $this -> db -> query($sql);
		return $query -> row();
	}

	/* 게시물입력 */
	function board_option_insert($data){
		$insert_data = array(
			'op_table' => $data['op_table'],
			'op_name' => $data['op_name'],
			'op_skin' => $data['op_skin'],
			'op_adm_skin' => $data['op_adm_skin'],
			'op_is_category' => $data['op_is_category'],
			'op_is_view_category' => $data['op_is_view_category'],
			'op_is_view_caption' => $data['op_is_view_caption'],
			'op_is_secret' => $data['op_is_secret'],
			'op_is_ip' => $data['op_is_ip'],
			'op_is_sign' => $data['op_is_sign'],
			'op_is_file' => $data['op_is_file'],
			'op_new_date' => $data['op_new_date'],
			'op_page_rows' => $data['op_page_rows'],
			'op_is_preview' => $data['op_is_preview'],	
			'op_level_list' => $data['op_level_list'],
			'op_level_view' => $data['op_level_view'],
			'op_level_comment' => $data['op_level_comment'],
			'op_level_reply' => $data['op_level_reply'],
			'op_level_write' => $data['op_level_write'],
			'op_thumb_width' => $data['op_thumb_width'],
			'op_thumb_height' => $data['op_thumb_height'],
			'op_is_gallery' => $data['op_is_gallery'],
			'op_img_max_width' => $data['op_img_max_width'],
			'op_regdate' => date("Y-m-d H:i:s")
		);
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}

	/* 게시물수정 */
	function board_option_update($data){
		$update_data = array(
			'op_name' => $data['op_name'],
			'op_skin' => $data['op_skin'],
			'op_adm_skin' => $data['op_adm_skin'],
			'op_is_category' => $data['op_is_category'],
			'op_is_view_category' => $data['op_is_view_category'],
			'op_is_view_caption' => $data['op_is_view_caption'],
			'op_is_secret' => $data['op_is_secret'],
			'op_is_ip' => $data['op_is_ip'],
			'op_is_sign' => $data['op_is_sign'],
			'op_is_file' => $data['op_is_file'],
			'op_is_preview' => $data['op_is_preview'],
			'op_new_date' => $data['op_new_date'],
			'op_page_rows' => $data['op_page_rows'],
			'op_level_list' => $data['op_level_list'],
			'op_level_view' => $data['op_level_view'],
			'op_level_comment' => $data['op_level_comment'],
			'op_level_reply' => $data['op_level_reply'],
			'op_level_write' => $data['op_level_write'],
			'op_thumb_width' => $data['op_thumb_width'],
			'op_thumb_height' => $data['op_thumb_height'],
			'op_is_gallery' => $data['op_is_gallery'],
			'op_img_max_width' => $data['op_img_max_width']
		);
		$where = array(
			'op_idx' => $data['op_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);

		return $result;
	}
	
	/* 게시판관리 삭제 */
	function board_option_delete($op_table){
       $sql = "delete from {$this->table} where op_table = '{$op_table}'";
       $result =  $this->db->query($sql);
       return $result;
	}

	/* End of file Board_option_m.php */
	/* Location : ./application/models/board/Board_option_m.php */
}
?>