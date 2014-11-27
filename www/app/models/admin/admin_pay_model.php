<?php
class Admin_Pay_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	
	/**
	 * 환급 완료 등록
	 * @param unknown_type $refund_seq
	 * @param unknown_type $mb_email
	 * @param unknown_type $pay
	 * @param unknown_type $charge
	 * @param unknown_type $comment
	 */
	function pay_insert($refund_seq, $mb_email, $pay, $charge, $comment) {
		$sql  = " INSERT INTO pay ( ";
		$sql .= " refund_seq, ";
		$sql .= " mb_email, ";
		$sql .= " pay, ";
		$sql .= " charge, ";
		$sql .= " pay_dt, ";
		$sql .= " comment ";
		$sql .= " ) VALUES ( ";
		$sql .= " '".$refund_seq."', ";
		$sql .= " '".$mb_email."', ";
		$sql .= " '".$pay."', ";
		$sql .= " '".$charge."', ";
		$sql .= " NOW(), ";
		$sql .= " '".$comment."' ";
		$sql .= " ) ";

		return $this->db->query($sql);
	}
}
?>