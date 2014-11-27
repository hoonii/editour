<?php if ( ! defined('WIDGET')) exit('No direct script access allowed');

class Tail extends Widget {
	function index($data=FALSE) {
		$widget = FALSE;
		if (defined('WIDGET_SKIN'))
			$widget = WIDGET_SKIN;
		elseif (defined('BO_TABLE'))
			$widget = BO_TAIL;

		if ($widget)
			$this->$widget($data);

		$js = isset($data['js']) ? $data['js'] : array();
		
// 		$this->load->view('_tail', array('js' => $js));
		
		if(strrpos($this->input->server('REQUEST_URI'), "admin") == false){
			$this->load->view('_tail', array('js' => $js));
		} else {
			$this->load->view('_admin_tail', array('js' => $js));
		}
	}

	function admin($admin=FALSE) {
        $this->load->view('admin/tail', $admin);
    }

	function adm($admin=FALSE) {
        $this->load->view(ADM_F.'/tail', $admin);
    }

	function main($main=FALSE) {
		$this->load->view('main/tail', $main);
	}

	function popup($main=FALSE) {
		$this->load->view('popup/tail', $main);
	}

	function temp($main=FALSE) {
		$this->load->view('temp/tail', $main);
	}

	function emptySkin($main=FALSE) {
		$this->load->view('empty/tail', $main);
	}
}