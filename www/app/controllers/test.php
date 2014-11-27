<?php
class Test extends CI_Controller {
	
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		echo "index";
		
		widget::run('head');
		$this->load->view('test/index', "");
		widget::run('tail');
	}
	
	function view(){
		echo "view";
	}
}
?>