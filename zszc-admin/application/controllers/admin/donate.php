<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donate extends MY_Controller {

	public function index()
	{
		//后台设置后缀为空，否则分页出错
		$this->config->set_item('url_suffix', '');
		//载入分页类
		$this->load->library('pagination');
		$perPage = 10;

		//配置项设置
		$config['base_url'] = site_url('admin/donate/index');
		$config['total_rows'] = $this->db->count_all_results('donate');
		$config['per_page'] = $perPage;
		$config['uri_segment'] = 4;
		$config['first_link'] = '第一页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = '最后一页';

		$this->pagination->initialize($config);

		$data['links'] = $this->pagination->create_links();
		$offset = $this->uri->segment(4);
		$this->db->limit($perPage, $offset);

		
		$this->load->model('donate_model','donate');
		$this->load->model('pro_info_model','pro_info');

		$data['don']=$this->donate->check_all();
		
		foreach ($data['don'] as $key) {
			$data['user_name'][$key['user_id']]=$this->pro_info->check_user_name($key['user_id']);
 			$data['pro_title'][$key['pro_id']]=$this->pro_info->check_name($key['pro_id']);
		}
		$this->load->view('admin/donate.html',$data	);
	}

}

/* End of file donate.php */
/* Location: ./application/controllers/admin/donate.php */