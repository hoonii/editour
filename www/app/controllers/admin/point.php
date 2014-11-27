<?php
class Point extends CI_Controller {
	function __construct() {
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
	}
	
	function publisher(){
		$this->getPoint('publisher');
	} 

	function user() {
		$this->getPoint('user');
	}
	
	function getPoint($type) {
		$this->load->model('/admin/Admin_Point_model');
		define('WIDGET_SKIN', 'popup');
		define('CSS_SKIN', 'jquery');
		
		$this->load->library(array('form_validation', 'pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$config = array(
			array('field'=>'mb_id', 'label'=>'아이디', 'rules'=>'trim|required|max_length[20]|xss_clean'),
			array('field'=>'po_content', 'label'=>'포인트내용', 'rules'=>'trim|required'),
			array('field'=>'po_point', 'label'=>'포인트', 'rules'=>'trim|required|numeric')
		);

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			
 			$param =& $this->querystring;
			$page = $this->uri->segment(5, 1);
			$sst  = $param->get('sst', 'po_id');
			$sod  = $param->get('sod', 'asc');
			$sfl  = $param->get('sfl');
			$stx  = $param->get('stx');
			
			$config['suffix'] = $param->output();
			$config['per_page'] = 25;
			
			if($type == 'publisher'){
				$config['base_url'] = '/admin/point/publisher/page/';
			} else {
				$config['base_url'] = '/admin/point/user/page/';
			}

			$offset = ($page - 1) * $config['per_page'];
			$result = $this->Admin_Point_model->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);

			$config['total_rows'] = $result['total_cnt'];
			$this->pagination->initialize($config);

			if ($sfl == 'mb_id' && $stx && $result['total_cnt'] > 0) {
				$total_pnt = $stx.' 님 포인트 합계 : ' . number_format($result['total_pnt']) . '점';
				$stx_mb_id = TRUE;
			} else
				$total_pnt = '전체 포인트 합계 : ' . number_format($result['total_pnt']) . '점';

			$list = array();
			foreach ($result['qry'] as $i => $row) {
				$list[$i] = new stdClass();
				
				if ($this->config->item('cf_use_nick'))
					$list[$i]->mb_nick = $row['mb_nick'];

				$list[$i]->id = $row['po_id'];
				$list[$i]->datetime = substr($row['po_datetime'], 0, 10);
				$list[$i]->content = $row['po_content'];
				$list[$i]->point = number_format($row['po_point']);
				$list[$i]->mb_name = $row['mb_name'];
				$list[$i]->mb_point = number_format($row['mb_point']);
				$list[$i]->etc = $row['po_etc'];
				$list[$i]->rest_point = number_format($row['po_rest_point']);
			}
			
			if($type == 'publisher'){
				$head = array('title' => '포인트 변경 이력');
			} else {
				$head = array('title' => '포인트 변동 이력');
			}

			
			$data = array(
				'token' => get_token(),

				'list' => $list,
				'use_nick' => $this->config->item('cf_use_nick'),
		
				'sfl' => $sfl,
				'stx' => $stx,
				'stx_mb_id' => (isset($stx_mb_id)) ? $stx : '',

				'total_cnt' => number_format($result['total_cnt']),
				'total_pnt' => $total_pnt,
				'paging' => $this->pagination,

				'sort_mb_id' => $param->sort('mb_id'),
				'sort_po_datetime' => $param->sort('po_datetime'),
				'sort_po_content' => $param->sort('po_content'),
				'sort_po_point' => $param->sort('po_point')
			);
			
			widget::run('head', $head);
			
			if($type == 'publisher'){
				$this->load->view('admin/point/publisher', $data);
			} else {
				$this->load->view('admin/point/user', $data);
			}
			
			widget::run('tail');
		}
		else {
			check_token();
			$member = unserialize(MEMBER);
			$mb_id = $this->input->post('mb_id');
			$po_point = $this->input->post('po_point');
			$mb = $this->Basic_model->get_member($mb_id, 'mb_id,mb_point');

			if (!isset($mb['mb_id']))
				alert('존재하는 회원아이디가 아닙니다.');

			if (($po_point < 0) && ($po_point * (-1) > $mb['mb_point']))
				alert('포인트를 깎는 경우 현재 포인트보다 작으면 안됩니다.');

			$this->load->model('Admin_Point_model');
			$this->Admin_Point_model->insert($mb_id, $po_point, $this->input->post('po_content'), '@passive', $mb_id, $member['mb_id'].'-'.uniqid(''));

			goto_url('/admin/point/index');
		}
	}
}
?>