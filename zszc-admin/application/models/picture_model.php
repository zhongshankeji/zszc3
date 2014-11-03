<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Picture_model extends CI_Model {

	
	/**
	 * 图片模型
	 */
	/**
	 * 增加图片
	 */
	function add_pic($data){
		$this->db->insert('picture',$data);
	}
	/**
	 * 删除图片
	 */
	function delete_pic($pic_id){
		$this->db->delete('picture',array('pic_id'=>$pic_id));
	}
	/**
	 * 修改图片
	 */
	function update_pic($pic_id,$data){
		$this->db->update('picture',$data,array('pic_id'=>$pic_id));
	}
	/**
	 * 查询图片
	 */
	function check_pic($pic_id){
		$data=$this->db->where(array('pic_id'=>$pic_id))->get('picture')->result_array();
		return $data;
	}

}

/* End of file picture_model.php */
/* Location: ./application/models/picture_model.php */