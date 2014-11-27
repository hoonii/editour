<?php
class Admin_Faq_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function list_result($searchSubject, $type, $limit, $offset) {
		$from  = "  FROM faq a";
		
		$where  = "  WHERE 1=1 ";
		$where .= "    AND faq_type = '" .$type. "'";
		$where .= "    AND del_yn = 'N'";
		
		if(strlen(trim($searchSubject)) > 0){
			$where .= "    AND ( ";
			$where .= "         subject LIKE '%" .$searchSubject. "%' ";
			$where .= "         OR ";
			$where .= "         content LIKE '%" .$searchSubject. "%' ";
			$where .= "        ) ";
		}
		
		$cnt_sql  = " SELECT count(a.seq) AS count ";
		$cnt_sql .= $from;
		$cnt_sql .= $where;
		
		$sql  = " SELECT a.seq, a.subject, a.content, a.reg_dt, a.update_dt, a.read_cnt, ";
		$sql .= "        (SELECT mb_name FROM member WHERE mb_email = a.mb_email) AS mb_name " ;
		$sql .= $from;
		$sql .= $where;
		$sql .= " ORDER BY seq DESC ";
		$sql .= " LIMIT " . $offset . "," . $limit;
		
		$query = $this->db->query($cnt_sql);
		$row = $query->row_array();
		$result['total_cnt'] = $row['count'];
		
		$qry = $this->db->query($sql);
		$result['qry'] = $qry->result_array();
		
		return $result;
	}
	
	function insert_faq($subject, $content, $mb_email, $type){
		$sql  = " INSERT INTO faq ( ";
		$sql .= " subject, content, reg_dt, update_dt, mb_email, del_yn, faq_type, read_cnt ";
		$sql .= " ) VALUES ( ";
		$sql .= "'" . $subject . "', ";
		$sql .= "'" . $content . "', ";
		$sql .= "NOW(), ";
		$sql .= "NOW(), ";
		$sql .= "'" . $mb_email . "', ";
		$sql .= "'N', ";
		$sql .= "'" . $type . "', ";
		$sql .= "0 ";
		$sql .= " ) ";
		
		$result = $this->db->query($sql);
		
		return $result;
	}
	
	function view_faq($seq){
		$sql  = " SELECT seq, subject, content, reg_dt, update_dt, mb_email, del_yn, faq_type, read_cnt ";
		$sql .= "   FROM faq ";
		$sql .= "  WHERE seq = '" .$seq. "'";
		$sql .= "    AND del_yn = 'N'";
		
		$qry = $this->db->query($sql);
		$result = $qry->result_array();
		
		return $result;
		
	}
	
	function update_faq($seq, $subject, $content, $mb_email, $type){
		$sql  = " UPDATE faq set ";
		$sql .= "  subject = '" .$subject. "', ";
		$sql .= "  content = '" .$content. "', ";
		$sql .= "  update_dt = NOW() ";
		$sql .= "  WHERE seq =  '" .$seq. "'";
		$sql .= "    AND faq_type = '" .$type. "'";
		
		$result = $this->db->query($sql);
		
		return $result;
	}
	
	function delete_faq($seq, $type){
		$sql  = " UPDATE faq set ";
		$sql .= "  del_yn = 'Y' ";
		$sql .= "  WHERE seq =  '" .$seq. "'";
		$sql .= "    AND faq_type = '" .$type. "'";
		
		$result = $this->db->query($sql);
		
		return $result;
	}
}
?>