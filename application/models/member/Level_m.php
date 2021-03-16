<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Level_m extends CI_Model{
	private $table = "member_level";
	
	function __construct(){
		parent::__construct();
	}

	//회원레벨
	function level_list(){
		$sql = "SELECT count(mb_level) as cnt , ml_name , ml_idx FROM {$this->table} as level RIGHT JOIN member on level.ml_idx = member.mb_level GROUP BY member.mb_level ORDER BY level.ml_idx desc"; 
		$query = $this -> db -> query($sql);
		$result = $query -> result();
		return $result;
	}

	//회원권한 리스트
	function get_level_list($type='', $page='', $rows=''){
		$limit_query = "";
		if( $page != '' ){
			$limit_query = " limit ".(($page-1) * $rows).", ".$rows;
		}
		$sql = "select ml_idx, ml_name from ".$this->table." order by ml_idx asc ".$limit_query;
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

	//회원권한 이름
	function get_level_name($mb_level){
		$sql = "SELECT ml_name FROM ".$this->table." WHERE ml_idx = '".$mb_level."'";
		$query = $this -> db -> query($sql);
		
		if( $query -> num_rows() > 0 ){
			return $query -> row()->ml_name;
		}else{
			return "no name";
		}
	}
    
	//회원권한 업데이트
	function level_update($data){
	    
	    for($i=0;$i<count($data['ml_idx']);$i++){
	        $sql = "UPDATE {$this->table} SET ml_name = '{$data['ml_name'][$i]}' WHERE ml_idx = {$data['ml_idx'][$i]} ";
	        $this->db->query($sql);
	        
	        log_message("debug", $sql);
	    }
        return true;
	}
}

?>