<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 后台默认控制器
 */
class Admin extends MY_Controller{
	/**
	 * 默认方法
	 */
	public function index(){
		$this->load->view('admin/index.html');
	}


	/**
	 * 默认欢迎
	 */
	public function copy(){
		$this->load->model('donate_model','donate_model');
		$this->load->model('pro_info_model','pro_info');
		//总的捐助金额
		$data['money']=$this->donate_model->donate_check();
		//当前天发起的项目
		$data['new_pro']=$this->donate_model->pro_check(date('Y-m-d',time()));
		//项目当前金额数
		foreach ($data['new_pro'] as  $key) {
			$data['current_money'][$key['pro_id']]=$this->pro_info->check_pro_all($key['pro_id']);
		}
		$this->load->model('pro_info_model','pro_info');
		$this->load->view('admin/copy.html',$data);
	}

	/**
	 * 修改密码
	 */
	public function change(){
		$this->load->view('admin/change_passwd.html');
	}

	/**
	 * 修改动作
	 */
	public function change_passwd(){
		$this->load->model('admin_user_model', 'admin');

		$user_name = $this->session->userdata('user_name');
		$admindata = $this->admin->check_admin_name($user_name);

		// p($admindata);die;
		$passwd = $this->input->post('passwd');
		if(md5($passwd) != $admindata[0]['password']) error('原始密码错误');

		$passwdF = $this->input->post('passwdF');
		$passwdS = $this->input->post('passwdS');

		if($passwdF != $passwdS) error('两次密码不相同');

		
		$user_id = $this->session->userdata('user_id');

		$data = array(
			'password'	=> md5($passwdF)
			);
		$this->admin->update_admin($user_id,$data);

		success('admin/admin/change', '修改成功');
	}


	public function add_href(){
		$this->load->view('admin/addhref.html');
	}

	public function add_href_action(){
		$href_name=$this->input->post('href_name');
		$href_url=$this->input->post('href_url');

		$this->load->model('href_model','href');
		$data=array(
			'href_name'=>$href_name,
			'href_url'=>$href_url
		);
		$this->href->add_href($data);
		success('admin/admin/add_href','添加成功');
	}
	public function all_href(){
		$this->load->model('href_model','href');
		$data['href']=$this->href->all_href();
		$this->load->view('admin/all_href.html',$data);
	}

}