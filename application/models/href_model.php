<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Href_model extends CI_Model {
	function check_href(){
		$data=$this->db->get('href')->result_array();
		return $data;
	}
}