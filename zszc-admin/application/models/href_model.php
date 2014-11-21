<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 友情链接模型
 */
class Href_model extends CI_Model {

	function add_href($data){
		$this->db->insert('href',$data);
	}
	function all_href(){
		$data=$this->db->get('href')->result_array();
		return $data;
	}
	function delete_href($href_id){
		$this->db->delete('href',array('href_id'=>$href_id));
	}
	function get_href($href_id){
		$data=$this->db->where(array('href_id'=>$href_id))->get('href')->result_array();
		return $data;
	}
	function update_href($href_id,$data){
		$this->db->update('href',$data,array('href_id'=>$href_id));
	}
}

/* End of file href_model.php */
/* Location: ./application/models/href_model.php */ ?>