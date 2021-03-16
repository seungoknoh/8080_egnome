<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Popup_m extends CI_Model{
	private $table = "popup";

	function __construct(){
		parent::__construct();
	}

	/* 게시물 리스트 */
	function popup_list( $type='' , $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC' ){
		
		$search_query = " WHERE 1 ";
		if( $stx != "" && $sfl != "" ){
			$search_query .= ' and '.$sfl.' LIKE "%'.$stx.'%" ';
		}
		
		$order_query = " ORDER BY po_regdate DESC";
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

	/* 팝업 삭제 */
	function popup_delete($data){
	    $view = $this->popup_view($data['po_idx']);
	    if( isset($view) ){
	        //팝업에 등록된 이미지 삭제
	        delete_editor_image($view->po_content);
	        $sql = "DELETE FROM {$this->table} WHERE po_idx=".$data['po_idx'];
	        $query = $this -> db -> query($sql);
	        return $query;
	    }else{
	        return false;
	    }
	}

	/* 팝업 뷰 */
	function popup_view($idx){
		$sql = "SELECT * FROM ".$this->table." WHERE po_idx = ".$idx." ";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}

	/* 팝업 입력 */
	function popup_insert($data){
		$insert_data = array(
			'po_subject' => $data['po_subject'],
			'po_content' => $data['po_content'],
			'po_left' => $data['po_left'],
			'po_top' => $data['po_top'],
			'po_width' => $data['po_width'],
			'po_height' => $data['po_height'],
			'po_hour' => $data['po_hour'],
			'po_begin_time' => $data['po_begin_time'],
			'po_end_time' => $data['po_end_time'],
			'po_is_view' => $data['po_is_view'],
			'po_color' => $data['po_color'],
			'po_is_target' => $data['po_is_target'],
			'po_link' => $data['po_link'],
			'po_regdate' => date("Y-m-d H:i:s")
		);

		//새글입력
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}
	
	/* 팝업 수정 */
	function popup_update($data){
		$update_data = array(
			'po_subject' => $data['po_subject'],
			'po_content' => $data['po_content'],
			'po_left' => $data['po_left'],
			'po_top' => $data['po_top'],
			'po_width' => $data['po_width'],
			'po_height' => $data['po_height'],
			'po_hour' => $data['po_hour'],
			'po_begin_time' => $data['po_begin_time'],
			'po_end_time' => $data['po_end_time'],
			'po_is_view' => $data['po_is_view'],
			'po_color' => $data['po_color'],
			'po_is_target' => $data['po_is_target'],
			'po_link' => $data['po_link']
		);
		$where = array(
			'po_idx' => $data['po_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/*  실행 팝업 리스트 */
	function popup_open_list($type=''){
		$sql = "SELECT * FROM {$this->table} WHERE now() BETWEEN po_begin_time AND po_end_time AND po_is_view = 1";
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

	/* End of file popup_m.php */
	/* Location : ./application/models/config/popup_m.php */
}
?>