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
		$today=date('Y-m-d',time());
		$Date_List_a=explode("-",$today);
		$d=mktime(0,0,0,$Date_List_a[1],$Date_List_a[2],$Date_List_a[0]);
		$d2=$d + 3*24*3600;
		$pro_end=date('Y-m-d',$d2);
		$data['pro_end']=$this->pro_info->chec_pro_end($pro_end);


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
	/**
	 * 删除友情链接
	 */
	public function delete_href(){
		$href_id=$this->uri->segment('4');
		$this->load->model('href_model','href');
		$this->href->delete_href($href_id);
		redirect('admin/admin/all_href');
	}
	public function edit_href(){
		$href_id=$this->uri->segment('4');
		$this->load->model('href_model','href');
		$data['href']=$this->href->get_href($href_id);

		$this->load->view('admin/edit_href.html',$data);
	}
	public function update_href(){
		$href_id=$this->input->post('href_id');
		
		$data=array(
			'href_name'=>$this->input->post('href_name'),
			'href_url'=>$this->input->post('href_url')
			);
		$this->load->model('href_model','href');
		$this->href->update_href($href_id,$data);
		success('admin/admin/all_href','修改成功');
	}
	/**
	 * 项目将要到期的通知
	 */
	public function notice(){
			$this->load->model('pro_info_model','pro_info');
			$pro_id=$this->uri->segment(4);
			$pro=$this->pro_info->check_pro_name($pro_id);
			$pro_title=$pro[0]['pro_title'];
			
			$user=$this->pro_info->pro_user($pro_id);
			$pro_user=$this->pro_info->pro_useremail($pro_id);
			$user_name=$user[0]['user_name'];
			$user_email=$pro_user[0]['user_email'];

			// 发邮件
			//配置邮箱信息
			$config['protocol']="smtp";
	    	$config['smtp_host']="smtp.126.com";
	   		$config['smtp_user']="zs_email_server@126.com";
	    	$config['smtp_pass']="zhongshan2014";
	   		$config['crlf']="\n";   
	   		$config['newline']="\n";
			$config['smtp_port'] = 25; 
			$config['charset'] = 'utf-8'; 
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html'; 
			$config['validate'] = true; 
			$config['priority'] = 1; 
			//发送邮件
	   		$this->load->library('email'); 
	   		$this->email->initialize($config);
			$this->email->from('zs_email_server@126.com','众善科技');
			$this->email->to($user_email);
			$this->email->subject('众善科技公司项目审核邮件通知');
			$message="<p>" .$user_name. "，您好：</p>
				<p>您发起的项目： <strong>".$pro_title."</strong></p>
				<p>距离截止日期还有三天，今天的时间为：".date('Y-m-d',time())."，请及时做好准备。欲查看项目详情，请登录众善网查看：{unwrap}www.allheart.cn{unwrap}</p>
				<p>众善科技</p>
				<p>" . date('Y-m-d',time()) . "</p>";
			$this->email->message($message); 
			$this->email->send();
			success('admin/admin/copy',"已通知用户");
	}
}