<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Header_init  {
	private $CI;

	function __construct(){
		$this->CI =& get_instance();
		
		//Check if session lib is loaded or not
		if(!isset($this->CI->session)){
			//If not loaded, then load it here
			$this->CI->load->library('session');
		}
	}

	public function init(){
		$CI =& get_instance();
		//print_r( $this->CI -> session -> userdata );
	}
}
?>