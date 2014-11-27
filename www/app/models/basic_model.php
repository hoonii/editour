<?php
class Basic_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->library('encrypt');
	}

	// 회원 정보
	function get_member($mb_email = null, $mb_password = null) {
		if (!$mb_email) return FALSE;
		
		$sql  = " SELECT * ";
		$sql .= "   FROM member ";
		$sql .= " WHERE mb_email = '" .$mb_email. "'";
		if($mb_password != null){
			$sql .= "   AND mb_password = password('".$mb_password."')";
		}
		
		$qry = $this->db->query($sql);
		return $qry->result_array();
	}

	// 아이디로 관리자 정보 가져오기
	function get_admin_email($mb_email) {
		if (!$mb_email) return FALSE;
		
		$sql  = " SELECT * ";
		$sql .= "   FROM member ";
		$sql .= " WHERE mb_email = '" .$mb_email. "'";
		
		$qry = $this->db->query($sql);
		return $qry->result_array();
	}

	// 관리자 정보
	function get_admin($mb_email, $mb_password) {
		if (!$mb_email) return FALSE;
		
		$sql  = " SELECT * ";
		$sql .= "   FROM member ";
		$sql .= " WHERE mb_email = '" .$mb_email. "'";
		$sql .= "   AND mb_password = password('".$mb_password."')";
		$sql .= "   AND mb_admin_yn = 'Y'";
		
		$qry = $this->db->query($sql);
		return $qry->result_array();
	}

	// 게시판 정보
	function get_board($bo_table, $fields='*', $gr_join='') {
        if (!$bo_table) return FALSE;
        
		$gr_field = '';
		if ($gr_join) {
			$gr_join = 'a';
			$this->db->join('board_group b', 'a.gr_id = b.gr_id');
			$gr_field = ', b.gr_id, b.gr_subject, b.gr_admin ';
		}

		$this->db->select($fields.$gr_field);
		return $this->db->get_where('board '.$gr_join, array('bo_table' => $bo_table))->row_array();
	}
	
	// 게시글 정보
	function get_write($bo_table, $wr_ids, $fields) {
		if (!$wr_ids) return FALSE;

		$this->db->select($fields);
		$this->db->where('bo_table', $bo_table);
		if (is_array($wr_ids)) {
			$this->db->where_in('wr_id', $wr_ids);
			return $this->db->get('write')->result_array();
		}
		else {
			return $this->db->get_where('write', array(
				'wr_id' => $wr_ids
			))->row_array();
		}
	}
}
?>