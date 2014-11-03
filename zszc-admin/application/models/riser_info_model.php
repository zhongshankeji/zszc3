<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Riser_info_model extends CI_Model {

	
	/**
	 * 发起人模型
	 */
	/**
	 * 增加发起人
	 */
	function add_riser($data){
		$this->db->insert('riser_info',$data);
	}
	/**
	 * 删除发起人
	 */
	function delete_riser($riser_id){
		$this->db->delete('riser_info',array('riser_id'=>$riser_id));
	}
	/**
	 * 修改发起人
	 */
	function update_riser($riser_id,$data){
		$this->db->update('riser_info',$data,array('riser_id'=>$riser_id));
	}
	/**
	 * 查询发起人
	 */
	function check_riser($riser_id){
		$data=$this->db->where(array('riser_id'=>$riser_id))->get('riser_info')->result_array();
		return $data;
	}

	function check_riser_pro_id($pro_id){
		$data=$this->db->where(array('pro_id'=>$pro_id))->get('riser_info')->result_array();
		return $data;
}
	public function check_my_rise($uid)
	{
		$data=$this->db->select('riser_info.pro_id, pro_title, pro_goal, pro_end, pro_status')->from('riser_info')->join('pro_info','riser_info.pro_id=pro_info.pro_id')->where(array('user_id'=>$uid))->order_by('pro_id','asc')->get()->result_array();
		return $data;
	}

}

/* End of file riser_info.php */
/* Location: ./application/models/riser_info.php */