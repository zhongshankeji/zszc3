<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donate_model extends CI_Model {

	function check_all(){
		$data=$this->db->get('donate')->result_array();
		return $data;
	}
	
}

/* End of file donate_model.php */
/* Location: ./application/models/donate_model.php */