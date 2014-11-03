<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	/*****************************
	 * 初始化函数，用于加载本类中使用到的模型
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_info_model','user_info');
		$this->load->model('pro_info_model','pro_info');
		$this->load->model('donate_model','donate');
		$this->load->model('href_model','href');
	}
	/*****************************	 
	 * 常规的加载视图函数，函数中需要有两个必备的变量
	 * 一个是加载标题$data['title']
	 * 一个是加载友情链接 $data['href']
	 * 附加信息都紧贴在加载视图之前，以免对上边数据进行处理产生干扰
	 */
	/**
	 * 个人中心页面显示
	 */
	public function s_my(){

		$uid=$this->session->userdata('user_id');
		//累计捐助的项目个数
		$data['don_num']=$this->donate->check_don_num($uid);
		 //累计发起的项目个数
		$data['pro_num']=$this->user_info->my_pro($uid);
		//累计捐助总金额
		$data['don_all']=$this->user_info->check_don_all($uid);
		 //累计发起的项目
		$data['my_pro']=$this->user_info->check_my($uid);
		$my_pro=$data['my_pro'];
		 //累计捐助的项目
		$data['my_don']=$this->donate->check_my_don($uid);
		$my_don=$data['my_don'];
		 // 项目进度
		foreach ($my_pro as $key) {
			$data['process'][$key['pro_id']]=$this->pro_info->pro_process($key['pro_id']);
		}

		$data['title'] = "用户中心";
		// 友情链接
		$data['href']=$this->href->check_href();
		$this->load->view('my.html',$data,FALSE);
	}

	/**
	 * 个人中心修改密码，此处用于登录后主动修改密码
	 */
	public function p_change(){
		$user_password=$this->input->post('oldpass');
		$user_passwordag=$this->input->post('newpass');

		$user_id=$this->session->userdata('user_id');
		$data_return=$this->user_info->check_user_id($user_id);
		$data=$this->user_info->check_user_id($user_id);	
		$data=array(
			'user_password'=>md5($user_passwordag),
			);
		$this->user_info->update_user($user_id,$data);

		if(!isset($_SESSION)){
			session_start();
		}
		$data_session=array(
			'user_password'=>$data[0]['user_password'],
			);
		$this->session->set_userdata($data_session);
		redirect('user/s_my');
	} 
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */