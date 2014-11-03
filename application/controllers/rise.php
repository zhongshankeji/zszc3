<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rise extends MY_Controller {

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
	 * 上传图片,同时在此处设置缩略图大小
	 */
	public function pic_upload()
	{
		$config = Rise::pic_upload_config();
		
		$this->load->library('upload',$config);
		$status=$this->upload->do_upload('pro_img');
		$wrong=$this->upload->display_errors();
		$info=$this->upload->data();
		//缩略图
		$arr['source_image'] = $info['full_path'];
		$arr['create_thumb'] = FALSE;
		$arr['maintain_ratio'] = TRUE;
		$arr['width'] =368;
		$arr['height'] =256;

		$this->load->library('image_lib',$arr);
		$status=$this->image_lib->resize();
		return $info;
	}
	/*****************************
	 * 公共配置函数，用于配置各种用到的配置项
	 */
	/**
	 * 配置图片上传
	 */
	public function pic_upload_config()
	{
		//上传图片
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = '10000';
		$config['file_name'] = time() . mt_rand(1000,9999).'.jpg';
		return $config;
	}
	/*****************************	 
	 * 常规的加载视图函数，函数中需要有两个必备的变量
	 * 一个是加载标题$data['title']
	 * 一个是加载友情链接 $data['href']
	 * 附加信息都紧贴在加载视图之前，以免对上边数据进行处理产生干扰
	 */

	/**
	 * 加载发起项目视图
	 */
	public function index()
	{	
		$data['title']='发起项目';
		// 友情链接
		$data['href']=$this->href->check_href();

		$this->load->view('rise.html',$data,FALSE);
	}
	/**
	 * 发起项目处理
	 */
	public function p_rise(){
		// 上传图片
		Rise::pic_upload();

		// 获取用户ID
		$user_id=$this->session->userdata('user_id');
		// 获取ueditor内容
		$content =  htmlspecialchars($_POST['editorValue']);
		// 获取项目发起信息
		$data=array(
			'pro_title'=>$this->input->post('pro_title'),
			'pro_goal'=>$this->input->post('pro_goal'),
			'pro_dur'=>$this->input->post('pro_dur'),
			'pro_img'=>$info['file_name'],
			'pro_des'=>$this->input->post('pro_des'),
			'pro_video'=>$this->input->post('pro_video'),
			'user_id'=>$user_id,
			'pro_det'=>$content
			);
		// 通过项目名称查到钢发起的项目信息
		$pro=$this->pro_info->check_pro_name($this->input->post('pro_title'));
		if(!empty($pro)){
			error('该项目名称已经存在，请更换');
		}
		$this->pro_info->add_pro($data);
		$pro_id=$this->db->insert_id();
		// 获取发起人信息
		$user_info=array(
			'user_phone'=>$this->input->post('user_phone'),
			'user_real_name'=>$this->input->post('user_real_name'),
			'user_bank_name'=>$this->input->post('user_bank_name'),
			'user_subbank_name'=>$this->input->post('user_subbank_name'),
			'user_bank_holder'=>$this->input->post('user_bank_holder'),
			'user_bank_num'=>$this->input->post('user_bank_num')
			);
		$this->user_info->update_user($user_id,$user_info);
		// 跳转到预览页面
		redirect('pro/thumb/'.$pro_id);
	}
	/**
	 * 显示编辑页面
	 */
	public function pro_edit()
	{
		$data['title'] = "发起项目";

		$pro_id=$this->uri->segment(3);
		$data['user']=$this->user_info->check_riser_all_pro_id($pro_id);
		$data['href']=$this->href->check_href();
		$this->load->view('edit.html', $data, FALSE);
	}
	/**
	 * 编辑项目
	 */
	public function p_edit()
	{
		// 上传图片
		$info=Rise::pic_upload();
		
		$user_id=$this->session->userdata('user_id');
		$content = htmlspecialchars($this->input->post('editorValue'));
		$pro_id= $this->input->post('pro_id');
		// 更新项目表
		$data=array(
			'pro_title'=>$this->input->post('pro_title'),
			'pro_goal'=>$this->input->post('pro_goal'),
			'pro_dur'=>$this->input->post('pro_dur'),
			'pro_img'=>$info['file_name'],
			'pro_des'=>$this->input->post('pro_des'),
			'pro_video'=>$this->input->post('pro_video'),
			'user_id'=>$user_id,
			'pro_det'=>$content
			);
		$this->pro_info->update_pro($pro_id,$data);
		// 更新发起人表
		$user_info=array(
			'user_phone'=>$this->input->post('user_phone'),
			'user_real_name'=>$this->input->post('user_real_name'),
			'user_bank_name'=>$this->input->post('user_bank_name'),
			'user_subbank_name'=>$this->input->post('user_subbank_name'),
			'user_bank_holder'=>$this->input->post('user_bank_holder'),
			'user_bank_num'=>$this->input->post('user_bank_num')
			);
		$this->user_info->update_user($user_id,$user_info);
		// 跳转到项目预览页
		redirect('pro/thumb/'.$pro_id);
	}
}

/* End of file rise.php */
/* Location: ./application/controllers/rise.php */