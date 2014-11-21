<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$user_name=$this->session->userdata('user_name');
		$user_id=$this->session->userdata('user_id');
		
		if(!$user_name||!$user_id){
			redirect('admin/login/index');
		}
	}
}

/* End of file MY_Controller */
/* Location: ./application/core/MY_Controller */