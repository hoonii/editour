<?php
class Admin_Publisher_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function list_result($searchEmail, $limit, $offset) {
		$from  = "  FROM publisher a, member b ";
		
		$where  = "  WHERE a.mb_email = b.mb_email ";
		if(strlen(trim($searchEmail)) > 0){
			$where .= " AND a.mb_email = '" . $searchEmail . "'";
		}
		
		$cnt_sql  = " SELECT count(a.seq) as count ";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
		
		$sql  = " SELECT a.seq, a.mb_email, a.pen_name, b.mb_name, ";
		$sql .= "        (select count(seq) from publishing where a.mb_email = mb_email) as pub_cnt,";
		$sql .= "        (select ifnull(sum(cumulative_price), 0) from publishing where a.mb_email = mb_email) as cumulative_price,";
		$sql .= "        (select ifnull(sum(cumulative_download), 0) from publishing where a.mb_email = mb_email) as cumulative_download";
		$sql .= $from;
		$sql .= $where;
		$sql .= " ORDER BY mb_name ";
		$sql .= " LIMIT " . $offset . "," . $limit;
		
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
		
		$qry = $this->db->query($sql);
		$result['qry'] = $qry->result_array();
		
		return $result;
	}

	function sale_result($searchKeyword, $limit, $offset) {
		$from  = "  FROM publisher a, member b ";
		
		$where  = "  WHERE 1 = 1 ";
		
		if(strlen(trim($searchKeyword)) > 0){
			$where .= "    AND ( ";
			$where .= "        b.mb_name LIKE '%" .$searchKeyword. "%' ";
			$where .= "        OR ";
			$where .= "        a.mb_email LIKE '%" .$searchKeyword. "%' ";
			$where .= "        ) ";
		}
		
		$where .= "   AND a.mb_email = b.mb_email ";
		
		$cnt_sql  = " SELECT count(a.seq) as count ";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
		
		$sql  = " SELECT a.seq, a.mb_email, a.pen_name, b.mb_name, b.mb_point, ";
		$sql .= "        (select ifnull(sum(price), 0) from purchase where a.mb_email = mb_email) as sale_price,";
		$sql .= "        (select ifnull(sum(refund_price), 0) from refund where a.mb_email = mb_email) as refund_price, ";
		$sql .= "        (select ifnull(sum(pay), 0) from pay where a.mb_email = mb_email) as pay, ";
		$sql .= "        (select ifnull(sum(charge), 0) from pay where a.mb_email = mb_email) as charge";
		$sql .= $from;
		$sql .= $where;
		$sql .= " ORDER BY mb_name ";
		$sql .= " LIMIT " . $offset . "," . $limit;
		
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
		
		$qry = $this->db->query($sql);
		$result['qry'] = $qry->result_array();
		
		return $result;
	}
	
	
	
	function refund_list_popup_result($searchEmail, $limit, $offset) {
		$from  = "  FROM refund a";
	
		$where  = "  WHERE 1 = 1 ";
	
		if(strlen(trim($searchEmail)) > 0){
			$where .= "    AND a.mb_email = '" .$searchEmail. "'";
		}
	
		$cnt_sql  = " SELECT count(a.seq) as count ";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
	
		$sql  = " SELECT a.seq, a.mb_email, a.reg_dt, a.refund_price, a.pay, a.charge, a.status, ";
		$sql .= "        (select mb_name from member where a.mb_email = mb_email) as mb_name";
		$sql .= $from;
		$sql .= $where;
		$sql .= " ORDER BY a.reg_dt DESC ";
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