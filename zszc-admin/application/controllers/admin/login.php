<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 后台登陆控制器
 */
class Login extends CI_Controller{
	/**
	 * 登陆默认方法
	 * @return [type] [description]
	 */
	public function index(){
		$this->load->view('admin/login.html');
	}

	/**
	 * 登陆
	 */
	public function login_in(){
		//$code = $this->input->post('captcha');
		if(!isset($_SESSION)){
			session_start();
		}
		//if(strtoupper($code) != $_SESSION['code']) error('验证码错误');

		$user_name = $this->input->post('user_name');
		$this->load->model('admin_user_model', 'admin');
		$userdata = $this->admin->check_admin_name($user_name);

		$password = $this->input->post('password');

		if(!$userdata || $userdata[0]['password'] != md5($password)) error('用户名或者密码不正确');

		$sessionData = array(
			'user_name'	=> $user_name,
			'user_id'		=> $userdata[0]['user_id'],
			'logintime' => time()
			);

		$this->session->set_userdata($sessionData);
		
		redirect('admin/admin/index');

	}
	/**
	 * 退出登陆
	 */
	public function login_out(){
		$this->session->sess_destroy();
		success('admin/login/index','退出成功');
	}
}