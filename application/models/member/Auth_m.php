<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_m extends CI_Model{
	private $table = "member";
	
	function __construct(){
		parent::__construct();
	}

	/* 로그인 */
	function login($auth) {
		$sql = "SELECT mb_idx, mb_name, mb_email, mb_level, mb_id, mb_passwd, mb_state 
                FROM {$this->table} 
                WHERE mb_id = '{$auth['mb_id']}' ";
		$query = $this -> db -> query($sql);
		if ($query -> num_rows() > 0) {
		    return $query -> row();
		} else {
			return FALSE;
		}
	}
	
	/* 아이디값으로 회원정보 받기 */
	function getMemberById($mb_id){
	    $sql = "SELECT mb_idx, mb_name, mb_email, mb_level, mb_id, mb_phone, mb_regdate, mb_state FROM {$this->table} WHERE mb_id = '{$mb_id}'";
	    $query = $this -> db -> query($sql);
	   	if ($query -> num_rows() > 0) {
	        return $query -> row();
	    } else {
	        return FALSE;
	    }
	}
	
	//로그인 시간 저장
	function setLatestDateById($mb_id){
	    $sql = "UPDATE {$this->table} SET mb_latestdate = '".date("Y-m-d H:i:s", time())."' WHERE mb_id = '{$mb_id}'";
	    $result = $this->db->query($sql);
	    log_message("debug", "setLatestDateById() - ".$result);
	    return $result;
	}

}

?>