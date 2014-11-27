<?php
class Faq extends CI_Controller {
	function __construct() {
		$CI =& get_instance();
		
		parent::__construct();
		$this->load->model('/admin/Admin_Faq_model');
		define('CSS_SKIN', 'jquery');
		define('WIDGET_SKIN', 'admin');
	}
	
	function user(){
		$this->getFaqList('U');
	}
	
	function publisher(){
		$this->getFaqList('P');
	}
	
	/**
	 * FAQ 목록
	 */
	function getFaqList($type){
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchSubject  = $param->get('searchSubject');
		
		$config['suffix'] = $param->output();
		$config['per_page'] = 50;
		
		if($type == 'U'){
			$config['base_url'] = '/admin/faq/user/page/';
		} else if($type == 'U'){
			$config['base_url'] = '/admin/faq/publisher/page/';
		}

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_Faq_model->list_result($searchSubject, $type, $config['per_page'], $offset);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->num = $result['total_cnt'] - ($page - 1) * $config['per_page'] - $i;
			$list[$i]->seq = $row['seq'];
			$list[$i]->subject = $row['subject'];
			$list[$i]->content = $row['content'];
			$list[$i]->mb_name = $row['mb_name'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
			$list[$i]->update_dt = substr($row['update_dt'], 0, 10);
			$list[$i]->read_cnt = $row['read_cnt'];
			$list[$i]->type = $type;
		}
		
		$title = '';
		$action = '';
		
		if($type == 'U'){
			$head = array('title' => '사용자 FAQ');
			$title = '사용자 FAQ';
			$action = '/admin/faq/user';
		} else {
			$head = array('title' => '발행자 FAQ');
			$title = '발행자 FAQ';
			$action = '/admin/faq/publisher';
		}
			
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
			'searchSubject' => $searchSubject,
			'type' => $type,
			'title' => $title,
			'action' => $action,
	
			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/faq/index', $data);
		widget::run('tail');
	}
	
	/**
	 * FAQ 등록 페이지
	 */
	function add(){
		$ss_admin = $this->session->userdata('ss_admin');
		$type = $_GET['type'];
		
		if($type == 'U'){
			$head = array('title' => '사용자 FAQ');
			$title = '사용자 FAQ';
		} else {
			$head = array('title' => '발행자 FAQ');
			$title = '발행자 FAQ';
		}
			
		$data = array(
				'token' => get_token(),
		
				'ss_admin' => $ss_admin,
				'type' => $type,
				'title' => $title
		);
		
		widget::run('head', $head);
		$this->load->view('admin/faq/add', $data);
		widget::run('tail');
	}
	
	/**
	 * FAQ 등록 처리
	 */
	function add_action(){
		$ss_admin = $this->session->userdata('ss_admin');
		$type = $_POST['type'];
		
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		
		if(strlen(trim($subject)) < 1 && strlen(trim($content))) {
			$url = $this->config->item('base_url') . "/admin/faq/add?type=" . $type;
			goto_url($url);
		} else {
			$this->Admin_Faq_model->insert_faq($subject, $content, $ss_admin['mb_email'], $type);
			
			alert("등록 되었습니다.", $this->getFaqListUrl($type));
		}
	}
	
	/**
	 * FAQ 상세보기
	 */
	function edit(){
		$seq  = $_GET['seq'];
		$type = $_GET['type'];
		
		if(strlen(trim($seq)) < 1) {
			goto_url($this->getFaqListUrl($type));
		} else {
			$ss_admin = $this->session->userdata('ss_admin');
			
			if($type == 'U'){
				$head = array('title' => '사용자 FAQ');
				$title = '사용자 FAQ';
			} else {
				$head = array('title' => '발행자 FAQ');
				$title = '발행자 FAQ';
			}
			
			$result = $this->Admin_Faq_model->view_faq($seq);
			
			$data = array(
				'token' => get_token(),
		
				'ss_admin' => $ss_admin,
				'type' => $type,
				'title' => $title,
				'view' => $result[0]
			);
			
			widget::run('head', $head);
			$this->load->view('admin/faq/edit', $data);
			widget::run('tail');
		}
	}
	
	/**
	 * FAQ 수정 처리
	 */
	function edit_action(){
		$ss_admin = $this->session->userdata('ss_admin');
		$type = $_POST['type'];
		
		$seq = $_POST['seq'];
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		
		$url = $this->config->item('base_url') . "/admin/faq/edit?seq=".$seq."&type=" . $type;
		
		if(strlen(trim($subject)) < 1 && strlen(trim($content))) {
			goto_url($url);
		} else {
			$this->Admin_Faq_model->update_faq($seq, $subject, $content, $ss_admin['mb_email'], $type);
			
			alert("수정 되었습니다.", $url);
		}
	}
	
	/**
	 * FAQ 삭제 처리
	 */
	function delete_action(){
		$type = $_POST['type'];
		
		$seq = $_POST['seq'];
		
		$url = $this->getFaqListUrl($type);
		
		if(strlen(trim($seq)) < 1) {
			goto_url($url);
		} else {
			$this->Admin_Faq_model->delete_faq($seq, $type);
			
			alert("삭제 되었습니다.", $url);
		}
	}
	
	
	function getFaqListUrl($type){
		$url = '';
		if($type == 'U') {
			$url = $this->config->item('base_url') . "/admin/faq/user";
		} else {
			$url = $this->config->item('base_url') . "/admin/faq/publisher";
		}
		
		return $url;
	}
}
?>