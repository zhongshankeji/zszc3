<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Don extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pro_info_model','pro_info');
		$this->load->model('user_info_model','user_info');
		$this->load->model('donate_model','donate');
	}
	/**
	 * 确认捐助
	 */
	public function sure_donate(){
		// 配置支付工具所需参数
		$pro_id=$this->input->post('pro_id');
		$don_money=$this->input->post('don_money');
		$bank_id=$this->input->post('001');

		$agentbillid=substr(md5(rand(0,100000)),0,32);
		
		$project=$this->pro_info->check_pro($pro_id);
		$user=$this->pro_info->pro_user($pro_id);

		$pro_title=$project[0]['pro_title'];
		$user_name=$this->session->userdata('user_name');
		//获取IP
		$onlineip = "";
		
		if(getenv('HTTP_CLIENT_IP')){
		$onlineip=getenv('HTTP_CLIENT_IP');
		}elseif(getenv('HTTP_X_FORWARDED_FOR')){
		$onlineip=getenv('HTTP_X_FORWARDED_FOR');
		}else{
		$onlineip=getenv('REMOTE_ADDR');
		}
		//数据包中的数据获取
		$version=1;
		$agent_id="1852365";
		$agent_bill_id=$agentbillid;
		$agent_bill_time=date('YmdHis', time());
		$pay_type=0;
		$pay_code=$bank_id;
		$pay_amt=$don_money;

		//汇付宝返回信息跳转页URL
		$notify_url='http://www.allheart.cn/style/'."Notify.php";
		$return_url=site_url('don/return_url');

		$user_ip=$onlineip;
		$goods_name=$pro_title;
		$goods_num=1;
		$goods_note='none';
		$remark=$pro_id.'/'.$user_name;
		
		$key = "78A19858A9FD41EE8CAFE170";
		//数据签名组成
		$signStr='';
		$signStr  = $signStr . 'version=' . $version;
		$signStr  = $signStr . '&agent_id=' . $agent_id;
		$signStr  = $signStr . '&agent_bill_id=' . $agent_bill_id;
		$signStr  = $signStr . '&agent_bill_time=' . $agent_bill_time;
		$signStr  = $signStr . '&pay_type=' . $pay_type;
		$signStr  = $signStr . '&pay_amt=' . $pay_amt;
		$signStr  = $signStr . '&notify_url=' . $notify_url;
		$signStr  = $signStr . '&return_url=' . $return_url;
		$signStr  = $signStr . '&user_ip=' . $user_ip;

		$signStr = $signStr . '&key=' . $key;
		//获取sign密钥
		$sign='';
		$sign=md5($signStr);

		//将数据发送到汇付宝接口进行处理
		$data['version']=$version;
		$data['agent_id']=$agent_id;
		$data['agent_bill_id']=$agent_bill_id;
		$data['agent_bill_time']=$agent_bill_time;
		$data['pay_type']=$pay_type;

		$data['pay_code']=$pay_code;
		$data['pay_amt']=$pay_amt;
		$data['notify_url']=$notify_url;
		$data['return_url']=$return_url;
		$data['user_ip']=$user_ip;
		$data['goods_name']=$goods_name;
		$data['goods_num']=$goods_num;
		$data['goods_note']=$goods_note;
		$data['is_test']=1;
		$data['remark']=$remark;
		$data['key']=$key;

		$data['sign']=$sign;

		$this->load->view('donate.html',$data);

	}
	public function return_url(){
		// 获取第三方支付返回的数据包
		$result=$_GET['result'];
		$pay_message=$_GET['pay_message'];
		$agent_id=$_GET['agent_id'];
		$jnet_bill_no=$_GET['jnet_bill_no'];
		$agent_bill_id=$_GET['agent_bill_id'];
		$pay_type=$_GET['pay_type'];
		
		$pay_amt=$_GET['pay_amt'];
		$remark=$_GET['remark'];

		$returnSign=$_GET['sign'];
		$key = '78A19858A9FD41EE8CAFE170';
		
		$signStr='';
		$signStr  = $signStr . 'result=' . $result;
		$signStr  = $signStr . '&agent_id=' . $agent_id;
		$signStr  = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
		$signStr  = $signStr . '&agent_bill_id=' . $agent_bill_id;
		$signStr  = $signStr . '&pay_type=' . $pay_type;
		
		$signStr  = $signStr . '&pay_amt=' . $pay_amt;
		$signStr  = $signStr .  '&remark=' . $remark;
		
		$signStr = $signStr . '&key=' . $key;
		
		$sign='';
		$sign=md5($signStr);
		
		$remark2=explode('/',$remark);
		$user_name = $remark2[1];

		$user=$this->user_info->check_user_name($user_name);
		$user_id=$user[0]['user_id'];
		//请确保 notify.php 和 return.php 判断代码一致
		if($sign==$returnSign){
			
			$data=array(
				'don_money'=>$pay_amt,
				'don_time'=>date('Y-m-d H:i',time()),
				'pro_id'=>$remark2[0],
				'user_id'=>$user_id
				);
			$this->donate->add_donate($data);
			success('pro/thumb/'.$remark,"捐助成功");
		}   //比较MD5签名结果 是否相等 确定交易是否成功  成功显示给客户信息
		else{
			error('捐助失败');
		}
	}
}

/* End of file don.php */
/* Location: ./application/controllers/don.php */