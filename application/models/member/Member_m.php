<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Member_m extends CI_Model{
	private $table = "member";

	function __construct(){
		parent::__construct();
	}
	
	/* 회원 조회 */
	function member_view($mb_idx= ''){
		$sql = "SELECT * FROM ".$this->table." WHERE mb_idx=".$mb_idx;
		$query = $this->db->query($sql);
		$result = $query -> row();
		
		return $result;
	}
	
	/* 회원 리스트 */
	function member_list( $type='' , $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC', $mb_level='' ){
		
		$search_query = "";
		if( $stx != "" && $sfl != "" ){
			$search_query = ' WHERE '.$sfl.' LIKE "%'.$stx.'%" ';
		}else if( $mb_level != "" ){
			$search_query = ' WHERE mb_level = "'.$mb_level.'" ';
		}
		
		$order_query = " ORDER BY mb_idx DESC ";
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
	
	/* 회원 아이디 중복 체크 */
	function member_id_chk($mb_id){
		$sql = "SELECT COUNT(mb_idx) as cnt FROM ".$this -> table." WHERE mb_id='".$mb_id."'";
		$query = $this -> db -> query($sql);
		return $query -> row();
	}

	/* 회원 수정 */
	function member_update($data){
		$update_data = array(
			'mb_email' => $data['mb_email'],
			'mb_is_email' => $data['mb_is_email'],
			'mb_level' => $data['mb_level'],
			'mb_phone' => $data['mb_phone'],
			'mb_is_phone' => $data['mb_is_phone']
		);
		
		//비밀번호재등록
		if( $data['mb_passwd'] != null ){
		    $update_data['mb_passwd'] = $data['mb_passwd'];
		    $update_data['mb_passwddate'] = date("Y-m-d H:i:s", time());
		}
		
		$where = array(
			'mb_idx' => $data['mb_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/* 회원비밀번호 수정 */
	function member_passwd_update($data){
		$update_data['mb_passwd'] = $data['mb_passwd'];
		$update_data['mb_passwddate'] = date("Y-m-d H:i:s", time());
		$where = array(
			'mb_idx' => $data['mb_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/* 회원 탈퇴처리 */
	function member_state($data){
		$update_data = array(
			'mb_state' => $data['mb_state']
		);
		if( $data['mb_state'] == 0 ){
		    $update_data['mb_leavedate'] = date("Y-m-d H:i:s", time());
		}else{
		    $update_data['mb_leavedate'] = "";
		}
		$where = array(
			'mb_idx' => $data['mb_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/* 회원 입력 */
	function member_insert($data){
		$insert_data = array(
			'mb_id' => $data['mb_id'],
			'mb_name' => $data['mb_name'],
		    'mb_passwd' => $data['mb_passwd'],
			'mb_email' => $data['mb_email'],
			'mb_is_email' => $data['mb_is_email'],
			'mb_level' => $data['mb_level'],
			'mb_phone' => $data['mb_phone'],
			'mb_is_phone' => $data['mb_is_phone'],
		    'mb_state' => 1,
		    'mb_regdate' => date("Y-m-d H:i:s", time()),
		    'mb_passwddate' => date("Y-m-d H:i:s", time())
		);
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}
	
	/* 멤버레벨 멤버 리스트 */
	function get_member_level($mb_level, $gt=false){
		$sql = "SELECT mb_idx, mb_id FROM {$this->table} WHERE 1 ";
		if( $gt ){
		    $sql .= " and mb_level >= {$mb_level} ";
		}else{
		    $sql .= " and mb_level = {$mb_level} ";
		}
		$query = $this -> db -> query($sql);
		if( $query -> num_rows() > 0 ){
			return $query -> result();
		}else{
			return false;
		}
	}
	
	/* 회원 삭제 */
	function member_delete($mb_idxs){
		//for( $i = 0; $i < count($mb_idxs); $i++ ){ //회원삭제 수정 sonoh
			//$result =  $this -> db -> delete( $this ->table, array('mb_idx' => $mb_idxs[$i] ));
			$result =  $this -> db -> delete( $this ->table, array('mb_idx' => $mb_idxs));
			if( !$result ){
				return false;
			}
		//}
	}

	/* End of file board_m.php */
	/* Location : ./application/models/board_m.php */
}
?>