<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MemberAjax extends Adm_Controller {

	function __construct(){
		parent::__construct();
		$this -> load -> model('member/member_m');
		$this -> load -> model('member/level_m');
	}
	
	/* 회원 아이디 중복 체크 */
	function id_chk(){
		$mb_id = $this -> input -> get('mb_id');
		$result = $this -> member_m -> member_id_chk($mb_id);

		echo $result->cnt == 1 ? "" : "TRUE" ;
	}

	/* 회원 권한 출력 */
	function level_list(){
		$page = $this -> input -> get('page');
		$rows = $this -> input -> get('rows');

		$view['list'] = $this -> level_m -> get_level_list('', $page, $rows);
		$view['total'] = $this -> level_m -> get_level_list('count', '', '');

		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($view);
	}

	/* 회원 권한 수정 */
	function level_update(){
		if( $_POST ){
			$le_name = $this -> input -> post('le_name');
			$mb_level = $this -> input -> post('mb_level');
			$msg = "수정되었습니다";
			for( $i = 0; $i < count($mb_level); $i++ ){
				$update_data = array(
					'le_name' => $le_name[$i],
					'mb_level' => $mb_level[$i]
				);
				$result = $this -> level_m -> level_update($update_data);
				if( $result == 0 ){
					$msg = "수정실패하였습니다";
					return;
				}
			}
			$view['msg'] = $msg;
			$this->output->set_header('Content-Type: application/json; charset=utf-8');
			echo json_encode($view);
		}
	}
}
?>