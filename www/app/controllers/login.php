<?php
class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		define('WIDGET_SKIN', 'main');
	}

	function index($msg=FALSE) {
		if ($this->input->post('url')){
			$url = $this->input->post('url');
		} else {
			$url = (is_numeric($msg)) ? URL : urldecode(str_replace('.', '%', $msg));
		}
		
		$head = array('title' => '사용자 로그인');
		$data = array(
			'url'      => $url,
			'msg'      => ($msg == 1) ? TRUE : FALSE
		);
		
		widget::run('head', $head);
		$this->load->view('login', $data);
		widget::run('tail');
	}

	function in() {
		$mb = $this->Basic_model->get_member($this->input->post('mb_email'), $this->input->post('mb_password'));
		if(count($mb) < 1){
			alert("사용자 이메일 또는 비밀번호를 확인해 주세요.");
		}
		
		$this->session->set_userdata('ss_member', $mb[0]);
		
		$url = $this->input->post('url');
		
		if($url == ""){
			//goto_url($this->config->item('base_url') . "/admin/member/index");
			goto_url($this->config->item('base_url') . "/main");
		} else {
			goto_url($url);
		}
	}

	function out() {
		$this->session->sess_destroy();
		goto_url('/');
	}
}
?>