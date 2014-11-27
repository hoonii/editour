<?php
class News extends CI_Controller {
	function __construct() {
		$CI =& get_instance();
		
		parent::__construct();
		$this->load->model('/admin/Admin_News_model');
		define('CSS_SKIN', 'jquery');
		define('WIDGET_SKIN', 'admin');
	}
	
	/**
	 * News 목록
	 */
	function index(){
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$searchSubject  = $param->get('searchSubject');
		
		$config['suffix'] = $param->output();
		$config['per_page'] = 50;
		$config['base_url'] = '/admin/news/index/';

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_News_model->list_result($searchSubject, $config['per_page'], $offset);
		
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
		}
		
		$title = '';
		$action = '';
		
		$head = array('title' => '보도자료');
		$title = '보도자료';
			
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
			'searchSubject' => $searchSubject,
			'title' => $title,
	
			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/news/index', $data);
		widget::run('tail');
	}
	
	/**
	 * News 등록 페이지
	 */
	function add(){
		$ss_admin = $this->session->userdata('ss_admin');
		
		$head = array('title' => '보도자료');
		$title = '보도자료';
			
		$data = array(
				'token' => get_token(),
		
				'ss_admin' => $ss_admin,
				'title' => $title
		);
		
		widget::run('head', $head);
		$this->load->view('admin/news/add', $data);
		widget::run('tail');
	}
	
	/**
	 * News 등록 처리
	 */
	function add_action(){
		$ss_admin = $this->session->userdata('ss_admin');
		
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		
		if(strlen(trim($subject)) < 1 && strlen(trim($content))) {
			$url = $this->config->item('base_url') . "/admin/news/add";
			goto_url($url);
		} else {
			$this->Admin_News_model->insert_news($subject, $content, $ss_admin['mb_email']);
			$url = $this->config->item('base_url') . "/admin/news/index";
			alert("등록 되었습니다.", $url);
		}
	}
	
	/**
	 * News 상세보기
	 */
	function edit(){
		$seq  = $_GET['seq'];
		
		if(strlen(trim($seq)) < 1) {
			goto_url($this->config->item('base_url') . "/admin/news/index");
		} else {
			$ss_admin = $this->session->userdata('ss_admin');
			
			$head = array('title' => '보도자료');
			$title = '보도자료';
			
			$result = $this->Admin_News_model->view_news($seq);
			
			$data = array(
				'token' => get_token(),
		
				'ss_admin' => $ss_admin,
				'title' => $title,
				'view' => $result[0]
			);
			
			widget::run('head', $head);
			$this->load->view('admin/news/edit', $data);
			widget::run('tail');
		}
	}
	
	/**
	 * News 수정 처리
	 */
	function edit_action(){
		$ss_admin = $this->session->userdata('ss_admin');
		
		$seq = $_POST['seq'];
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		
		$url = $this->config->item('base_url') . "/admin/news/edit?seq=".$seq;
		
		if(strlen(trim($subject)) < 1 && strlen(trim($content))) {
			goto_url($url);
		} else {
			$this->Admin_News_model->update_news($seq, $subject, $content, $ss_admin['mb_email']);
			
			alert("수정 되었습니다.", $url);
		}
	}
	
	/**
	 * News 삭제 처리
	 */
	function delete_action(){
		$seq = $_POST['seq'];
		
		$url = $url = $this->config->item('base_url') . "/admin/news/index";
		
		if(strlen(trim($seq)) < 1) {
			goto_url($url);
		} else {
			$this->Admin_News_model->delete_news($seq);
			
			alert("삭제 되었습니다.", $url);
		}
	}
}
?>