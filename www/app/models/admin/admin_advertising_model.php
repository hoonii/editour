<?php
class Admin_Advertising_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	/**
	 * 광고등록 목록
	 * @param unknown_type $limit
	 * @param unknown_type $offset
	 */
	function list_result($limit, $offset) {
		$this->db->select("seq, company_name, business_type, s_dt, e_dt, in_charge, tel, email, keyword, reg_dt");
		$this->db->order_by('reg_dt', 'desc');
		
		return $this->db->get('advertising', $limit, $offset)->result_array();
	}

	/**
	 * 광고등록 목록 전체 카운트
	 */
	function list_count() {
		return $this->db->count_all_results('advertising');
	}
	
	function show_image($seq){
		$this->db->select("image");
		$this->db->where('seq', $seq);
		
		return $this->db->get('advertising')->result_array();
	}
	
	
	/**
	 * 새로운 광고 등록
	 * @param unknown_type $company_name
	 * @param unknown_type $business_type
	 * @param unknown_type $s_dt
	 * @param unknown_type $e_dt
	 * @param unknown_type $in_charge
	 * @param unknown_type $tel
	 * @param unknown_type $email
	 * @param unknown_type $image
	 * @param unknown_type $keyword
	 */
	function insert_advertising($company_name, $business_type, $s_dt, $e_dt, $in_charge, $tel, $email, $image, $keyword){
		$sql  = "INSERT INTO advertising (";
		$sql .= "	company_name,";
		$sql .= "	business_type,";
		$sql .= "	s_dt,";
		$sql .= "	e_dt,";
		$sql .= "	in_charge,";
		$sql .= "	tel,";
		$sql .= "	email,";
		$sql .= "	image,";
		$sql .= "	keyword,";
		$sql .= "	reg_dt";
		$sql .= ") VALUES (";
		$sql .= "	'" .$company_name. "',";
		$sql .= "	'" .$business_type. "',";
		$sql .= "	'" .$s_dt. "',";
		$sql .= "	'" .$e_dt. "',";
		$sql .= "	'" .$in_charge. "',";
		$sql .= "	'" .$tel. "',";
		$sql .= "	'" .$email. "',";
		$sql .= "	'" .$image. "',";
		$sql .= "	'" .$keyword. "',";
		$sql .= "	NOW()";
		$sql .= ")";
		
		return $this->db->query($sql);
	}
	
	/**
	 * 광고 통계 노출 목록
	 * @param unknown_type $company_name
	 * @param unknown_type $s_dt
	 * @param unknown_type $e_dt
	 */
	function statics_list($company_name=null, $s_dt=null, $e_dt=null){
		$this->db->select('a.seq, b.publishing_seq, a.company_name, a.business_type, a.s_dt, a.e_dt, a.in_charge, a.tel, a.email, a.image, count(b.advertising_seq) as cnt');
		$this->db->join('advertising_use b', 'a.seq = b.advertising_seq');
		
		if($company_name != null){
			$this->db->where("a.company_name = '{$company_name}'");
		}
		
		if($s_dt != null && $e_dt != null){
			$this->db->where("b.reg_dt BETWEEN '{$s_dt}' AND '{$e_dt}'");
		}
		
		$this->db->group_by('a.company_name');
		$this->db->order_by('cnt', 'desc');
		
		
		return $this->db->get('advertising a')->result_array();
	}
	
	/**
	 * 광고 노출 목록
	 * @param unknown_type $advertising_seq
	 * @param unknown_type $publishing_seq
	 */
	function statics_exposure($advertising_seq=null, $publishing_seq=null){
		$this->db->select('b.subject, count(a.advertising_seq) AS cnt');
		$this->db->join('publishing b', 'a.publishing_seq = b.seq');
		$this->db->group_by('b.subject');
		$this->db->order_by('cnt', 'desc');
		$this->db->where("a.advertising_seq = '{$advertising_seq}'");
		$this->db->where("b.seq = '{$publishing_seq}'");
		
		return $this->db->get('advertising_use a')->result_array();
	}
	
	/**
	 * 광고 그래프 통계
	 * @param unknown_type $company_name
	 * @param unknown_type $date
	 */
	function statics_graph($company_name=null, $date = null){
		$this->db->select("a.seq, a.company_name ,count(b.advertising_seq) as cnt, b.reg_dt ");
		$this->db->join('advertising_use b', 'a.seq = b.advertising_seq');
		$this->db->group_by('a.company_name');
		$this->db->where("DATE_FORMAT(b.reg_dt, '%Y-%m-%d') = '{$date}'");
		$this->db->order_by('a.seq', 'asc');
		
		if($company_name != null){
			$this->db->where("a.company_name = '{$company_name}'");
		}
		
		return $this->db->get('advertising a')->result_array();
	}
	
	function statics_company_list($company_name=null, $s_dt=null, $e_dt=null){
		$this->db->select('a.seq, b.publishing_seq, a.company_name');
		$this->db->join('advertising_use b', 'a.seq = b.advertising_seq');
	
		if($company_name != null){
			$this->db->where("a.company_name = '{$company_name}'");
		}
	
		if($s_dt != null && $e_dt != null){
			$this->db->where("DATE_FORMAT(b.reg_dt, '%Y-%m-%d') BETWEEN '{$s_dt}' AND '{$e_dt}'");
		}
	
		$this->db->group_by('a.company_name');
		$this->db->order_by('a.seq', 'asc');
	
		return $this->db->get('advertising a')->result_array();
	}
}
?>