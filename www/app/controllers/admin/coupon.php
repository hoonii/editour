<?php
class Coupon extends CI_Controller {
	function __construct() {
		$CI =& get_instance();
		
		parent::__construct();
		$this->load->model('/admin/Admin_Coupon_model');
	}
	
	/**
	 * 쿠폰 목록
	 */
	function index(){
		define('CSS_SKIN', 'jquery');
		define('WIDGET_SKIN', 'admin');
		
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		
		$config['suffix'] = $param->output();
		$config['per_page'] = 50;
		$config['base_url'] = '/admin/coupon/index/page/';

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_Coupon_model->list_result($config['per_page'], $offset);
		
		$config['total_rows'] = $this->Admin_Coupon_model->list_count();
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->num = $config['total_rows'] - ($page - 1) * $config['per_page'] - $i;
			$list[$i]->seq = $row['seq'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
			$list[$i]->name = $row['name'];
			$list[$i]->code = $row['code'];
			$list[$i]->s_dt = substr($row['s_dt'], 0, 10);
			$list[$i]->e_dt = substr($row['e_dt'], 0, 10);
			$list[$i]->point = number_format($row['point']);
			$list[$i]->total = number_format($row['total']);
			$list[$i]->use = number_format($row['use']);
		}
		
		$head = array('title' => '발행 쿠폰 현황');
		
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
	
			'total_cnt' => number_format($config['total_rows']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/coupon/index', $data);
		widget::run('tail');
	}
	
	/**
	 * 쿠폰 등록 페이지
	 */
	function add_popup(){
		define('CSS_SKIN', 'jquery');
		define('WIDGET_SKIN', 'popup');
		
		$head = array('title' => '쿠폰등록');
		
		$code = "";
		for($i=0; $i<4; $i++){
			$code .= rand(1000, 9999);
			if(($i+1) < 4 ){
				$code .= "-";
			}
		}
		
		while(true){
			$count = $this->Admin_Coupon_model->coupon_code_search($code);
			if($count < 1){
				break;
			} else {
				$code = "";
				for($i=0; $i<4; $i++){
					$code .= rand(1000, 9999);
					if(($i+1) < 4 ){
						$code .= "-";
					}
				}
			}
		}
		
		$data = array(
				'token' => get_token(),
				
				'code' => $code
		);
		
		widget::run('head', $head);
		$this->load->view('admin/coupon/add_popup', $data);
		widget::run('tail');
	}
	
	/**
	 * 쿠폰 등록 처리
	 */
	function add_submit(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$code = $_POST['code'];
			$name = $_POST['name'];
			$total = $_POST['total'];
			$point = $_POST['point'];
			$s_dt = $_POST['s_dt'];
			$e_dt = $_POST['e_dt'];
			
			$this->Admin_Coupon_model->insert_coupon($name, $code, $s_dt, $e_dt, $total, $point);
		}
	}
}
?>