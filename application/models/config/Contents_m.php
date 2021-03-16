<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Contents_m extends CI_Model{
	private $table = "contents";

	function __construct(){
		parent::__construct();
	}

	/* 게시물 리스트 */
	function contents_list( $type='' , $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC' ){
		
		$search_query = " WHERE 1 ";
		if( $stx != "" && $sfl != "" ){
			$search_query .= ' and '.$sfl.' LIKE "%'.$stx.'%" ';
		}
		
		$order_query = " ORDER BY cn_regdate DESC";
		if( $sst != ""){
			$order_query = " ORDER BY ".$sst." ".$sod;
		}

		$limit_query = '';
		if( $limit != '' OR $offset != ''){
			$limit_query = " LIMIT ".$offset.','.$limit;
		}

		$sql = "SELECT * FROM {$this->table} ".$search_query.$order_query.$limit_query;
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

	/* 컨텐츠 삭제 */
	function contents_delete($cn_idx){
	    $sql = "DELETE FROM ".$this->table." WHERE cn_idx=".$cn_idx;
		$query = $this -> db -> query($sql);
		return $query;
	}

	/* 컨텐츠 뷰 */
	function contents_view_link($cn_sitelink){
		$sql = "SELECT * FROM {$this->table} WHERE cn_sitelink = '{$cn_sitelink}' ";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}

	/* 컨텐츠 페이지값 */
	function contents_view($idx){
	    $sql = "SELECT * FROM ".$this->table." WHERE cn_idx = ".$idx." ";
	    $query = $this -> db -> query($sql);
	    $result = $query -> row();
	    return $result;
	}
	
	/* 컨텐츠 입력 */
	function contents_insert($data){
		$insert_data = array(
			'cn_subject' => $data['cn_subject'],
			'cn_content' => $data['cn_content'],
			'cn_is_view' => $data['cn_is_view'],
			'cn_sitelink' => $data['cn_sitelink'],
			'cn_regdate' => date("Y-m-d H:i:s")
		);

		//새글입력
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}
	
	/* 컨텐츠 수정 */
	function contents_update($data){
		$update_data = array(
		    'cn_subject' => $data['cn_subject'],
		    'cn_content' => $data['cn_content'],
		    'cn_is_view' => $data['cn_is_view'],
		    'cn_sitelink' => $data['cn_sitelink']
		);
		$where = array(
			'cn_idx' => $data['cn_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/* End of file contents_m.php */
	/* Location : ./application/models/config/contents_m.php */
}
?>