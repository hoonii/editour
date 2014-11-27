<?php
class Admin_Point_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function list_result($sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				case 'mb_id' :
					$this->db->where('a.'.$sfl, $stx);
				break;
				default :
					$this->db->like('a.'.$sfl, $stx, 'both');
				break;
			}
		}
		$this->db->stop_cache();

		$result['total_cnt'] = $this->db->count_all_results('point a');

		$this->db->join('member b', 'a.mb_email = b.mb_email');
		$this->db->select('a.po_id,a.po_datetime,a.po_content,a.po_point,b.mb_name,b.mb_nick,b.mb_point,a.po_etc, a.po_rest_point');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get('point a', $limit, $offset);
		$result['qry'] = $qry->result_array();

		$this->db->select_sum('a.po_point');
		$query = $this->db->get('point a');
		$row = $query->row_array();
		$result['total_pnt'] = $row['po_point'];

		$this->db->flush_cache();

		return $result;
	}
	
	/**
	 * 포인트 등록
	 * @param unknown_type $mb_email
	 * @param unknown_type $content
	 * @param unknown_type $point
	 * @param unknown_type $etc
	 * @param unknown_type $rest_point
	 */
	function insert_point($mb_email, $content, $point, $etc, $rest_point){
		$sql  = " INSERT INTO point (";
		$sql .= " mb_email, ";
		$sql .= " po_datetime, ";
		$sql .= " po_content, ";
		$sql .= " po_point, ";
		$sql .= " po_etc, ";
		$sql .= " po_rest_point ";
		$sql .= " ) VALUES (";
		$sql .= " '".$mb_email."', ";
		$sql .= " NOW(), ";
		$sql .= " '".$content."', ";
		$sql .= " '".$point."', ";
		$sql .= " '".$etc."', ";
		$sql .= " '".$rest_point."' ";
		$sql .= " )";
		
		return $this->db->query($sql);
	}
}
?>