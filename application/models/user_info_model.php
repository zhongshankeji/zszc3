<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_info_model extends CI_Model {

	/**
	 * 用户模型
	 */
	/**
	 * 增加用户
	 */
	function add_user($data){
		$this->db->insert('user_info',$data);
	}
	/**
	 * 删除用户
	 */
	function delete_user($user_id){
		$this->db->delete('user_info',array('user_id'=>$user_id));
	}
	/**
	 * 修改用户真实姓名，手机
	 */
	function update_user($user_id,$data){
		$this->db->update('user_info',$data,array('user_id'=>$user_id));
	}
	function update_user_password($user_name,$data){
		$this->db->update('user_info',$data,array('user_name'=>$user_name));
	}
	/**
	 * 查询用户
	 */
	function check_user($user_name){
		$data=$this->db->where(array('user_name'=>$user_name))->get('user_info')->result_array();
		return $data;
	}
	function check_user_email($user_email){
		$data=$this->db->where(array('user_email'=>$user_email))->get('user_info')->result_array();
		return $data;
	}
	function check_user_id($user_id){
		$data=$this->db->where(array('user_id'=>$user_id))->get('user_info')->result_array();
		return $data;
	}
	function check_user_name($user_name){
		$data=$this->db->where(array('user_name'=>$user_name))->get('user_info')->result_array();
		return $data;
	}
	/**
	 * 查询用户所有的项目
	 */
	function check_my($uid){
		$data=$this->db->where(array('user_id'=>$uid))->order_by('pro_id','asc')->get('pro_info')->result_array();
		if(is_null($data)){
			return $data=array('尚未发起任何项目');
		}else{
			return $data;
		}
		
	}
	/**
	 * 所发起项目个数
	 */
	function  my_pro($uid){
		$this->db->select('pro_id')->from('pro_info')->where(array('user_id'=>$uid));
		$num=$this->db->count_all_results();
		if($num){
			return $num;
		}else{
			return 0;
		}
	}
	/**
	 * 通过项目ID查询用户
	 *
	 */
	function check_riser_pro_id($pro_id){
		$data=$this->db->select('user_name')->from('pro_info')->join('user_info','pro_info.user_id=user_info.user_id')->where(array('pro_id'=>$pro_id))->get()->result_array();
		if($data){
			return $data;
		}else{
			$data=array('data'=>0);
			return $data;
		}
	}
	/**
	 * 通过项目ID查询用户所有信息
	 *
	 */
	function check_riser_all_pro_id($pro_id){
		$data=$this->db->from('pro_info')->join('user_info','pro_info.user_id=user_info.user_id')->where(array('pro_info.pro_id'=>$pro_id))->get()->result_array();
		return $data;
		}
	/**
	 * 查询用户所捐助总金额
	 */
	function check_don_all($uid){
		$data=$this->db->select('don_money')->from('donate')->where(array('user_id'=>$uid))->get()->result_array();
		$num=0;
		$v=count($data);
		foreach ($data as $v) {
			$num += $v['don_money'];
			
		}
		return $num;
	}
}

/* End of file user_info_model.php */
/* Location: ./application/models/user_info_model.php */