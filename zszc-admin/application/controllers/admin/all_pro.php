<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All_pro extends MY_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pro_info_model','pro');

	}
	/**
	 * 查看项目
	 */
	public function index(){
		//后台设置后缀为空，否则分页出错
		$this->config->set_item('url_suffix', '');
		//载入分页类
		$this->load->library('pagination');
		$perPage = 8;

		//配置项设置
		$config['base_url'] = site_url('admin/all_pro/index');
		$config['total_rows'] = $this->db->count_all_results('pro_info');
		$config['per_page'] = $perPage;
		$config['uri_segment'] = 4;
		$config['first_link'] = '第一页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = '最后一页';

		$this->pagination->initialize($config);

		$data['links'] = $this->pagination->create_links();
		// p($data);die;
		$offset = $this->uri->segment(4);
		$this->db->limit($perPage, $offset);
		

		$data['pro'] = $this->pro->check_all_pro_xx();

		// p($data);die;
		$this->load->view('admin/all_pro.html', $data);
	}
	/**
	 * 发表项目模板显示
	 */
	public function send_pro(){
		$this->load->helper('form');
		$this->load->view('admin/send_pro.html');
	}	

	/**
	 * 发表项目动作
	 */
	public function send(){
		//上传图片
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = '10000';
		$config['file_name'] = time() . mt_rand(1000,9999);

		$this->load->library('upload',$config);
		$status=$this->upload->do_upload('pro_img');
		
		$wrong=$this->upload->display_errors();
		$info=$this->upload->data();
		//缩略图
		$arr['source_image'] = $info['full_path'];
		$arr['create_thumb'] = False;
		$arr['maintain_ratio'] = TRUE;
		$arr['width'] =368;
		$arr['height'] =256;

		$this->load->library('image_lib',$arr);
		$status=$this->image_lib->resize();
			$this->load->model('article_model', 'art');
			
			$project = array(
				'pro_title'	=> $this->input->post('pro_title'),
				'pro_status'	=> $this->input->post('pro_status'),
				'pro_goal'	=> $this->input->post('pro_goal'),
				'pro_dur'=>$this->input->post('pro_dur'),
				'pro_des'=>$this->input->post('pro_des'),
				'pro_img'=>$this->input->post('pro_img'),
				);	
			

			$this->pro->add_pro($project);
			$pro_id=$this->db->insert_id();
			
			$return=$this->pro->check_pro($pro_id);
			$data['pro']=$return;

			$data['pic']=explode(';', $return[0]['pro_pic_url']);

			$this->load->view('admin/pro_detail.html',$data);
		
		
}

	/**
	 * 编辑项目
	 */

	public function edit_pro(){
		$pro_id=$this->uri->segment(4);
		$return=$this->pro->check_pro($pro_id);
		$data['pro']=$return;
		
		$this->load->helper('form');
		$this->load->view('admin/edit_pro.html',$data);
	}


	/**
	 * 编辑动作
	 */
	public function edit(){
		$this->load->library('form_validation');
		$status=$this->form_validation->run('pro');
		if($status){
			$pro_id=$this->input->post('pro_id');
			$pro_title=$this->input->post('pro_title');
			$pro_status=$this->input->post('pro_status');
			$pro_goal=$this->input->post('pro_goal');
			$pro_start=$this->input->post('pro_start');
			$pro_end=$this->input->post('pro_end');
			$pro_text=$this->input->post('pro_text	');

		
			$data=array(
			
			'pro_title'=>$pro_title,
			'pro_status'=>$pro_status,
			'pro_goal'=>$pro_goal,
			'pro_start'=>$pro_start,
			'pro_end'=>$pro_end,
			'pro_text'=>$pro_text,
			'pro_remark'=>0
			);

			$this->pro->update_pro($pro_id,$data);
			success('admin/all_pro/index','修改成功');
		} else {
			$this->load->helper('form');
			$this->load->view('admin/edit_article.html');
		}
	}

	function delete_pro($pro_id){
		$this->pro->delete_pro($pro_id);
		success('admin/all_pro/index','删除成功');
	}





}