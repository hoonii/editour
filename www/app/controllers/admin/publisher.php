<?php
class Publisher extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('/admin/Admin_Publisher_model');
	}
	
	
	function index() {
		define('WIDGET_SKIN', 'admin');
		define('CSS_SKIN', 'jquery');
		
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchEmail  = $param->get('searchEmail');
		
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/publisher/index/page/';
		$config['per_page'] = 50;

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_Publisher_model->list_result($searchEmail, $config['per_page'], $offset);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->seq = $row['seq'];
			$list[$i]->mb_email = $row['mb_email'];
			$list[$i]->pen_name = $row['pen_name'];
			$list[$i]->mb_name = $row['mb_name'];
			$list[$i]->pub_cnt = $row['pub_cnt'];
			$list[$i]->cumulative_price = $row['cumulative_price'];
			$list[$i]->cumulative_download = $row['cumulative_download'];
		}
		
		$head = array('title' => '발행자 목록');
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
			'searchEmail' => $searchEmail,
	
			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/publisher/index', $data);
		widget::run('tail');
	}
	
	
	
	
	/**
	 * 발행자 별 판매현황
	 */
	function sale() {
		define('WIDGET_SKIN', 'admin');
		define('CSS_SKIN', 'jquery');
		
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
	
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchKeyword  = $param->get('searchKeyword');
	
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/publisher/sale/page/';
		$config['per_page'] = 50;
	
		$offset = ($page - 1) * $config['per_page'];
	
		$result = $this->Admin_Publisher_model->sale_result($searchKeyword, $config['per_page'], $offset);
	
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
	
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
	
			$list[$i]->num = $result['total_cnt'] - ($page - 1) * $config['per_page'] - $i;
			$list[$i]->seq = $row['seq'];
			$list[$i]->mb_email = $row['mb_email'];
			$list[$i]->pen_name = $row['pen_name'];
			$list[$i]->mb_name = $row['mb_name'];
			$list[$i]->sale_price = $row['sale_price'];
			$list[$i]->mb_point = $row['mb_point'];
			$list[$i]->refund_price = $row['refund_price'];
			$list[$i]->pay = $row['pay'];
			$list[$i]->charge = $row['charge'];
		}
	
		$head = array('title' => '발행자 별 판매 현황');
		$data = array(
				'token' => get_token(),
	
				'list' => $list,
				'searchKeyword' => $searchKeyword,
	
				'total_cnt' => number_format($result['total_cnt']),
				'paging' => $this->pagination,
		);
	
		widget::run('head', $head);
		$this->load->view('admin/publisher/sale', $data);
		widget::run('tail');
	}
	
	
	
	/**
	 * 발행자별 판매 현황 > 개인 환급 이력 팝업
	 */
	function refund_popup() {
		define('WIDGET_SKIN', 'popup');
		define('CSS_SKIN', 'jquery');
	
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
	
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchEmail  = $param->get('searchEmail');
	
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/publisher/refund_popup/page/';
		$config['per_page'] = 25;
	
		$offset = ($page - 1) * $config['per_page'];
	
		$result = $this->Admin_Publisher_model->refund_list_popup_result($searchEmail, $config['per_page'], $offset);
	
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
	
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
	
			$list[$i]->seq = $row['seq'];
			$list[$i]->mb_email = $row['mb_email'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
			$list[$i]->refund_price = $row['refund_price'];
			$list[$i]->pay = $row['pay'];
			$list[$i]->charge = $row['charge'];
			$list[$i]->status = $row['status'];
			$list[$i]->mb_name = $row['mb_name'];
		}
	
		$head = array('title' => '개인 환급 이력');
		$data = array(
				'token' => get_token(),
	
				'list' => $list,
				'searchEmail' => $searchEmail,
	
				'total_cnt' => number_format($result['total_cnt']),
				'paging' => $this->pagination,
		);
	
		widget::run('head', $head);
		$this->load->view('admin/publisher/refund_popup', $data);
		widget::run('tail');
	}
}
?>