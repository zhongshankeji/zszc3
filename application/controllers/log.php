<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 本类用于处理用户登入登出等相关问题，所有函数均可无登录访问使用
 */
class Log extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('href_model','href');
		$this->load->model('user_info_model','user_info');
	}
	/*****************************
	 * 公共函数，用于处理被重复利用的模块
	 */
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
		$this->load->helper('cookie');
		$this->input->set_cookie('user_name',$data_session['user_name'],86500,'.allheart.cn');
		$this->input->set_cookie('user_id',$user_id,86500,'.allheart.cn');

	}
	/**
	 * 发送邮件
	 */
	public function send_email($user_email, $user_name,$subject)
	{
		//发送邮件
   		$this->load->library('email'); 
   		$this->email->initialize(Welcome::send_email_config());
		$this->email->from('zs_email_server@126.com','众善科技');
		$this->email->to($user_email);
		$this->email->subject($subject);

		switch ($subject) {
			case '众善网密码找回':
				$message="<p>" .$user_name. "，你好：</p>
				<p>点击下面的链接修改密码：</p>
				{unwrap}".site_url('log/s_change_forget/'.$user_name.'/'.date('Y-m-d',time()))."{/unwrap}
				<p>(如果链接无法点击，请将它拷贝到浏览器的地址栏中。)</p>
				<p>众善科技</p>
				<p>" . date('Y-m-d',time()) . "</p>";				
			break;
			
			default:
				$message = "众善科技官方邀请邮件: http://www.allheart.cn  欢迎注册";
				break;
		}

		$this->email->message($message); 

		return $this->email->send();
	}

	/*****************************
	 * 公共配置函数，用于配置各种用到的配置项
	 */

	/**
	 * 配置发送邮件
	 */
	public function send_email_config()
	{
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

		return $config;
	}
	/*****************************	 
	 * 常规的加载视图函数，函数中需要有两个必备的变量
	 * 一个是加载标题$data['title']
	 * 一个是加载友情链接 $data['href']
	 * 附加信息都紧贴在加载视图之前，以免对上边数据进行处理产生干扰
	 */

	/**
	 * 用户注册页面加载
	 */
	public function s_signup()
	{	
		$data['title'] = "注册";
		$data['href']=$this->href->check_href();
		$this->load->view('signup.html',$data, FALSE);

	}
	/**
	 * 用户注册 处理函数
	 */
	public function p_signup()
	{
		if(!isset($_SESSION)){
			session_start();
		}
		
		$user_name=$this->input->post('user_name');
		$user_password=$this->input->post('user_password');
		$user_email = $this->input->post('user_email');

		$data=$this->user_info->check_user($user_name);

		if($data){
			error('该用户名已经被注册了，请更换！');
		}
		//获取页面数据，添加到数据库
		$data=array(
			'user_name'=>$user_name,
			'user_password'=>md5($this->input->post('user_password')),
			'user_email' =>$user_email
			);
		$this->user_info->add_user($data);
		//通过新注册的用户名查询新插入用户的ID
		$data_session=$this->user_info->check_user($user_name);
		$user_id=$data_session[0]['user_id'];
		//将新注册用户的信息写入SESSION
		$session_userdata=array(
			'user_id'=>$user_id,
			'user_name'=>$user_name,
			'sign_time'=>time(),
			'user_email'=>$user_email
			);
		$this->session->set_userdata($session_userdata);
		redirect('welcome/index');
	}
	/**
	 * 展示忘记密码页面
	 */
	public function s_forget()
	{
		$data['title'] = "忘记密码";
		$data['href']=$this->href->check_href();
		$this->load->view('forget.html',$data, FALSE);	
	}
	/**
	 * 修改密码页面
	 */
	public function p_forget()
	{
		$user_name=$this->input->post('user_name');
		$user_email=$this->input->post('user_email');

		$subject = "众善网密码找回";
		// 调用邮件发送函数
		$status = Log::send_email($user_email, $user_name, $subject);

		if ($status) {
			success('welcome/index','已发送成功');
		}else{
			error("邮件发送失败，请核对信息！");
		}
	}
	/**
	 * 显示修改密码页
	 */
	public function s_change_forget(){
		$data['title'] = "更改密码";
		$data['user_name']=$this->uri->segment(3);
		$data['href']=$this->href->check_href();
		$this->load->view('set_newpassword.html', $data, FALSE);	
	}
	/**
	 * 处理修改密码
	 */
	public function p_change_forget(){

		$user_name=$this->input->post('user_name');
		$user_password=$this->input->post('user_password');
		$user_passwordag=$this->input->post('user_passwordag');

		$data_return=$this->user_info->check_user($user_name);

		if(!$data_return){
			error('用户名不存在');
		}
		$data=array(
			'user_password'=>md5($user_passwordag),
			);
		$this->user_info->update_user_password($user_name,$data);
		// 写入session
		Log::write_session($data_return[0]['user_id'],$user_name,$user_password);		

		redirect('welcome/index');
	}
	/**
	 * 登录页面视图加载
	 */
	public function s_signin(){

		$data['title'] = "登录";
		$data['href']=$this->href->check_href();

		$this->load->view('login.html',$data, FALSE);
	}
	/**
	 * 用户登录处理
	 */
	public function p_signin(){
		

		$user_name=$this->input->post('user_name');
		$user_password=$this->input->post('user_password');

		$data=$this->user_info->check_user($user_name);

		if(!$data||$data[0]['user_password']!=md5($user_password)){
			error('用户名或密码错误');
		}
		// 写入session
		Log::write_session($data[0]['user_id'], $user_name);
		p($data);die;
		// SSO
		$expire = "3600";
		$key = "fuchao2012forzhongshankeji";

		$encode_data = array( 
		　　　'uid'=>$data[0]['user_id'], 
		　　　'uname'=>$user_name,
		　　　'expire'=>$expire
		);
		setcookie('syncuyan', des_encrypt(json_encode($encode_data), $key), time() + 3600, '/', '');

		redirect('welcome/index');
	}
	/**
	 * 退出登录
	 */
	public function quit(){

		$data['title'] = "登录";
		$data['href']=$this->href->check_href();
		$this->session->sess_destroy();
		// 退出SSO
		setcookie('syncuyan', 'logout', time() + 3600, '/', 'blog.jiathis.com');
		$this->load->view('login.html',$data,FALSE);
	}

}

/* End of file log.php */
/* Location: ./application/controllers/log.php */