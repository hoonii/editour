<?php
class Join extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->config->load('cf_register');
		$this->load->library('form_validation');
		$this->load->model(array('Member_infor_model', 'Register_model'));
		define('WIDGET_SKIN', 'main');
		define('CSS_SKIN', 'jquery');
	}

	function index() {
		if (IS_MEMBER){
			goto_url('/');
		}
		
		$token = get_token();
		
		$this->load->helper('file');
		
		$head = array('title' => '회원 가입');
		$data = array(
				'mb_name' => $this->input->post('mb_name'),
		
				'cf_use_nick'	 => $this->config->item('cf_use_nick'),
				'cf_nick_modify' => $this->config->item('cf_nick_modify'),
				'cf_open_modify' => $this->config->item('cf_open_modify'),
		
				'token'  => $token,
				'todays' => date("Ymd", time()),
				
				'privacy'	  => read_file(SKIN_PATH.'/member/privacy.txt'),
				'stipulation' => read_file(SKIN_PATH.'/member/stipulation.txt')
		);
		
		widget::run('head', $head);
		$this->load->view('member/join', $data);
		widget::run('tail');
	}

	/*
	function _form() {
		$token = get_token();

		$head = array('title' => '회원 가입');
		$data = array(
			'mb_name' => $this->input->post('mb_name'),

			'cf_use_nick'	 => $this->config->item('cf_use_nick'),
			'cf_nick_modify' => $this->config->item('cf_nick_modify'),
			'cf_open_modify' => $this->config->item('cf_open_modify'),

			'token'  => $token,
			'todays' => date("Ymd", time())
		);

		widget::run('head', $head);
		$this->load->view('member/join', $data);
		widget::run('tail');
	}
	*/

	function update() {
		if($_SERVER['REQUEST_METHOD'] == 'POST'){		
			$this->load->helper('chkstr');
			$config = array(
				array('field'=>'mb_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[10]|callback_mb_name_check'),
				array('field'=>'mb_email', 'label'=>'이메일', 'rules'=>'trim|required|max_length[50]|valid_email|callback_mb_email_check'),
				array('field'=>'mb_tel', 'label'=>'전화번호', 'rules'=>'trim|required'),
				array('field'=>'mb_birth', 'label'=>'생일', 'rules'=>'trim|exact_length[10]'),
				array('field'=>'mb_password', 'label'=>'비밀번호', 'rules'=>'trim|required|max_length[20]|md5'),
				array('field'=>'mb_password_re', 'label'=>'비밀번호 확인', 'rules'=>'trim|required|max_length[20]|matches[mb_password]|md5')
			);
	
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == FALSE) {
				$this->index();
			}
			else {
				$this->load->library(array('encrypt', 'email'));
				
				// 회원 INSERT
				$this->Member_infor_model->insert();
				
				// 회원님께 메일 발송
				/*
				if ($this->config->item('cf_email_mb_member') || $this->config->item('cf_use_email_certify')) {
					$admin = $this->Basic_model->get_admin_email(ADMIN, 'mb_nick, mb_email');
					
					$mb_md5 = md5($this->input->post('mb_id').$this->input->post('mb_email').TIME_YMDHIS);
					$certify_href = $this->config->item('base_url').'/member/certify/email/'.$this->input->post('mb_id').'/'.$mb_md5;
	
					$data = array(
						'mb_name' => $this->input->post('mb_name'),
						'certify_href' => $certify_href,
						'email_chk' => $this->config->item('cf_use_email_certify')
					);
					
					$content = $this->load->view('mail/join_member', $data, TRUE);
	
					$this->email->clear();
					$this->email->from($admin[0]['mb_email'], $admin[0]['mb_name']);
					$this->email->to($this->input->post('mb_email'));
					$this->email->subject("회원가입을 축하드립니다.");
					$this->email->message($content);
					$this->email->send();
				}
				*/
				
				$mb = $this->Basic_model->get_member($this->input->post('mb_email'), $this->input->post('mb_password'));
				$this->session->set_userdata('ss_member', $mb[0]);
	
				goto_url('main');
			}
		} else {
			$this->index();
		}
	}

	function result() {
		$head = array('title' => '회원가입 결과');
		$data = array(
			'mb_name' 	=> $mb['mb_name'],
			'mb_email' 	=> $mb['mb_email'],
			'email_chk' => $this->config->item('cf_use_email_certify')
		);

		widget::run('head', $head);
		$this->load->view('member/join_result', $data);
		widget::run('tail');
	}

	function mb_id_check($str) {
		if (preg_match("/[\,]?{$str}/i", $this->config->item('cf_prohibit_id'))) {
			$this->form_validation->set_message('mb_id_check', $str.' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다.');
			return FALSE;
		}

		$row = $this->Register_model->is('mb_id', $str);
		if ($row != 0) {
			$this->form_validation->set_message('mb_id_check', $str.' 은(는) 이미 다른분이 사용중인 회원아이디이므로 사용이 불가합니다.');
			return FALSE;
		}
		return TRUE;
	}

	function mb_name_check($str) {
		if (!check_string($str, _RT_HANGUL_)) {
			$this->form_validation->set_message('mb_name_check', '이름은 공백없이 한글만 입력 가능합니다.');
			return FALSE;
		}
		return TRUE;
	}

	function mb_nick_check($str) {
		if (!check_string($str, _RT_HANGUL_ + _RT_ALPHABETIC_ + _RT_NUMERIC_)) {
			$this->form_validation->set_message('mb_nick_check', '별명은 공백없이 한글, 영문, 숫자만 입력 가능합니다.');
			return FALSE;
		}

		if (preg_match("/[\,]?{$str}/i", $this->config->item('cf_prohibit_id'))) {
			$this->form_validation->set_message('mb_nick_check', $str.' 은(는) 예약어로 사용하실 수 없는 별명입니다.');
			return FALSE;
		}

		$row = $this->Register_model->is('mb_nick', $str);
		if ($row != 0) {
			$this->form_validation->set_message('mb_nick_check', $str.' 은(는) 이미 다른분이 사용중인 별명이므로 사용이 불가합니다.');
			return FALSE;
		}
		return TRUE;
	}

	function mb_email_check($str) {
		$row = $this->Register_model->is('mb_email', $str);
		if ($row != 0) {
			$this->form_validation->set_message('mb_email_check', $str.' 은(는) 이미 다른분이 사용중인 E-mail이므로 사용이 불가합니다.');
			return FALSE;
		}
		return TRUE;
	}
}
?>