<?php
class Forget_pwd extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->config->load('cf_register');
		$this->load->model('Member_forget_model');
		$this->load->model('Basic_model');
		define('WIDGET_SKIN', 'main');
	}

	function index() {
		if (IS_MEMBER)
			alert('이미 로그인 중입니다.');

		$head = array('title' => '비밀번호 찾기');
		$data = array();

		widget::run('head', $head);
		$this->load->view('member/forget_pwd', $data);
		widget::run('tail');
	}
	
	function find(){
		$mb_email = $this->input->post('mb_email');
		
		if($mb_email == ADMIN){
			alert("에디투어 회원이 아닙니다.", $this->input->server('HTTP_REFERER'));
		} else {
			$member = $this->Basic_model->get_member($mb_email);
			
			if(sizeof($member) == 1){
			
				$change_pwd = rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999);
				$this->Member_forget_model->new_pwd_update($mb_email, $change_pwd);
				
				alert("임시 비밀번호가 발송 되었습니다.", "/");
			} else {
				alert("에디투어 회원이 아닙니다.", $this->input->server('HTTP_REFERER'));
			}
		}
		
		
		/*
		$mail_to = $mb_email."<".$mb_email.">";
		$subject = "에디투어에 요청하신 임시 비밀번호 입니다.";
		$msg  = "요청하신 임시 비밀번호는 " . $change_pwd . " 입니다.\r\n";
		$msg .= "로그인 후 반듯이 비밀번호를 변경하시기 바랍니다.";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: ".$mb_email."\r\n";
		
		mail($mail_to, $subject, $msg, $headers);
		*/
	}
}
?>