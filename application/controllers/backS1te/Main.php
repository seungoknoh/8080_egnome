<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends ADM_Controller {
	function __construct(){
		parent::__construct();
		$this -> load -> model('board/board_m');
		$this -> load -> model('config/config_m');
		$this -> load -> model('board/latest_m');
	}

	public function index(){
		//$data['lastestList'] = $this -> board_m -> board_list('','',0,5);
		$data['lastestList'] = $this -> latest_m -> admin_total_list(10, true);


		//스킨
		$data['page'] = "admin/main/main_v";
		$this -> load -> view('admin/_layout/container_v', $data);
	}	
}
?>