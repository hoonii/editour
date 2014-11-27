<?php
class Admin_Purchase_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function list_result($mb_email, $limit, $offset) {
		$from  = "  FROM purchase a, publishing b, publisher c";
		
		$where  = "  WHERE a.mb_email = '" . $mb_email . "' ";
		$where .= "    AND a.publishing_seq = b.seq ";
		$where .= "    AND a.publisher_seq = c.seq ";
		
		$cnt_sql  = " SELECT count(a.seq) as count";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
		
		$sql  = " SELECT a.seq, a.purchase_dt, b.subject, b.price, c.pen_name ";
		$sql .= $from;
		$sql .= $where;
		
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
		
		$query = $this->db->query($sql);
		$result['qry'] = $query->result_array();
	
		return $result;
	}
}
?>