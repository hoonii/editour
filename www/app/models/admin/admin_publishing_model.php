<?php
class Admin_Publishing_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function list_result($searchSubject, $orderBy, $searchStatus, $limit, $offset) {
		$from  = "  FROM publishing a";
		
		$where  = "  WHERE 1=1 ";
		
		if(strlen(trim($searchSubject)) > 0){
			$where .= "    AND subject LIKE '%" .$searchSubject. "%' ";
		}
		
		if($searchStatus != 'ALL'){
			$where .= "    AND status = '" . $searchStatus . "'";
		}
		
		$cnt_sql  = " SELECT count(a.seq) AS count ";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
		
		$sql  = " SELECT a.seq, a.subject, a.reg_dt, a.price_division, a.price, a.status, ";
		$sql .= "        (SELECT mb_name FROM member WHERE mb_email = a.mb_email) AS mb_name " ;
		$sql .= $from;
		$sql .= $where;
		$sql .= " ORDER BY " . $orderBy . " DESC ";
		$sql .= " LIMIT " . $offset . "," . $limit;
		
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
		
		$qry = $this->db->query($sql);
		$result['qry'] = $qry->result_array();
		
		return $result;
	}

	function popup_list_result($searchEmail, $limit, $offset) {
		$from  = "  FROM publishing ";
		
		$where  = "  WHERE 1=1 ";
		$where .= "    AND mb_email = '" . $searchEmail . "'";
		
		$cnt_sql  = " SELECT count(seq) AS count ";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
		
		$sql  = " SELECT seq, subject, reg_dt, price_division, price ";
		$sql .= $from;
		$sql .= $where;
		$sql .= " LIMIT " . $offset . "," . $limit;
		
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
		
		$qry = $this->db->query($sql);
		$result['qry'] = $qry->result_array();
		
		return $result;
	}
	
	function view_result($seq){
		$sql  = "SELECT (SELECT name FROM area WHERE code = a.area_code1) AS area1, ";
		$sql .= "       (SELECT name FROM area WHERE code = a.area_code2) AS area2, ";
		$sql .= "       (SELECT name FROM area WHERE code = a.area_code3) AS area3, ";
		$sql .= "       (SELECT name FROM category WHERE code = a.category_code1) AS category1, ";
		$sql .= "       (SELECT name FROM category WHERE code = a.category_code2) AS category2, ";
		$sql .= "       (SELECT name FROM category WHERE code = a.category_code3) AS category3, ";
		$sql .= "       a.price_division, a.price, a.status ";
		$sql .= "  FROM publishing a ";
		
		$qry = $this->db->query($sql);
		return $qry->result_array();
	}
	
	function keyword($publishing_seq){
		$this->db->select("seq, publishing_seq, keyword");
		$this->db->where('publishing_seq', $publishing_seq);
		$this->db->order_by('seq', 'asc');
		
		return $this->db->get('publishing_keyword')->result_array();
	}
	
	/**
	 * 상태 수정
	 * @param unknown_type $seq
	 * @param unknown_type $status
	 * @return unknown
	 */
	function update_status($seq, $status){
		$sql  = " UPDATE publishing SET ";
		$sql .= " status = '" . $status . "', ";
		$sql .= " update_dt = NOW()";
		$sql .= " WHERE seq = '" .$seq. "'";
		
		$result = $this->db->query($sql);
		
		return $result;
	}
	
	/**
	 * 반려사유 등록
	 * @param unknown_type $seq
	 * @param unknown_type $status
	 * @return unknown
	 */
	function update_reason($seq, $status, $return_reason){
		$sql  = " UPDATE publishing SET ";
		$sql .= " status = '" . $status . "', ";
		$sql .= " return_reason = '" . $return_reason . "', ";
		$sql .= " update_dt = NOW()";
		$sql .= " WHERE seq = '" .$seq. "'";
		
		$result = $this->db->query($sql);
		
		return $result;
	}
	
	
	
	
	function sale_result($searchKeyword, $limit, $offset) {
		$from  = "  FROM publishing a";
	
		$where  = "  WHERE 1=1 ";
	
		if(strlen(trim($searchKeyword)) > 0){
			$where .= "    AND ( ";
			$where .= "        p.subject LIKE '%" .$searchKeyword. "%' ";
			$where .= "        OR ";
			$where .= "        p.mb_name LIKE '%" .$searchKeyword. "%' ";
			$where .= "        OR ";
			$where .= "        p.mb_email LIKE '%" .$searchKeyword. "%' ";
			$where .= "        ) ";
		}
	
		$cnt_sql  = " SELECT count(p.seq) AS count ";
		$cnt_sql .= "   FROM ( " ;
		$cnt_sql .= "         SELECT a.seq, a.subject, a.mb_email, a.reg_dt, a.price_division, a.price, a.status, " ;
		$cnt_sql .= "                (SELECT mb_name FROM member WHERE mb_email = a.mb_email) AS mb_name, " ;
		$cnt_sql .= "                (SELECT COUNT(seq) FROM purchase WHERE publishing_seq = a.seq) sale_cnt, " ;
		$cnt_sql .= "                (SELECT IFNULL(sum(price), 0) FROM purchase WHERE publishing_seq = a.seq) sale_price " ;
		$cnt_sql .= $from;
		$cnt_sql .= "        ) p";
		$cnt_sql .= $where;
	
		$sql  = " SELECT p.seq, p.subject, p.mb_email, p.reg_dt, p.price_division, p.price, p.status, p.mb_name, p.sale_cnt, p.sale_price ";
		$sql .= "   FROM ( " ;
		$sql .= "         SELECT a.seq, a.subject, a.mb_email, a.reg_dt, a.price_division, a.price, a.status, " ;
		$sql .= "                (SELECT mb_name FROM member WHERE mb_email = a.mb_email) AS mb_name, " ;
		$sql .= "                (SELECT COUNT(seq) FROM purchase WHERE publishing_seq = a.seq) sale_cnt, " ;
		$sql .= "                (SELECT IFNULL(sum(price), 0) FROM purchase WHERE publishing_seq = a.seq) sale_price " ;
		$sql .= $from;
		$sql .= "        ) p";
		$sql .= $where;
		$sql .= " ORDER BY p.reg_dt DESC ";
		$sql .= " LIMIT " . $offset . "," . $limit;
	
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
	
		$qry = $this->db->query($sql);
		$result['qry'] = $qry->result_array();
	
		return $result;
	}
}
?>