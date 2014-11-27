<?php
class Admin_Coupon_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	/**
	 * 발행 쿠폰 현황 목록
	 * @param unknown_type $limit
	 * @param unknown_type $offset
	 */
	function list_result($limit, $offset) {
		$this->db->select("seq, reg_dt, name, code, s_dt, e_dt, point, total, use");
		$this->db->order_by('reg_dt', 'desc');
		
		return $this->db->get('coupon', $limit, $offset)->result_array();
	}

	/**
	 * 발행 쿠폰 현황 전체 카운트
	 */
	function list_count() {
		return $this->db->count_all_results('coupon');
	}
	
	/**
	 * 중복 쿠폰 코드 검색
	 * @param unknown_type $code
	 */
	function coupon_code_search($code){
		$this->db->where('code', $code);
		
		return $this->db->count_all_results('coupon');
	}
	
	
	/**
	 * 쿠폰 등록
	 * @param unknown_type $name
	 * @param unknown_type $code
	 * @param unknown_type $s_dt
	 * @param unknown_type $e_dt
	 * @param unknown_type $total
	 * @param unknown_type $point
	 */
	function insert_coupon($name, $code, $s_dt, $e_dt, $total, $point){
		$sql  = "INSERT INTO coupon (";
		$sql .= "	reg_dt,";
		$sql .= "	name,";
		$sql .= "	code,";
		$sql .= "	s_dt,";
		$sql .= "	e_dt,";
		$sql .= "	point,";
		$sql .= "	total";
		$sql .= ") VALUES (";
		$sql .= "	NOW(),";
		$sql .= "	'" .$name. "',";
		$sql .= "	'" .$code. "',";
		$sql .= "	'" .$s_dt. "',";
		$sql .= "	'" .$e_dt. "',";
		$sql .= "	'" .$point. "',";
		$sql .= "	'" .$total. "'";
		$sql .= ")";
		
		return $this->db->query($sql);
	}
}
?>