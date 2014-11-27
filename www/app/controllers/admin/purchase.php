<?php
class Purchase extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('/admin/Admin_Purchase_model');
		define('WIDGET_SKIN', 'popup');
		define('CSS_SKIN', 'jquery');
		//$this->output->enable_profiler(TRUE);
	}

	function popup() {
		$this->load->library(array('form_validation', 'pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$mb_email  = $param->get('mb_email');
		
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/purchase/popup/page/';
		$config['per_page'] = 25;

		$offset = ($page - 1) * $config['per_page'];
		$result = $this->Admin_Purchase_model->list_result($mb_email, $config['per_page'], $offset);

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->seq = $row['seq'];
			$list[$i]->purchase_dt = substr($row['purchase_dt'], 0, 10);
			$list[$i]->subject = $row['subject'];
			$list[$i]->price = number_format($row['price']);
			$list[$i]->pen_name = $row['pen_name'];
		}
		
		$head = array('title' => '구매 목록');
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
	
			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/purchase/popup', $data);
		widget::run('tail');
	}
}
?>