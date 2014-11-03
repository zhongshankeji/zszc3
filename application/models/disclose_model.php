<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Disclose_model extends CI_Model {
/**
 * 披露模型
 */
/**
 * 添加披露
 */
	function add_disc($data){
		$this->db->insert('disclose',$data);
	}
/**
 * 删除披露
 */
	function delete_disc($disc_id){
		$this->db->delete('disclose',array('disc_id'=>$disc_id));
	}
/**
 * 修改披露
 */
	function update_disc($disc_id,$data){
		$this->db->update('disclose',$data,array('disc_id'=>$disc_id));
	}
/**
 * 查询披露
 */
	function check_disc($disc_id){
		$data=$this->db->where(array('disc_id'=>$disc_id))->get('disclose')->result_array();
		return $data;
	}
}

/* End of file disclose_model.php */
/* Location: ./application/models/disclose_model.php */