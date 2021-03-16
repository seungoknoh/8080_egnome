<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contents extends FRONT_Controller {
    
    private $page_idx = null;
    private $page_info = null;
    
	function __construct(){
		parent::__construct();
		
		$this -> load -> model("config/contents_m");
		
		//페이지값
		$this -> page_idx = $this -> uri -> segment(2);
		$this -> page_info = $this -> contents_m -> contents_view_link(urldecode(trim($this -> page_idx)));
		
	}

	public function index(){
		$data = array();
		$data['content'] = $this -> page_info;

		if(isset($data['content'])){
			$data['page'] = 'front/contents/main_v';
			$data['title'] = $this -> page_info-> cn_subject;
		}else{
			$data['page'] = 'front/contents/404_v';
		}
		//$this -> load -> view('front/_layout/container_v', $data);
		$this->_full_view('front/contents/main_v', $data);
	}
}

?>