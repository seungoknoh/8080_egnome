<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Board_category_m extends CI_Model{
	private $table = "board_category";

	function __construct(){
		parent::__construct();
	}

	/* 카테고리입력 */
	function insert($data){
		$cnt = is_countable($data['bc_name']) && count($data['bc_name']);
	    for($i=0; $i < $cnt;$i++){
    		$insert_data = array(
    			'op_table' => $data['op_table'],
				'bc_name' => $data['bc_name'][$i],
				'bc_order' => $i
    		);
    		$result = $this->db->insert($this->table, $insert_data);
	    }
	}
	
	/* 카테고리 리스트 */
	function list($op_table){
	    $sql = "select * from {$this->table} where op_table = '{$op_table}' order by bc_order asc";
	    $query = $this->db->query($sql);	    
	    return $query -> result();
	}

	/* 카테고리 업데이트 */
	function update($data){
		$cnt = is_countable($data['bc_idx']) && count($data['bc_idx']);
	    for($i=0; $i < $cnt;$i++){
	        $update_data = array(
				'bc_name' => $data['bc_update_name'][$i],
				'bc_order' => $i
	        );
	        $where = array(
	            'bc_idx' => $data['bc_idx'][$i],
	            'op_table' => $data['op_table']
	        );
	        $this->db->update($this->table, $update_data, $where);
	    }
	}
	
	/* 삭제리스트 */
	function delete($data){
		$cnt = is_countable($data['bc_delete']) && count($data['bc_delete']);
	    for($i=0; $i < $cnt;$i++){
	        $delete_data = array(
	            'bc_idx'=>$data['bc_delete'][$i],
	            'op_table' => $data['op_table']
	        );
	        $this -> db -> delete($this->table, $delete_data);
	    }
	}
	
	function delete_all($op_table){
	    
	    foreach ( $this->list($op_table) as $list ){
	        $sql = "delete from {$this->table} where op_table = '{$op_table}' and bc_idx = {$list->bc_idx}";
	        $this->db->query($sql);
	    }

	}

	/* End of file board_category_m.php */
	/* Location : ./application/models/board/board_category_m.php */
}
?>