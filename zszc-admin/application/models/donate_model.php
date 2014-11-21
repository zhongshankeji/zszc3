<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donate_model extends CI_Model {

	function check_all(){
		$data=$this->db->get('donate')->result_array();
		return $data;
	}
	/**
	 * 查询总共捐助金额
	 */
	function donate_check(){
		$donate=$this->db->select_sum('don_money')->from('donate')->get()->result_array();
		if ($donate) {
			return $donate;
		}else{
			$donate = array('donate' => 0);
			return $donate;
		}
		
	}
	function pro_check($date){
		$data=$this->db->where(array('pro_start'=>$date))->get('pro_info')->result_array();
		return $data;
	}
}

/* End of file donate_model.php */
/* Location: ./application/models/donate_model.php */