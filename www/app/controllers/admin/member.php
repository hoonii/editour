<?php
class Member extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('/admin/Admin_Member_model');
		define('WIDGET_SKIN', 'admin');
		define('CSS_SKIN', 'jquery');
	}
	
	
	function index() {
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		$sst = $param->get('sst', 'mb_name');
		$sod = $param->get('sod', 'desc');
		$sfl = $param->get('sfl');
		$stx = $param->get('stx');
		
		$config['suffix'] = $param->output();
		$config['base_url'] = '/admin/member/index/page/';
		$config['per_page'] = 50;
		
		$offset = ($page - 1) * $config['per_page'];
		$result = $this->Admin_Member_model->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$list = array();
		$token = get_token();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = new stdClass();
			
			if ($this->config->item('cf_use_nick'))
				$list[$i]->nick = $row['mb_nick'];
		
			if (!$row['mb_leave_date'])
				$mb_id_s = get_sideview($row['mb_id'], $row['mb_id']);
			else
				$mb_id_s = '<font color="crimson">'.$row['mb_id'].'</font>';
			
			$list[$i]->name = $row['mb_name'];
			$list[$i]->id_s = $mb_id_s;
			$list[$i]->mb_email = $row['mb_email'];
			$list[$i]->level_select = get_mb_level_select("mb_levels[".$row['mb_id']."]", $row['mb_level'], TRUE);
			$list[$i]->point = number_format($row['mb_point']);
			$list[$i]->today_login = substr($row['mb_today_login'], 2, 8);
			$list[$i]->mailling_chk = $row['mb_mailling'] ? '&radic;' : '&nbsp;';
			$list[$i]->open_chk = $row['mb_open'] ? '&radic;' : '&nbsp;';
			$list[$i]->s_mod = icon('수정', 'member/form/u/'.$row['mb_id']);
			$list[$i]->s_del = icon('삭제', "javascript:post_send('".ADM_F."/_trans/member/delete', {mb_id:'".$row['mb_id']."', token:'".$token."'}, true);");
			$list[$i]->email_certify = $row['mb_email_certify'];
			$list[$i]->mail_certify_chk = preg_match('/[1-9]/', $row['mb_email_certify']) ? '&radic;' : '&nbsp;';
			$list[$i]->mb_open_date = substr($row['mb_open_date'], 2, 8);
		}
		
		$head = array('title' => '회원관리');
		$data = array(
				'token' => $token,
		
				'list' => $list,
				's_add' => icon('작성', 'member/form'),
				'use_nick' => $this->config->item('cf_use_nick'),
		
				'sfl' => $sfl,
				'stx' => $stx,
		
				'total_cnt' => number_format($result['total_cnt']),
				'leave_cnt' => number_format($result['leave_cnt']),
				'paging' => $this->pagination,
		
				'sort_mb_id' => $param->sort('mb_id'),
				'sort_mb_name' => $param->sort('mb_name'),
				'sort_mb_nick' => $param->sort('mb_nick'),
				'sort_mb_level' => $param->sort('mb_level', 'desc'),
				'sort_mb_point' => $param->sort('mb_point', 'desc'),
				'sort_mb_today_login' => $param->sort('mb_today_login', 'desc'),
				'sort_mb_mailling' => $param->sort('mb_mailling', 'desc'),
				'sort_mb_open' => $param->sort('mb_open', 'desc'),
				'sort_mb_email_certify' => $param->sort('mb_email_certify', 'desc')
		);
		
		widget::run('head', $head);
		$this->load->view('/admin/member/index', $data);
		widget::run('tail');
	}
}
?>