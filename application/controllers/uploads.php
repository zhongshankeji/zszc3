<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploads extends CI_Controller {

	function __construct(){
		parent::__construct();
  		$this->load->helper(array('form'));
 	}

 	function index(){

  		$config['upload_path'] = base_url().'/uploads/';
  		$config['allowed_types'] = 'gif|jpg|png';
  		$config['max_size'] = '256';
  		$config['max_width']  = '1024';
  		$config['max_height']  = '768';
  
  		$this->load->library('upload', $config);
 
  		if (!$this->upload->do_upload()){
   			$error = array('error' => $this->upload->display_errors());
   			$this->load->view('upload_form', $error);
  		} else {
   			$data = array('upload_data' => $this->upload->data());
   			$this->load->view('upload_success', $data);
		}
	}
}

/* End of file uploads.php */
/* Location: ./application/controllers/uploads.php */