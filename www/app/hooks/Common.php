<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _Common {
	function index() {
		$CI =& get_instance();

		header("Content-Type: text/html; charset=".$CI->config->item('charset'));
		header("Expires: 0"); // rfc2616 - Section 14.21
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
		header("Pragma: no-cache"); // HTTP/1.0
		
		$uri = $CI->input->server('REQUEST_URI');
		
		
		/*
		 * 요청한 url이 관리자 페이지 일때
		 */
		if(substr($uri, 0, 6) == "/admin"){
			$ss_admin = $CI->session->userdata('ss_admin');
			if(is_array($ss_admin) != 1){
				if(substr($uri, 0, 12) != "/admin/login"){
					define('URL', $CI->config->item('base_url') . $uri);
					define('SU_ADMIN', null);
					goto_url($CI->config->item('base_url') . "/admin/login");
					
				}
			} else {
				//echo "not null";
			}
			
			define('SU_ADMIN', $ss_admin['mb_email']);
		}
		
		/*
		 * 요청한 url이 사용자 페이지 일때
		 */
		else {
			$ss_member = $CI->session->userdata('ss_member');
			
			$is_member = $is_super = false;
			
			if(!empty($ss_member)){
				$is_member = true;
				if($ss_member['mb_admin_yn'] == 'Y'){
					$is_super = true;
				}
			}
			
			$php_self     = $CI->input->server('PHP_SELF');
			$http_referer = $CI->input->server('HTTP_REFERER');
			
			$referer = parse_url($http_referer);
			$repself = str_replace('/index.php', '', $php_self);
			if (!empty($referer['path']) && $referer['path'] != $repself) {
				$url = $http_referer;
			} else {
				$url = '/';
			}
				
			define('URL', $url);
			define('IS_MEMBER', $is_member);
			define('SU_ADMIN', $is_super);
			define('MEMBER', serialize($ss_member));
			
			/*
			$is_member = $is_super = FALSE;
			$login_id = $CI->session->userdata('ss_mb_email');
			if ($login_id) {
				$member = $CI->Basic_model->get_member($login_id);
			
				if (substr($member['mb_today_login'], 0, 10) != TIME_YMD) {
					$CI->load->model('Point_model');
					$CI->Point_model->insert($member['mb_id'], $CI->config->item('cf_login_point'), TIME_YMD.' 첫로그인', '@login', $member['mb_id'], TIME_YMD);
			
					$CI->db->where('mb_id', $member['mb_id']);
					$CI->db->update('member', array(
							'mb_today_login' => TIME_YMDHIS,
							'mb_login_ip'	 => $CI->input->server('REMOTE_ADDR')
					));
				}
			
				if ($member['mb_id']) {
					$is_member = TRUE;
					if ($member['mb_level'] >= 10) // 관리자 조건
						$is_super = $member['mb_id'];
			
					if (!$CI->config->item('cf_use_nick'))
						$member['mb_nick'] = $member['mb_name'];
				}
			}
			else {
				$member['mb_level'] = 1;
			}
			
			$php_self     = $CI->input->server('PHP_SELF');
			$http_referer = $CI->input->server('HTTP_REFERER');
			
			// visit
			if ($CI->session->userdata('ck_visit_ip') != $CI->input->server('REMOTE_ADDR')) {
				$CI->session->set_userdata('ck_visit_ip', $CI->input->server('REMOTE_ADDR'));
					
				$visit_referer = ($http_referer) ? $http_referer : $php_self;
				$CI->db->simple_query(" insert into visit ( vi_ip, vi_date, vi_time, vi_referer, vi_agent ) values ( '".$CI->input->server('REMOTE_ADDR')."', '".TIME_YMD."', '".TIME_HIS."', '".$visit_referer."', '".$CI->input->server('HTTP_USER_AGENT')."' ) ");
			}
			
			// 관리자 페이지
			if ($is_super) {
			} else if ($CI->uri->segment(1) == ADM_F) {
				show_404();
			}
			
			$referer = parse_url($http_referer);
			$repself = str_replace('/index.php', '', $php_self);
			if (!empty($referer['path']) && $referer['path'] != $repself) {
				$url = $http_referer;
			} else {
				$url = '/';
			}
			
			define('URL', $url);
			define('IS_MEMBER', $is_member);
			define('SU_ADMIN', $is_super);
			define('MEMBER', serialize($member));
			*/
		}
	}
}
?>