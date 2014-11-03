<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 项目模型
 */
class Pro_info_model extends CI_Model{
	/**
	 * 增加项目
	 * 
	 */
	function add_pro($data){
		$this->db->insert('pro_info',$data);
	}
	
	/**
	 * 删除项目
	 */
	function delete_pro($pro_id){
		$this->db->delete('pro_info',array('pro_id'=>$pro_id));
	}
	/**
	 * 修改项目
	 */
	function update_pro($pro_id,$data){
		$this->db->update('pro_info',$data,array('pro_id'=>$pro_id));
	}
	/**
	 * 查询项目
	 */
	function check_pro($pro_id, $select="*"){
		$data=$this->db->select($select)->where(array('pro_id'=>$pro_id))->get('pro_info')->result_array();
		return $data;
	}
	function check_pro_name($pro_title){
		$data=$this->db->where(array('pro_title'=>$pro_title))->get('pro_info')->result_array();
		return $data;
	}
	/**
	 * 查询所有项目
	 */
	function check_all_pro_xx(){
		$data = $this->db->get('pro_info')->result_array();
		return $data;
	}
	/**
	 * 按需求数目查询项目
	 */
	function check_all_pro($limit){
		$data=$this->db->limit($limit)->order_by('pro_id', 'desc')->get('pro_info')->result_array();
		return $data;
	}
	/**
	 * 剩余时间   
	 * 持续时间-（当前时间-项目开始时间）！！
	 */
	function pro_left_time($pro_id, $flag=1)
	{
		$this->load->helper('date');
		$this->load->model('pro_info_model','pro');

		$data=$this->pro->check_pro($pro_id);
		$mysql = $data[0]['pro_end'];

		$time=mysql_to_unix($mysql);
		$now = now();
		return $flag ? my_timespan($now, $time) : timespan($now, $time);
	}
	/**
	 * 项目进度
	 */
	public function pro_process($pid)
	{
		$this->load->model('pro_info_model','pro');
		$this->load->model('donate_model', 'donate');

		$data['current'] = $this->donate->donate_check($pid);
		$data['goal'] = $this->pro->check_pro($pid, 'pro_goal');
		$current = (int)$data['current'][0]['don_money'];
		$goal = (int)$data['goal'][0]['pro_goal'];
		return number_format(($current/ $goal)*100);
		
	}
	/**
	 * 剩余可捐金额
	 * 
	 */
	public function pro_left_money($pid)
	{
		$this->load->model('pro_info_model','pro');
		$this->load->model('donate_model', 'donate');

		$data['current'] = $this->donate->donate_check($pid);
		$data['goal'] = $this->pro->check_pro($pid, 'pro_goal');
		$current = $data['current'][0]['don_money'];
		$goal = $data['goal'][0]['pro_goal'];
		return number_format(($goal - $current),2, '.',',');
	}
	/**
	 * 获得项目的封面 
	 */
	public function pro_pic($pid)
	{
		$this->load->model('pro_info_model','pro');
		$pics = $this->pro->check_pro($pid);
		return $pic_arr[0]['pro_img'];
	}
	/**
	 * 项目总捐助
	 */
	function check_pro_all($pro_id){
		$data=$this->db->select('don_money')->from('donate')->where(array('pro_id'=>$pro_id))->get()->result_array();
		$num=0;
		$v=count($data);
		foreach ($data as $v) {
			$num += $v['don_money'];
			
		}
		return $num;
	}
	/**
	 * 项目捐助人数
	 */
	function  don_num($pro_id){
		$this->db->select('don_id')->from('donate')->where(array('pro_id'=>$pro_id));
		$num=$this->db->count_all_results();
		if($num){
			return $num;
		}else{
			return 0;
		}
	}
	/**
	 * 查询项目发起人信息
	 */
	function pro_user($pro_id){
		$data=$this->db->select('user_name')->from('pro_info')->join('user_info','pro_info.user_id=user_info.user_id')->where(array('pro_info.pro_id'=>$pro_id))->get()->result_array();
		return $data;
	}
		/**
	 * 通过status查询项目所有信息
	 */
	function check_all(){
		$data=$this->db->where(array('pro_status'=>0))->order_by('pro_id', 'desc')->get('pro_info')->result_array();
		return $data;
	}
}

/* End of file pro_info.php */
/* Location: ./application/models/pro_info_model.php */
