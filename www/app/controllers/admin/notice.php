<?php
class Notice extends CI_Controller {
	function __construct() {
		$CI =& get_instance();
		
		parent::__construct();
		$this->load->model('/admin/Admin_Notice_model');
		define('CSS_SKIN', 'jquery');
		define('WIDGET_SKIN', 'admin');
	}
	
	function user(){
		$this->getNoticeList('U');
	}
	
	function publisher(){
		$this->getNoticeList('P');
	}
	
	/**
	 * Notice 목록
	 */
	function getNoticeList($type){
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchSubject  = $param->get('searchSubject');
		
		$config['suffix'] = $param->output();
		$config['per_page'] = 50;
		
		if($type == 'U'){
			$config['base_url'] = '/admin/notice/user/page/';
		} else if($type == 'U'){
			$config['base_url'] = '/admin/notice/publisher/page/';
		}

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_Notice_model->list_result($searchSubject, $type, $config['per_page'], $offset);
		
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
			$head = array('title' => '사용자 공지사항');
			$title = '사용자 공지사항';
			$action = '/admin/notice/user';
		} else {
			$head = array('title' => '발행자 공지사항');
			$title = '발행자 공지사항';
			$action = '/admin/notice/publisher';
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
		$this->load->view('admin/notice/index', $data);
		widget::run('tail');
	}
	
	/**
	 * Notice 등록 페이지
	 */
	function add(){
		$ss_admin = $this->session->userdata('ss_admin');
		$type = $_GET['type'];
		
		if($type == 'U'){
			$head = array('title' => '사용자 공지사항');
			$title = '사용자 공지사항';
		} else {
			$head = array('title' => '발행자 공지사항');
			$title = '발행자 공지사항';
		}
			
		$data = array(
				'token' => get_token(),
		
				'ss_admin' => $ss_admin,
				'type' => $type,
				'title' => $title
		);
		
		widget::run('head', $head);
		$this->load->view('admin/notice/add', $data);
		widget::run('tail');
	}
	
	/**
	 * Notice 등록 처리
	 */
	function add_action(){
		$ss_admin = $this->session->userdata('ss_admin');
		$type = $_POST['type'];
		
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		
		if(strlen(trim($subject)) < 1 && strlen(trim($content))) {
			$url = $this->config->item('base_url') . "/admin/notice/add?type=" . $type;
			goto_url($url);
		} else {
			$this->Admin_Notice_model->insert_notice($subject, $content, $ss_admin['mb_email'], $type);
			
			alert("등록 되었습니다.", $this->getNoticeListUrl($type));
		}
	}
	
	/**
	 * Notice 상세보기
	 */
	function edit(){
		$seq  = $_GET['seq'];
		$type = $_GET['type'];
		
		if(strlen(trim($seq)) < 1) {
			goto_url($this->getNoticeListUrl($type));
		} else {
			$ss_admin = $this->session->userdata('ss_admin');
			
			if($type == 'U'){
				$head = array('title' => '사용자 공지사항');
				$title = '사용자 공지사항';
			} else {
				$head = array('title' => '발행자 공지사항');
				$title = '발행자 공지사항';
			}
			
			$result = $this->Admin_Notice_model->view_notice($seq);
			
			$data = array(
				'token' => get_token(),
		
				'ss_admin' => $ss_admin,
				'type' => $type,
				'title' => $title,
				'view' => $result[0]
			);
			
			widget::run('head', $head);
			$this->load->view('admin/notice/edit', $data);
			widget::run('tail');
		}
	}
	
	/**
	 * Notice 수정 처리
	 */
	function edit_action(){
		$ss_admin = $this->session->userdata('ss_admin');
		$type = $_POST['type'];
		
		$seq = $_POST['seq'];
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		
		$url = $this->config->item('base_url') . "/admin/notice/edit?seq=".$seq."&type=" . $type;
		
		if(strlen(trim($subject)) < 1 && strlen(trim($content))) {
			goto_url($url);
		} else {
			$this->Admin_Notice_model->update_notice($seq, $subject, $content, $ss_admin['mb_email'], $type);
			
			alert("수정 되었습니다.", $url);
		}
	}
	
	/**
	 * Notice 삭제 처리
	 */
	function delete_action(){
		$type = $_POST['type'];
		
		$seq = $_POST['seq'];
		
		$url = $this->getNoticeListUrl($type);
		
		if(strlen(trim($seq)) < 1) {
			goto_url($url);
		} else {
			$this->Admin_Notice_model->delete_notice($seq, $type);
			
			alert("삭제 되었습니다.", $url);
		}
	}
	
	
	function getNoticeListUrl($type){
		$url = '';
		if($type == 'U') {
			$url = $this->config->item('base_url') . "/admin/notice/user";
		} else {
			$url = $this->config->item('base_url') . "/admin/notice/publisher";
		}
		
		return $url;
	}
}
?>