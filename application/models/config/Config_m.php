<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Config_m extends CI_Model{
	private $table = "config";
	
	function __construct(){
		parent::__construct();
	}

	function get_config(){
		$sql = "SELECT * FROM {$this->table}";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}

	function config_update($data){
		$update_data = array(
			'cf_title' => $data['cf_title'],
			'cf_admin' => $data['cf_admin'],
			'cf_admin_email' => $data['cf_admin_email'],
			'cf_admin_name' => $data['cf_admin_name'],
			'cf_addr1' => $data['cf_addr1'],
			'cf_addr2' => $data['cf_addr2'],
			'cf_zip' => $data['cf_zip'],
			'cf_tel' => $data['cf_tel'],
			'cf_fax' => $data['cf_fax'],
		    'cf_email_auth' => $data['cf_email_auth'],
			'cf_is_phone' => $data['cf_is_phone'],
			'cf_privacy' => $data['cf_privacy'],
			'cf_service' => $data['cf_service'],
		    'cf_url' => $data['cf_url']
		);
		$where = array();
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}
}

?>