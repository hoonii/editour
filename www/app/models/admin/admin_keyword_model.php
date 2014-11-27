<?php
class Admin_Keyword_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function keyword_group(){
		$this->db->select("seq, publishing_seq, keyword");
		$this->db->order_by('keyword', 'asc');
		$this->db->group_by('keyword');
		
		return $this->db->get('publishing_keyword')->result_array();
	}
}
?>