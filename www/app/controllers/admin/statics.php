<?php
class Statics extends CI_Controller {
	function __construct() {
		$CI =& get_instance();
		
		parent::__construct();
		$this->load->model('/admin/Admin_Statics_model');
		define('CSS_SKIN', 'jquery');
	}
	
	
	/**
	 * 통계 index
	 */
	function index(){
		define('WIDGET_SKIN', 'admin');
		
		$head = array('title' => '통계');
			
		$data = array(
			'token' => get_token()
		);
		
		widget::run('head', $head);
		$this->load->view('admin/statics/index', $data);
		widget::run('tail');
	}
	
	
	/**
	 * 일간 통계 그래프
	 */
	function day_view(){
		$head = array('title' => '통계');
		
		$s1 = array("2","6","7","10");
		$x = array("2014-11-01","2014-11-02","2014-11-03","2014-11-04");
			
		$data = array(
			'token' => get_token(),
				
			's1' => implode(",", $s1),
			'x' => implode(",", $x)
		);
		
		$this->load->view('admin/statics/day_view', $data);
	}
	
	
	/**
	 * 주간 통계 그래프
	 */
	function week_view(){
		$head = array('title' => '통계');
		
		$s1 = array("2","6","7","10");
		$x = array("2014-11-01","2014-11-02","2014-11-03","2014-11-04");
			
		$data = array(
			'token' => get_token(),
				
			's1' => implode(",", $s1),
			'x' => implode(",", $x)
		);
		
		$this->load->view('admin/statics/week_view', $data);
	}
	
	
	/**
	 * 월간 통계 그래프
	 */
	function month_view(){
		$head = array('title' => '통계');
		
		$s1 = array("2","6","7","10");
		$x = array("2014-11-01","2014-11-02","2014-11-03","2014-11-04");
			
		$data = array(
			'token' => get_token(),
				
			's1' => implode(",", $s1),
			'x' => implode(",", $x)
		);
		
		$this->load->view('admin/statics/month_view', $data);
	}
	
	
	/**
	 * 전체누적 통계 그래프
	 */
	function all_view(){
		$head = array('title' => '통계');
		
		$s1 = array("2","6","7","10");
		$x = array("2014-11-01","2014-11-02","2014-11-03","2014-11-04");
			
		$data = array(
			'token' => get_token(),
				
			's1' => implode(",", $s1),
			'x' => implode(",", $x)
		);
		
		$this->load->view('admin/statics/all_view', $data);
	}
}
?>