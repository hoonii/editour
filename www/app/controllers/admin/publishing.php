<?php
class Publishing extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('/admin/Admin_Publishing_model');
		define('CSS_SKIN', 'jquery');
	}
	
	/**
	 * 발행물 목록
	 */
	function index(){
		define('WIDGET_SKIN', 'admin');
		
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchSubject  = $param->get('searchSubject');
		$searchStatus  = strlen(trim($param->get('searchStatus'))) < 1 ? 'ALL' : $param->get('searchStatus');
		$orderBy  = strlen(trim($param->get('orderBy'))) < 1 ? '1' : $param->get('orderBy');
		
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/publishing/index/page/';
		$config['per_page'] = 50;

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_Publishing_model->list_result($searchSubject, $orderBy, $searchStatus, $config['per_page'], $offset);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->num = $result['total_cnt'] - ($page - 1) * $config['per_page'] - $i;
			$list[$i]->seq = $row['seq'];
			$list[$i]->subject = $row['subject'];
			$list[$i]->mb_name = $row['mb_name'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
			$list[$i]->price_division = $row['price_division'];
			$list[$i]->price = $row['price'];
			$list[$i]->status = $row['status'];
		}
		
		$head = array('title' => '발행물 관리');
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
			'searchSubject' => $searchSubject,
			'searchStatus' => $searchStatus,
			'orderBy' => $orderBy,
	
			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/publishing/index', $data);
		widget::run('tail');
	}
	
	/**
	 * 발행물 관리 상세보기
	 */
	function view(){
		define('WIDGET_SKIN', 'popup');
	
		$seq  = $_GET['seq'];
	
		$result = $this->Admin_Publishing_model->view_result($seq);
		$keywords = $this->Admin_Publishing_model->keyword($seq);
		
		$head = array('title' => '발행물 상세');
		$data = array(
			'seq' => $seq,
			'view' => $result[0],
			'keywords' => $keywords
		);
	
		widget::run('head', $head);
		$this->load->view('admin/publishing/view', $data);
		widget::run('tail');
	}
	
	/**
	 * 발행물 관리 반려 사유 입력 창
	 */
	function return_reason(){
		define('WIDGET_SKIN', 'popup');
		
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$seq  = $_GET['seq'];
		
			$head = array('title' => '반려사유 입력');
			$data = array(
				'seq' => $seq
			);
		
			widget::run('head', $head);
			$this->load->view('admin/publishing/return_reason', $data);
			widget::run('tail');
		} else {
			$seq  = $_POST['seq'];
			$status  = $_POST['status'];
			$return_reason  = $_POST['return_reason'];
			
			$result = $this->Admin_Publishing_model->update_reason($seq, $status, $return_reason);
	
			echo $result;
		}
	}
	
	/**
	 * 발행물 목록(팝업)
	 */
	function popup() {
		define('WIDGET_SKIN', 'popup');
		
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchEmail  = $param->get('searchEmail');
		
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/publishing/popup/page/';
		$config['per_page'] = 25;

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_Publishing_model->popup_list_result($searchEmail, $config['per_page'], $offset);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->seq = $row['seq'];
			$list[$i]->subject = $row['subject'];
			$list[$i]->price_division = $row['price_division'];
			$list[$i]->price = $row['price'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
		}
		
		$head = array('title' => '발행물 목록');
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
			'searchEmail' => $searchEmail,
	
			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/publishing/popup', $data);
		widget::run('tail');
	}
	
	
	/**
	 * 발행물 상태값 수정
	 */
	function update_status(){
		$result = $this->Admin_Publishing_model->update_status($_POST['seq'], $_POST['status']);
	
		echo $result;
	}
	
	
	
	
	
	
	/**
	 * 발행물 목록
	 */
	function sale(){
		define('WIDGET_SKIN', 'admin');
	
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
	
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchKeyword  = $param->get('searchKeyword');
	
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/publishing/sale/page/';
		$config['per_page'] = 50;
	
		$offset = ($page - 1) * $config['per_page'];
	
		$result = $this->Admin_Publishing_model->sale_result($searchKeyword, $config['per_page'], $offset);
	
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
	
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
	
			$list[$i]->num = $result['total_cnt'] - ($page - 1) * $config['per_page'] - $i;
			$list[$i]->seq = $row['seq'];
			$list[$i]->subject = $row['subject'];
			$list[$i]->mb_email = $row['mb_email'];
			$list[$i]->mb_name = $row['mb_name'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
			$list[$i]->price_division = $row['price_division'];
			$list[$i]->price = $row['price'];
			$list[$i]->status = $row['status'];
			$list[$i]->sale_cnt = $row['sale_cnt'];
			$list[$i]->sale_price = $row['sale_price'];
		}
	
		$head = array('title' => '발행물 별 판매 현황');
		$data = array(
				'token' => get_token(),
	
				'list' => $list,
				'searchKeyword' => $searchKeyword,
	
				'total_cnt' => number_format($result['total_cnt']),
				'paging' => $this->pagination,
		);
	
		widget::run('head', $head);
		$this->load->view('admin/publishing/sale', $data);
		widget::run('tail');
	}
}
?>