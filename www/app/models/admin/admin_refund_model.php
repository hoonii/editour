<?php
class Admin_Refund_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function index_result($searchKeyword, $limit, $offset) {
		$from  = "  FROM refund a, member b ";
		
		$where  = "  WHERE a.mb_email = b.mb_email ";
		$where .= "    AND status != '03' ";
		if(strlen(trim($searchKeyword)) > 0){
			$where .= " AND a.mb_email = '" . $searchKeyword . "'";
		}
		
		$cnt_sql  = " SELECT count(a.seq) as count ";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
		
		$sql  = " SELECT a.seq, a.mb_email, b.mb_name, b.mb_point, a.refund_price, a.reg_dt ";
		$sql .= $from;
		$sql .= $where;
		$sql .= " ORDER BY b.mb_name ";
		$sql .= " LIMIT " . $offset . "," . $limit;
		
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
		
		$qry = $this->db->query($sql);
		$result['qry'] = $qry->result_array();
		
		return $result;
	}
	
	
	
	function refund_popup_result($refund_seq, $searchEmail) {
		$sql  = " SELECT a.seq, a.mb_email, b.mb_name, b.mb_point, a.refund_price, ";
		$sql .= "        (select name from bank where seq = a.bank_seq) as bank_name, ";
		$sql .= "        a.depositor, a.account_number, a.pay, a.charge ";
		$sql .= "   FROM refund a, member b " ;
		$sql .= "  WHERE a.seq = '" .$refund_seq. "'";
		$sql .= "    AND a.mb_email = '" .$searchEmail. "'";
		$sql .= "    AND a.mb_email = b.mb_email ";
	
		$qry = $this->db->query($sql);
	
		return $qry->result_array();
	}
	
	/**
	 * 환급완료에 따른 상태값 수정
	 * @param unknown_type $seq
	 * @param unknown_type $mb_email
	 * @param unknown_type $pay
	 * @param unknown_type $charge
	 * @param unknown_type $status
	 */
	function update_refund_complete($seq, $mb_email, $pay, $charge, $status){
		$sql  = " UPDATE refund SET ";
		$sql .= "    pay = '" .$pay. "', ";
		$sql .= "    charge = '" .$charge. "', ";
		$sql .= "    status = '" .$status. "', ";
		$sql .= "    status_dt = NOW() ";
		$sql .= " WHERE seq = '" .$seq. "' ";
		$sql .= "   AND mb_email = '" .$mb_email. "' ";
		
		return $this->db->query($sql);
	}
}
?>