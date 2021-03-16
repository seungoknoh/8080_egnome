<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Email_m extends CI_Model{
	private $table = "member_email";

	function __construct(){
		parent::__construct();
	}
	
	//이메일 받는 멤버 리스트
	function member_email_list($data){
	    
	    //회원상태가 정상인 경우만 보냄
	    $search_query = "WHERE 1 and mb_state = 1 ";
	    if( $data['mb_level_start'] != null && $data['mb_level_last'] != null ){
	        $search_query .= " and mb_level >= {$data['mb_level_start']} and mb_level <= {$data['mb_level_last']}";
	    }
	
	    if( $data['mb_is_email'] != null ){
	        $search_query .= " and mb_is_email = 1 ";
	    }
	    
	    $sql = "SELECT mb_email FROM member ".$search_query;
	    log_message("debug", $sql);
	    $query = $this -> db -> query($sql);
	    
	    return $query -> result();
	}

	/* 메일 리스트 */
	function mail_list( $type='' , $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC' ){
		
		$search_query = " WHERE 1 ";
		if( $stx != "" && $sfl != "" ){
			$search_query .= ' and '.$sfl.' LIKE "%'.$stx.'%" ';
		}
		
		$order_query = " ORDER BY me_regdate DESC";
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

	/* 메일 삭제 */
	function mail_delete($data){
        $view = $this->mail_view($data['me_idx']);
        if( isset($view) ){
            delete_editor_image($view->me_content);
            $sql = "DELETE FROM {$this->table} WHERE me_idx=".$data['me_idx'];
            $query = $this -> db -> query($sql);
            return $query;
        }else{
            return false;
        }
	}

	/* 메일 뷰 */
	function mail_view($idx){
		$sql = "SELECT * FROM {$this->table} WHERE me_idx = ".$idx." ";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}

	/* 메일 입력 */
	function mail_insert($data){
		$insert_data = array(
			'me_subject' => $data['me_subject'],
			'me_content' => $data['me_content'],
			'me_regdate' => date("Y-m-d H:i:s")
		);
		//새글입력
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}
	
	/* 메일 수정 */
	function mail_update($data){
		$update_data = array(
			'me_subject' => $data['me_subject'],
			'me_content' => $data['me_content']
		);
		$where = array(
			'me_idx' => $data['me_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/* End of file mail_m.php */
	/* Location : ./application/models/config/mail_m.php */
}
?>