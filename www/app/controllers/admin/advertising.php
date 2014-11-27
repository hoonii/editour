<?php
class Advertising extends CI_Controller {
	function __construct() {
		$CI =& get_instance();
		
		parent::__construct();
		$this->load->model('/admin/Admin_Advertising_model');
		$this->load->model('/admin/Admin_Keyword_model');
	}
	
	/**
	 * 광고등록 목록
	 */
	function index(){
		define('CSS_SKIN', 'jquery');
		define('WIDGET_SKIN', 'admin');
		
		$this->load->library(array('pagination', 'querystring'));
		$this->load->helper(array('admin', 'sideview'));
		
		$param =& $this->querystring;
		$page = $this->uri->segment(5, 1);
		
		$config['suffix'] = $param->output();
		$config['per_page'] = 50;
		$config['base_url'] = '/admin/advertising/index/page/';

		$offset = ($page - 1) * $config['per_page'];
		
		$result = $this->Admin_Advertising_model->list_result($config['per_page'], $offset);
		
		$config['total_rows'] = $this->Admin_Advertising_model->list_count();
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->num = $config['total_rows'] - ($page - 1) * $config['per_page'] - $i;
			$list[$i]->seq = $row['seq'];
			$list[$i]->company_name = $row['company_name'];
			$list[$i]->business_type = $row['business_type'];
			$list[$i]->s_dt = substr($row['s_dt'], 0, 10);
			$list[$i]->e_dt = substr($row['e_dt'], 0, 10);
			$list[$i]->in_charge = $row['in_charge'];
			$list[$i]->tel = $row['tel'];
			$list[$i]->email = $row['email'];
			$list[$i]->keyword = $row['keyword'];
			$list[$i]->reg_dt = substr($row['reg_dt'], 0, 10);
		}
		
		$head = array('title' => '광고등록 목록');
		
		$data = array(
			'token' => get_token(),
	
			'list' => $list,
	
			'total_cnt' => number_format($config['total_rows']),
			'paging' => $this->pagination,
		);
		
