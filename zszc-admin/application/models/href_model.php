<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Href_model extends CI_Model {

	function add_href($data){
		$this->db->insert('href',$data);
	}
	function all_href(){
		$data=$this->db->get('href')->result_array();
		return $data;
	}
}

/* End of file href_model.php */
/* Location: ./application/models/href_model.php */ ?>