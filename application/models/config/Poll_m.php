<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	설문 모델
*/
class Poll_m extends CI_Model{
	private $table = "poll";
	private $result_table = "poll_result";

	function __construct(){
		parent::__construct();

		$this -> load -> helper("common");
	}

	/* 설문 가능 리스트 */
	function poll_view_list(){
		$sql = "SELECT * FROM {$this->table} WHERE pl_is_view = 1";
		$query = $this -> db -> query($sql);
		$result = $query -> result();
		return $result;
	}
	
	function poll_view_result($pl_idx){
		$sql =  " SELECT a.pl_idx, b.pr_check, count(pr_check) as total ";
		$sql .= " FROM {$this->table} a LEFT JOIN {$this->result_table} b ";
		$sql .= " ON a.pl_idx = b.pl_idx";
		$sql .= " WHERE b.pl_idx = {$pl_idx}"; 
		$sql .= " GROUP BY b.pr_check ";
		$sql .= " ORDER BY pr_check ASC";
		$query = $this -> db -> query($sql);
		$result = $query -> result();
		return $result;
	}

	/* 게시물 리스트  */
	function poll_list( $type='' , $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC' ){
		
		$search_query = " WHERE 1 ";
		if( $stx != "" && $sfl != "" ){
			$search_query .= ' and '.$sfl.' LIKE "%'.$stx.'%" ';
		}
		
		$order_query = " ORDER BY pl_regdate DESC";
		if( $sst != ""){
			$order_query = " ORDER BY ".$sst." ".$sod;
		}

		$limit_query = '';
		if( $limit != '' OR $offset != ''){
			$limit_query = " LIMIT ".$offset.','.$limit;
		}

		$sql = "SELECT * FROM ".$this->table.$search_query.$order_query.$limit_query;
		$query = $this -> db -> query($sql);
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

	/* 설문 삭제 */
	function poll_delete($data){
	    $view = $this->poll_view($data['pl_idx']);
	    if( isset($view) ){
	        $sql = "DELETE FROM {$this->table} WHERE pl_idx=".$data['pl_idx'];
	        $query = $this -> db -> query($sql);
	        return $query;
	    }else{
	        return false;
	    }
	}

	/* 설문 뷰 */
	function poll_view($idx){
		$sql = "SELECT * FROM ".$this->table." WHERE pl_idx = ".$idx." ";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}

	/* 설문 입력 */
	function poll_insert($data){
		$insert_data = array(
			'pl_subject' => $data['pl_subject'],
			'pl_content' => $data['pl_content'],
			'pl_question' => $data['pl_question'],
			'pl_is_view' => $data['pl_is_view'],
			'pl_regdate' => date("Y-m-d H:i:s"),
			'pl_writer' => $data['pl_writer'],
		);

		//새글입력
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}
	
	/* 설문 수정 */
	function poll_update($data){
		$update_data = array(
			'pl_subject' => $data['pl_subject'],
			'pl_question' => $data['pl_question'],
			'pl_content' => $data['pl_content'],
			'pl_is_view' => $data['pl_is_view']
		);
		$where = array(
			'pl_idx' => $data['pl_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/* 설문 입력 */
	function poll_result_insert($data){
		$insert_data = array(
			'pl_idx' => $data['pl_idx'],
			'pr_check' => $data['pr_check'],
			'pr_ip' => get_client_ip(),
			'pr_regdate' => date("Y-m-d H:i:s"),
			'mb_id' => $data['mb_id'],
		);

		//새글입력
		$result = $this->db->insert($this->result_table, $insert_data);
		return $result;
	}

	/* 설문 체크 */
	function poll_result_view($data){
		$sql = "SELECT * FROM {$this->result_table} WHERE pl_idx ={$data['pl_idx']} ";
		if($data['mb_id']){
			$sql .= " and mb_id ='{$data['mb_id']}'";
		}
		if($data['mb_id'] == null){
			$sql .= " and isNull(mb_id) and pr_ip ='".get_client_ip()."'";
		}
		log_message("DEBUG", $sql);
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}
	/* End of file poll_m.php */
	/* Location : ./application/models/config/poll_m.php */
}
?>