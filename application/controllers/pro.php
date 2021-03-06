<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pro extends CI_Controller{
	/*****************************
	 * 初始化函数，用于加载本类中使用到的模型
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_info_model','user_info');
		$this->load->model('pro_info_model','pro_info');
		$this->load->model('donate_model','don');
		$this->load->model('href_model','href');
		$this->load->model('disclose_model','disc');

	}
	/**
	 * 写入session
	 */
	public function write_session($user_id, $user_name, $user_passwordag='')
	{
		if(!isset($_SESSION)){
		 	session_start();
		}

		$data_session=array(
		 	'user_name'=>$user_name,
		 	'user_id'=>$user_id,
		 	'sign_time'=>time(),
		 	'user_password'=>$user_passwordag
		 	);
		$this->session->set_userdata($data_session);
	}
	/*****************************	 
	 * 常规的加载视图函数，函数中需要有两个必备的变量
	 * 一个是加载标题$data['title']
	 * 一个是加载友情链接 $data['href']
	 * 附加信息都紧贴在加载视图之前，以免对上边数据进行处理产生干扰
	 */

	/**
	 * 项目详情页
	 */
	public function thumb(){
		if ($this->uri->segment(4)) {
			$user_id = $this->uri->segment(4);
			$user=$this->user_info->check_user_id($user_id);
			$user_name=$user['user_name'];
			Pro::write_session($user_id, $user_name);
		}

		$pro_id=$this->uri->segment(3);

		$data['pro_id']=$pro_id;
		// 项目信息
		$data['pro']=$this->pro_info->check_pro($pro_id);
		// 项目总捐助人数
		$data['pro_all']=$this->pro_info->check_pro_all($pro_id);
		// 项目捐助总额
		$data['don_num']=$this->pro_info->don_num($pro_id);
		// 项目发起人
		$data['pro_user']=$this->pro_info->pro_user($pro_id);
		//项目捐赠者
		$data['donate'] = $this->don->donator_check_all($pro_id);

		foreach ($data['donate'] as  $value) {
		 	$data['donator'][$value['user_id']]=$this->don->user_name_user_id($value['user_id']);
		}
		//项目披露
		$data['disc'] = $this->disc->check_disc_proid($pro_id);

		$data['title'] = "项目详情";
		$data['href']=$this->href->check_href();
		$this->load->view('project.html',$data);
	}
}

/* End of file pro.php */
/* Location: ./application/controllers/pro.php */