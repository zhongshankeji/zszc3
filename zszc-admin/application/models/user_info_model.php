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
	 * 修改用户
	 */
	function update_user($user_id,$data){
		$this->db->update('user_info',$data,array('user_id'=>$user_id));
	}
	/**
	 * 查询用户
	 */
	function check_user($user_name){
		$data=$this->db->where(array('user_name'=>$user_name))->get('user_info')->result_array();
		return $data;
	}
	function check_user_user_id($user_id){
		$data=$this->db->where(array('user_id'=>$user_id))->get('user_info')->result_array();
		return $data;
	}
}

/* End of file user_info_model.php */
/* Location: ./application/models/user_info_model.php */