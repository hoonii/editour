<?php
class Member_infor_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function insert() {
		$sql  = " INSERT INTO member ( ";
		$sql .= "     mb_name, ";
		$sql .= "     mb_email, ";
		$sql .= "     mb_tel, ";
		$sql .= "     mb_birth, ";
		$sql .= "     mb_password, ";
		$sql .= "     mb_datetime, ";
		$sql .= "     mb_admin_yn ";
		$sql .= " ) VALUES ( ";
		$sql .= " '" .$this->input->post('mb_name'). "', ";
		$sql .= " '" .$this->input->post('mb_email'). "', ";
		$sql .= " '" .$this->input->post('mb_tel'). "', ";
		$sql .= " '" .$this->input->post('mb_birth'). "', ";
		$sql .= " password('" .$this->input->post('mb_password'). "'), ";
		$sql .= " NOW(), ";
		$sql .= " 'N' ";
		$sql .= " )";
		
		$result = $this->db->query($sql);
	}

	function update() {
		$sql = array(
			'mb_password_q' => $this->input->post('mb_password_q'),
			'mb_password_a' => $this->input->post('mb_password_a'),
			'mb_mailling'   => $this->input->post('mb_mailling'),
			'mb_open'       => $this->input->post('mb_open'),
			'mb_email'      => $this->input->post('mb_email'),
			'mb_homepage'   => $this->input->post('mb_homepage'),
			'mb_tel'        => $this->input->post('mb_tel'),
			'mb_hp'         => $this->input->post('mb_hp'),
			'mb_zip'        => $this->input->post('mb_zip1').'-'.$this->input->post('mb_zip2'),
			'mb_addr1'      => $this->input->post('mb_addr1'),
			'mb_addr2'      => $this->input->post('mb_addr2'),
			'mb_profile'    => $this->input->post('mb_profile', TRUE)
		);
		if ($this->config->item('cf_use_nick'))
			$sql['mb_nick'] = $this->input->post('mb_nick');
		
		if ($this->input->post('mb_nick_default') != $this->input->post('mb_nick'))
			$sql['mb_nick_date'] = TIME_YMD;

		if ($this->input->post('mb_open_default') != $this->input->post('mb_open'))
			$sql['mb_open_date'] = TIME_YMD;

		// 이전 메일주소와 수정한 메일주소가 틀리다면 인증을 다시 해야하므로 값을 삭제
		if ($this->input->post('old_email') != $this->input->post('mb_email') && $this->config->item('cf_use_email_certify'))
			$sql['mb_email_certify'] = '';
            
        // 성별 & 생일
        if ($this->input->post('mb_sex'))   $sql['mb_sex']   = $this->input->post('mb_sex');
        if ($this->input->post('mb_birth')) $sql['mb_birth'] = $this->input->post('mb_birth');
   
		$this->db->where('mb_id', $this->input->post('mb_id'));
		$this->db->update('member', $sql);
	}

	function update_pwd() {
		$sql = array('mb_password' => $this->encrypt->encode($this->input->post('new_password')));
		$this->db->where('mb_id', $this->input->post('mb_id'));
		$this->db->update('member', $sql);
	}
}
?>