<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_log_m extends CI_Model{
	private $table = "member_login_log";
	
	function __construct(){
		parent::__construct();
	}
	
	/* 회원 로그인 로그 입력 */
	function login_log_insert($data){
	    $insert_data = array(
	        'mll_success' => $data['mll_success'],
	        'mb_idx' => $data['mb_idx'],
	        'mb_id' => $data['mb_id'],
	        'mll_ip' => $data['mll_ip'],
	        'mll_useragent' => $data['mll_useragent'],
			'mll_msg' => $data['mll_msg'],
			'mll_url' => $data['mll_url'],
	        'mll_regdate' => date("Y-m-d H:i:s")
	    );
	    $result = $this->db->insert($this->table, $insert_data);
	    return $result;
	}
}

?>