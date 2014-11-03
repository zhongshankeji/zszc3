<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class finace_model extends CI_Model{	/**
	 * 金额模型
	 */
	/**
	 * 增加金额
	 */
	function add_fina($data){
		$this->db->insert('finace',$data);
	}
	/**
	 * 删除金额
	 */
	function delete_fina($fina_id){
		$this->db->delete('finace',array('id'=>$fina_id));
	}
	/**
	 * 修改金额
	 */
	function update_fina($fina_id,$data){
		$this->db->update('finace',$data,array('id'=>$fina_id));
	}
	/**
	 * 查询金额
	 */
	function check_fina($fina_id){
		
		$data=$this->db->select('id,user_id,pro_title,money')->from('finace')->join('pro_info','finace.pro_id=pro_info.pro_id')->order_by('id','asc')->get()->result_array();
		return $data;
	}
	public function check_my_fina($uid)
	{
		$data=$this->db->select('pro_title, pro_goal, money, pro_end, pro_status')->from('finace')->join('pro_info','finace.pro_id=pro_info.pro_id')->where(array('user_id'=>$uid))->order_by('id','asc')->get()->result_array();
		return $data;
	}
	/**
	 * 查询某个项目总共捐助金额
	 */
	function fina_check($pro_id){
		
		$data=$this->db->select_sum('money')->from('finace')->where(array('pro_id'=>$pro_id))->get()->result_array();
		return $data;
	}
	/**
	 * 查询某个项目总共捐助人数
	 */
	public function fina_donater_sum($pro_id)
	{
		$data=$this->db->select_sum('money')->from('finace')->where(array('pro_id'=>$pro_id));
		return $this->db->count_all_results();
	}
	
}

/* End of file finace_model.php */
/* Location: ./application/models/finace_model.php */