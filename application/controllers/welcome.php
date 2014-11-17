<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	/*****************************
	 * 初始化函数，用于加载本类中使用到的模型
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('href_model','href');
		$this->load->model('pro_info_model','pro_info');
		$this->load->model('user_info_model','user_info');

	}

	/*****************************
	 * 公共函数，用于处理被重复利用的模块
	 */
	/**
	 * 通过项目表查询全部通过审核的项目
	 */

	public function all_aproved_pro()
	{
		$data['pro']= $this->pro_info->check_all();
		foreach ($data['pro'] as $key) {
			//项目进度
			$data['process'][$key['pro_id']]=$this->pro_info->pro_process($key['pro_id']);
			//捐助人数
			$data['don_num'][$key['pro_id']]=$this->pro_info->don_num($key['pro_id']);
			//总金额
			$data['pro_all'][$key['pro_id']]=$this->pro_info->check_pro_all($key['pro_id']);
		}
		return $data;
	}
	// public function all_finished_pro()
	// {
	// 	$data['pro']= $this->pro_info->check_all(2);
	// 	if ($data['pro']) {
	// 	foreach ($data['pro'] as $key) {
	// 		//项目进度
	// 		//捐助人数
	// 		$data['don_num'][$key['pro_id']]=$this->pro_info->don_num($key['pro_id']);
	// 		//总金额
	// 		$data['pro_all'][$key['pro_id']]=$this->pro_info->check_pro_all($key['pro_id']);
	// 	}
	// 	return $data;
	// 	}else{
	// 	return 0;
	// }
	// }
	/**
	 * 分页函数
	 */
	public function pagination_my()
	{
		// 每一页项目数量
		$perPage = 8;
		// 调用分页配置
		$config = Welcome::page_config($perPage);
		// 加载分页库
		$this->load->library('pagination');
		// 初始化分页类
		$this->pagination->initialize($config);
		$offset = $this->uri->segment(3);
		$this->db->limit($perPage, $offset);
		//产生分页
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
	 * 公共配置函数，用于配置各种用到的配置项
	 */
	/**
	 * 配置分页
	 */
	public function page_config($perPage)
	{
		$url = site_url().'/welcome/donatelist';
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
	 * 首页
	 */
	public function index()
	{
		// 查询全部经过审核的项目
		$data = Welcome::all_aproved_pro();
		// 标题
		$data['title'] = "众善网";
		// 友情链接
		$data['href']=$this->href->check_href();
		// 加载主页视图
		$this->load->view('home.html', $data, FALSE);
	}
	/**
	 * 项目列表
	 */
	public function donatelist()
	{

		$data = Welcome::all_aproved_pro();

		// 调用分页方法
		Welcome::pagination_my();
		// 生成分页链接
		$data['links'] = $this->pagination->create_links();

				// 查询全部经过审核的项目
		// $data2 = Welcome::all_finished_pro();

		// $data = array('approve' => $data1,'finish'=>$data2 );

		$data['title'] = "捐助项目";
		$data['href']=$this->href->check_href();
		$this->load->view('donatelist.html', $data, FALSE);
	}

	/**
	 * 帮助中心
	 */
	public function help()
	{
		$data['title'] = "了解众善";
		$data['href']=$this->href->check_href();

		$this->load->view('help.html', $data, FALSE);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
