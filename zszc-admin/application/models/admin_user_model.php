<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户管理员模型
 */
class admin_user_model extends CI_Model{
	/**
	 * 添加
	 */
	public function add_admin($data){
		$this->db->insert('admin_user', $data);
	}

	/**
	 * 查看
	 */
	public function check_all_admin(){
		$data = $this->db->get('admin_user')->result_array();
		return $data;
	}


	/**
	 * 查询对应
	 */
	public function check_admin($user_id){
		$data = $this->db->where(array('user_id'=>$user_id))->get('admin_user')->result_array();
		return $data;
	}

public function check_admin_name($user_name){
		$data = $this->db->where(array('user_name'=>$user_name))->get('admin_user')->result_array();
		return $data;
	}
	/**
	 * 修改
	 */
	public function update_admin($user_id, $data){
		$this->db->update('admin_user', $data, array('user_id'=>$user_id));
	}


	/**
	 * 删除
	 */
	public function del_admin($user_id){
		$this->db->delete('admin_user', array('user_id'=>$user_id));
	}


	/**
	 * 调取导航栏
	 */
	public function limit_category($limit){
		$data = $this->db->limit($limit)->get('admin_user')->result_array();
		return $data;
	}















}