		widget::run('head', $head);
		$this->load->view('admin/advertising/index', $data);
		widget::run('tail');
	}
	
	/**
	 * 광고이미지 미리보기
	 */
	function show_advertising_image(){
		$seq = $_GET['seq'];
		
		$result = $this->Admin_Advertising_model->show_image($seq);
		
		header('Content-Length: '.strlen($result[0]['image']));
		header("Content-type: image/png");
		
		echo $result[0]['image'];
		exit();
	}
	
	/**
	 * 광고 등록 페이지
	 */
	function add_popup(){
		define('CSS_SKIN', 'jquery');
		define('WIDGET_SKIN', 'popup');
		
		$result = $this->Admin_Keyword_model->keyword_group();
		
		$head = array('title' => '광고등록');
		
		$data = array(
				'token' => get_token(),
				
				'keywords' => $result
		);
		
		widget::run('head', $head);
		$this->load->view('admin/advertising/add_popup', $data);
		widget::run('tail');
	}
	
	/**
	 * 광고 등록 처리
	 */
	function add_submit(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$company_name = $_POST['company_name'];
			$business_type = $_POST['business_type'];
			$s_dt = $_POST['s_dt'];
			$e_dt = $_POST['e_dt'];
			$in_charge = $_POST['in_charge'];
			$tel = $_POST['tel'];
			$email = $_POST['email'];
			$keyword = $_POST['keyword'];
			
			$img = getimagesize($_FILES['image']['tmp_name']);
			
			if($img[0] < 600 && $img[1] < 800){
				alert('이미지의 최소 해상도는 600 * 800 이상입니다.\\n다시 확인해 주세요.');
			}
			
			$image = file_get_contents($_FILES['image']['tmp_name']);
			$image = mysql_real_escape_string($image);
			
			$result = $this->Admin_Advertising_model->insert_advertising($company_name, $business_type, $s_dt, $e_dt, $in_charge, $tel, $email, $image, $keyword);
			
			echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$this->config->item('charset')."\">";
			echo "<script type='text/javascript'>alert('새로운 광고가 등록 되었습니다.'); opener.location.reload(); window.close();</script>";
		} else {
			
		}
	}
	
	/**
	 * 광고통계 목록 페이지
	 */
	function statics_list(){
		define('WIDGET_SKIN', 'admin');
	
		$head = array('title' => '광고 통계');
		
		$result = $this->Admin_Advertising_model->statics_list(null, null, null);
		
		$list = array();
		foreach ($result as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->company_name = $row['company_name'];
		}
		
		$data = array(
				'token' => get_token(),
				'list' => $list
		);
	
		widget::run('head', $head);
		$this->load->view('admin/advertising/statics_list', $data);
		widget::run('tail');
	}
	
	/**
	 * 광고통계 목록 결과
	 */
	function statics_list_result(){
		$data = array();
		
		$company_name = empty($_POST) ? "" : $_POST['company_name'];
		$s_dt = empty($_POST) ? "" : $_POST['s_dt'];
		$e_dt = empty($_POST) ? "" : $_POST['e_dt'];
		
		$result = $this->Admin_Advertising_model->statics_list($company_name, $s_dt, $e_dt);
		
		$list = array();
		foreach ($result as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->seq = $row['seq'];
			$list[$i]->publishing_seq = $row['publishing_seq'];
			$list[$i]->company_name = $row['company_name'];
			$list[$i]->business_type = $row['business_type'];
			$list[$i]->s_dt = substr($row['s_dt'], 0, 10);
			$list[$i]->e_dt = substr($row['e_dt'], 0, 10);
			$list[$i]->in_charge = $row['in_charge'];
			$list[$i]->tel = $row['tel'];
			$list[$i]->email = $row['email'];
			$list[$i]->image = $row['image'];
			$list[$i]->cnt = $row['cnt'];
		}
		
		$data = array(
				'token' => get_token(),
				'list' => $list
		);
	
		$this->load->view('admin/advertising/statics_list_result', $data);
	}
	
	/**
	 * 광고 노출 목록
	 */
	function statics_popup_result(){
		define('WIDGET_SKIN', 'popup');
		
		$head = array('title' => '광고 통계 노출 목록');
		
		$advertising_seq = $_GET['advertising_seq'];
		$publishing_seq = $_GET['publishing_seq'];
		
		$result = $this->Admin_Advertising_model->statics_exposure($advertising_seq, $publishing_seq);
		
		$list = array();
		foreach ($result as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->subject = $row['subject'];
			$list[$i]->cnt = $row['cnt'];
		}
		
		$data = array(
				'token' => get_token(),
				'list' => $list
		);
		
		widget::run('head', $head);
		$this->load->view('admin/advertising/statics_popup_result', $data);
		widget::run('tail');
	}
	
	
	
	/**
	 * 광고통계 그래프 페이지
	 */
	function statics_graph(){
		define('WIDGET_SKIN', 'admin');
	
		$head = array('title' => '광고 통계');
	
		$result = $this->Admin_Advertising_model->statics_list(null, null, null);
		
		$list = array();
		foreach ($result as $i => $row) {
			$list[$i] = new stdClass();
		
			$list[$i]->company_name = $row['company_name'];
		}
		
		$data = array(
				'token' => get_token(),
				'list' => $list
		);
	
		widget::run('head', $head);
		$this->load->view('admin/advertising/statics_graph', $data);
		widget::run('tail');
	}
	
	/**
	 * 광고통계 그래프 결과
	 */
	function statics_graph_result(){
		$data = array();
	
		$company_name = empty($_POST) ? "" : $_POST['company_name'];
		$s_dt = empty($_POST) ? "" : $_POST['s_dt'];
		$e_dt = empty($_POST) ? "" : $_POST['e_dt'];
		
		$company = $this->Admin_Advertising_model->statics_company_list($company_name, $s_dt, $e_dt);
		
		$value = array();
		$companyArr = array();
		$companySeqArr = array();
		$companyNameArr = array();
		$keyArr = array();
		$ticks = array();
		$statics = array();
		
		$s = new DateTime($s_dt);
		$e = new DateTime($e_dt);
			
		$diff = date_diff($s, $e);
		
		if(!empty($company)){
			foreach($company as $i => $row):
				$companyArr[$i] = "{label:'" .$row['company_name']. "'}";
				$companySeqArr[$i] = $row['seq'];
				$companyNameArr[$i] = $row['company_name'];
				$keyArr[$i] = 'key-'.$row['seq'];
			endforeach;
			
			for($i=0; $i<=$diff->days; $i++){
				$date = date('Y-m-d',strtotime($s_dt.'+'.$i.' days'));
				$ticks[$i] = $date; 
				
				$result = $this->Admin_Advertising_model->statics_graph($company_name, $date);
				
				$vals = array();
				foreach($result as $j => $row):
					$val = array("key-".strval($row['seq']) => $row['cnt']);
					array_push($vals, $val);
					unset($val);
				endforeach;
				
				if(empty($vals)){
					for($x=0; $x<sizeof($companySeqArr); $x++){
						$val = array('key-' . $companySeqArr[$x] => 0);
						array_push($vals, $val);
						unset($val);
					}
				} else {
					$tmpVals = array();
					
					$tmpKeys = array();
					foreach($vals as $z => $row):
						$re = array_keys($row);
						array_push($tmpKeys, $re[0]);
					endforeach;
					
					$re = array_diff($keyArr, $tmpKeys);
					
					foreach ($re as $z => $row):
						$val = array('key-' . $companySeqArr[$z] => 0);
						array_push($vals, $val);
						unset($val);
					endforeach;
				}
				
				$newVals = $this->keySort($vals);
				
				$stat = array();
				foreach($newVals as $z => $row):
					$k = array_keys($row);
					foreach($k as $a => $s):
						array_push($stat, $row[$s]);
					endforeach;
				endforeach;
				array_push($statics, $stat);
			}
			
			
			for($i=0; $i<sizeof($keyArr); $i++){
				$v = array();
				foreach($statics as $z => $row):
					array_push($v, $row[$i]);
				endforeach;
				array_push($value, $v);
				unset($v);
			}
		} else {
			for($i=0; $i<=$diff->days; $i++){
				$date = date('Y-m-d',strtotime($s_dt.'+'.$i.' days'));
				$ticks[$i] = $date;
			}
		}
		
		$data = array(
				'token' => get_token(),
	
				'ticks' => $ticks,
				'series' => $companyArr,
				'statics' => $value
		);
		
		$this->load->view('admin/advertising/statics_graph_result', $data);
	}
	
	function keySort($vals){
		$v = array();
		$tVal = "";
		
		foreach($vals as $z => $row):
			$k = array_keys($row);
			foreach($k as $a => $s):
				$tVal .= $s . "=" . $row[$s] . ",";
			endforeach;
		endforeach;
		
		$tmp = substr($tVal, 0, strlen($tVal)-1);
		$tt = explode(",", $tmp);
		sort($tt);
		
		foreach($tt as $a => $r):
			$te = explode("=", $r);
			$val = array($te[0] => $te[1]);
			array_push($v, $val);
			unset($val);
		endforeach;
		
		return $v;
	}
}
?>