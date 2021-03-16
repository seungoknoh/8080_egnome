<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	메인페이지 모델
*/
class MainPage_m extends CI_Model{
	private $table = "mainpage";
	
	function __construct(){
		parent::__construct();
	}
 
	function get_mainPage(){
		$sql = "SELECT * FROM {$this->table}";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}

	function update_mainPage($data){
		$update_data = array(
			'mp_youtube' => $data['mp_youtube'],
			'mp_visual' => $data['mp_visual'],
			'mp_poll' => $data['mp_poll'],
		);
		$where = array();
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}   
}
?>