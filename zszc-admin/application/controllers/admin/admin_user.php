<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_user extends MY_Controller{
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_user_model', 'admin');
	}
	/**
	 * 查看用户	 */
	public function index(){
		$this->load->library('pagination');
		$per_page=10;

		$config['base_url'] = site_url('admin/admin_user/index');
		$config['total_rows'] = $this->db->count_all_results('admin_user');
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$config['first_link'] = '第一页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = '最后一页';

		$this->pagination->initialize($config);
		$data['links']=$this->pagination->create_links();
		$offset=$this->uri->segment(4);
		$this->db->limit($per_page,$offset);
		
		$data['admin_user'] = $this->admin->check_all_admin();
		$this->load->view('admin/admin_user.html', $data);
	}
	/**
	 * 添加用户	 */
	public function add_admin(){
		$this->load->helper('form');
		$this->load->view('admin/add_admin.html');
	}

	/**
	 * 添加动作
	 */
	public function add(){
		 $this->load->library('form_validation');
		$status = $this->form_validation->run('admin');
		if($status){
			
		$user_name=$this->input->post('user_name');	
		$user_password=$this->input->post('password');
		$user_password2=$this->input->post('password2');
		$user_type=$this->input->post('type');
		$userdata=$this->admin->check_admin_name($user_name);

		if(!isset($userdata)){
			error('用户名已经存在');
		}
		if($user_password != $user_password2){
			error('两个密码不一样');
		}
		if($user_type==1){
			$data = array(
				'user_name'	=> $user_name,
				'password'=>md5($user_password)
				);

			$this->admin->add_admin($data);
			success('admin/admin_user/index', '添加成功');
	}
	else if($user_type==0){
		$data=array(
			'user_name'=>$user_name,
			'user_password'=>md5($user_password)
			);
		$this->load->model('user_info_model','user');
		$this->user->add_user($data);
		success('admin/admin_user/index','添加成功');
	}
	}else{
		$this->load->helper('form');
		$this->load->view('admin/add_admin.html');
	}
}

	/**
	 * 编辑
	 */
	public function edit_admin(){
		$user_id = $this->uri->segment(4);
		// echo $cid;die;

		$data['admin'] = $this->admin->check_admin($user_id);

		$this->load->helper('form');
		$this->load->view('admin/edit_admin.html', $data);
	}


	/**
	 * 编辑动作
	 */
	public function edit(){

			$user_id = $this->input->post('user_id');
			$user_name = $this->input->post('user_name');

			$data = array(
				'user_name'	=> $user_name
				);

			$data['admin'] = $this->admin->update_admin($user_id, $data);
			success('admin/admin_user/index', '修改成功');
	}


	/**
	 * 删除用户	 */
	public function del_user(){
		$user_id = $this->uri->segment(4);
		$this->admin->del_admin($user_id);
		success('admin/admin_user/index', '删除成功');
	}








}