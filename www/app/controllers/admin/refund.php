<?php
class Refund extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('/admin/Admin_Refund_model');
		$this->load->model('/admin/Admin_Member_model');
		$this->load->model('/admin/Admin_Point_model');
		$this->load->model('/admin/Admin_Pay_model');
	}
	
	
	/**
	 * 발행자 별 판매 현황 > 환급 신청 현황
	 */
	function index() {
		define('WIDGET_SKIN', 'admin');
		define('CSS_SKIN', 'jquery');
		
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
	
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchKeyword  = $param->get('searchKeyword');
	
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/refund/index/page/';
		$config['per_page'] = 50;
	
		$offset = ($page - 1) * $config['per_page'];
	
		$result = $this->Admin_Refund_model->index_result($searchKeyword, $config['per_page'], $offset);
	
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
	
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
	
			$list[$i]->num = $result['total_cnt'] - ($page - 1) * $config['per_page'] - $i;
			$list[$i]->seq = $row['seq'];
			$list[$i]->mb_email = $row['mb_email'];
			$list[$i]->mb_name = $row['mb_name'];
			$list[$i]->mb_point = $row['mb_point'];
			$list[$i]->refund_price = $row['refund_price'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
		}
	
		$head = array('title' => '환급 신청 현황');
		$data = array(
				'token' => get_token(),
	
				'list' => $list,
				'searchKeyword' => $searchKeyword,
	
				'total_cnt' => number_format($result['total_cnt']),
				'paging' => $this->pagination,
		);
	
		widget::run('head', $head);
		$this->load->view('admin/refund/index', $data);
		widget::run('tail');
	}
	
	
	
	/**
	 * 발행자 별 판매 현황 > 환급 신청 현황 > 환급 팝업
	 */
	function pay_popup() {
		define('WIDGET_SKIN', 'popup');
		define('CSS_SKIN', 'jquery');
	
		$seq  = $_GET['seq'];
		$searchEmail  = $_GET['searchEmail'];
	
		$result = $this->Admin_Refund_model->refund_popup_result($seq, $searchEmail);
	
		$head = array('title' => '환급');
		$data = array(
				'token' => get_token(),
	
				'data' => $result[0],
				'seq' => $seq,
				'searchEmail' => $searchEmail
		);
	
		widget::run('head', $head);
		$this->load->view('admin/refund/pay_popup', $data);
		widget::run('tail');
	}
	
	function refund_complete(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$seq = $_POST['seq'];
			$mb_email = $_POST['mb_email'];
			$pay = $_POST['pay'];
			$charge = $_POST['charge'];
			$point = $_POST['refund_price'];
			$mb_point = $_POST['mb_point'];
			$status = $_POST['status'];
			$comment = $_POST['comment'];
			$rest_point = $mb_point - $point;
			
			// 환급완료처리
			$this->Admin_Refund_model->update_refund_complete($seq, $mb_email, $pay, $charge, $status);
			
			// 지급목록에 등록
			$this->Admin_Pay_model->pay_insert($seq, $mb_email, $pay, $charge, $comment);
			
			// 회원 포인트 차감 또는 증감
			//$this->Admin_Member_model->update_member_point($point, $mb_email);
			
			// 회원 포인트 차감 또는 증감 이력
			$this->Admin_Point_model->insert_point($mb_email, $comment, -$point, $comment, $rest_point);
			
		}
	}
}
?>