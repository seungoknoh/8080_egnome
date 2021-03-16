<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Board_SearchField_m extends CI_Model{
	private $table = "board_search_field";
	function __construct(){
		parent::__construct();
	}
	
	/* 게시판별 검색조건값 리스트 */
	function board_searchField_list( $type='',$op_table='', $bs_type='' , $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='ASC'){
		log_message("DEBUG", "sfl - {$sfl}");
		log_message("DEBUG", "stx - {$stx}");
		$search_query = "";
		if( $stx != "" && $sfl == "op_name" ){//게시판명으로 검색
		    $search_query = " WHERE a.op_table = b.op_table and b.{$sfl} LIKE '%{$stx}%' ";
		} elseif( $stx != "" && $sfl == "bs_type" ) {//검색조건타입으로 검색
			$search_query = " WHERE a.op_table = b.op_table and a.{$sfl} LIKE '%{$stx}%' ";
		} elseif( $stx != "" && $sfl == "bs_name" ) {//검색조건명으로 검색
			$search_query = " WHERE a.op_table = b.op_table and a.{$sfl} LIKE '%{$stx}%' ";
		} else {
			$search_query = " WHERE a.op_table = b.op_table ";
		}
		
		$order_query = " ORDER BY bs_type, bs_name ASC ";

		$limit_query = '';
		if( $limit != '' OR $offset != ''){
			$limit_query = " LIMIT ".$offset.','.$limit;
		}

		$sql = "SELECT a.*, b.op_name FROM {$this->table} a, board_option b
                {$search_query}
                {$order_query}
                {$limit_query} ";

		$query = $this -> db -> query($sql);
	    log_message("DEBUG", "board_searchField_list - {$sql}");			
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

	/* board_searchField 조회 */
	function get_board_searchField($bs_idx= ''){
		$sql = "SELECT * FROM ".$this->table." WHERE bs_idx='{$bs_idx}'";
		$query = $this->db->query($sql);
		$result = $query -> row();
		
		return $result;
	}

	/* board_searchField 조회 */
	function search_type_list($op_table= ''){
		$sql = "SELECT bs_idx, bs_type FROM {$this -> table} WHERE op_table='{$op_table}' ";
		$query = $this->db->query($sql);
		$result = $query -> row();
	    //log_message("DEBUG", "search_type_list - {$sql}");		
		return $result;
	}

	/* 검색필드타입 조회 */
	function board_searchField_view($bs_idx= ''){
		$sql = "SELECT * FROM ".$this->table."  WHERE bs_idx={$bs_idx}";
		$query = $this->db->query($sql);
		$result = $query -> row();
	    //log_message("DEBUG", "board_searchField_view - {$sql}");		
		return $result;
	}

	//중복체크
	function board_searchField_chk($bs_idx){
		$sql = "SELECT COUNT(bs_idx) as cnt FROM {$this -> table} WHERE bs_idx='{$bs_idx}'";
		$query = $this -> db -> query($sql);
		var_dump(query);
		return $query -> row();
	}


	/* 검색조건 입력 */
	function board_searchField_insert($data){
		$insert_data = array(
			'op_table' => $data['op_table'],
			'bs_type' => $data['bs_type'],
			// 'bs_code' => $data['bs_code'],
			'bs_name' => $data['bs_name']
		);
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}

	/* 검색조건 수정 */
	function board_searchField_update($data){
		$update_data = array(
			'op_table' => $data['op_table'],
			'bs_type' => $data['bs_type'],
			// 'bs_code' => $data['bs_code'],
			'bs_name' => $data['bs_name']
		);
		$where = array(
			'bs_idx' => $data['bs_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);

		return $result;
	}
	
	/* 게시판관리 삭제 */
	function board_searchField_delete($bs_idx){
       $sql = "delete from {$this->table} where bs_idx = '{$bs_idx}'";
       $result =  $this->db->query($sql);
       return $result;
	}

	/* 검색조건값 불러오기 */
	function get_board_search_list($op_table='', $bs_type=''){
		$search_query = "";
		if( $bs_type == "year" ){
			$search_query = " ORDER by bs_name asc";
		}
		$sql = "select * from {$this->table} where op_table = '{$op_table}' and bs_type='{$bs_type}'
		                {$search_query} ";
		$query = $this -> db -> query($sql);
		log_message("DEBUG", "get_board_search_list - {$sql}");	
		$result = $query -> result();		
		return $result;			
	}
	/* End of file Board_option_m.php */
	/* Location : ./application/models/board/Board_option_m.php */
}
?>