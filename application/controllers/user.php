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
	/**
	 * 分页函数
	 */
	public function pagination_my()
	{
		// 每一页项目数量
		$perPage = 4;
		// 调用分页配置
		$config = User::page_config($perPage);
		// 加载分页库
		$this->load->library('pagination');
		// 初始化分页类
		$this->pagination->initialize($config);
		$offset = $this->uri->segment(3);
		$this->db->limit($perPage, $offset);
		//产生分页
	}
	/*****************************
	 * 公共配置函数，用于配置各种用到的配置项
	 */
	/**
	 * 配置分页
	 */
	public function page_config($perPage)
	{
		$url = site_url().'/user/s_my';
		// 查询全部经过审核的项目的个数
		$total = count($this->pro_info->check_all());
		// 配置分页类的参数
		$config['base_url'] = $url;   
		$config['total_rows'] = $total;  
		$config['per_page'] = $perPage; //每页条数。   
		$config['page_query_string'] = FALSE;   
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['num_links'] = 2;// 当前连接前后显示页码个数。意思就是说你当前页是第5页，那么你可以看到3、4、5、6、7页。   
		$config['uri_segment'] = 3;   
		$config['use_page_numbers'] = FALSE;  
		return $config;
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

		// 调用分页方法
		User::pagination_my();
		// 生成分页链接
		$data['links'] = $this->pagination->create_links();


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