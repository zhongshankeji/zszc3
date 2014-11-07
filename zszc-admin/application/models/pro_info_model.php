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
		return $this->db->affected_rows();
	}
	/**
	 * 查询项目
	 */
	function check_pro($pro_id, $select="*"){
		$data=$this->db->select($select)->where(array('pro_id'=>$pro_id))->get('pro_info')->result_array();
		return $data;
	}

	function check_all_pro_xx(){
		$data = $this->db->order_by('pro_id', 'desc')->get('pro_info')->result_array();
		return $data;
	}
	function check_all_pro($limit){
		$data=$this->db->limit($limit)->get('pro_info')->result_array();
		return $data;
	}
	function check_pro_name($pro_id){
		$data=$this->db->where(array('pro_id'=>$pro_id))->get('pro_info')->result_array();
		return $data;
	}
	/**
	 * 剩余时间
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
		$this->load->model('finace_model', 'fina');

		$data['current'] = $this->fina->fina_check($pid);
		$data['goal'] = $this->pro->check_pro($pid, 'pro_goal');
		$current = (int)$data['current'][0]['money'];
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
		$this->load->model('finace_model', 'fina');

		$data['current'] = $this->fina->fina_check($pid);
		$data['goal'] = $this->pro->check_pro($pid, 'pro_goal');
		$current = $data['current'][0]['money'];
		$goal = $data['goal'][0]['pro_goal'];
		return number_format(($goal - $current),2, '.',',');
	}
	/**
	 * 项目对应的图片 
	 * 1 pid, 项目ID
	 * 返回图片数组
	 */
	public function pro_pic($pid)
	{
		$this->load->model('pro_info_model','pro');
		$pics = $this->pro->check_pro($pid);
		
		$pic_arr []=explode(';', $pics[0]['pro_pic_url']);
		return $pic_arr;
	}
	/**
	 * 查询项目发起人信息
	 */
	function pro_user($pro_id){
		$data=$this->db->select('user_name')->from('pro_info')->join('user_info','pro_info.user_id=user_info.user_id')->where(array('pro_id'=>$pro_id))->get()->result_array();
		return $data;
	}
	function check_user($user_id){
		$data=$this->db->where(array('user_id'=>$user_id))->get('user_info')->result_array();
		return ($data);
	}
	function check_user_name($user_id){
		$name=$this->db->select('user_name')->from('user_info')->where(array('user_id'=>$user_id))->get()->result_array();
		return $name;
	}
	function check_name($pro_id){
		$name=$this->db->select('pro_title')->from('pro_info')->where(array('pro_id'=>$pro_id))->get()->result_array();
		return $name;
	}
	//查询单个项目的捐助总金额
	function check_pro_all($pro_id){
		$data=$this->db->select('don_money')->from('donate')->where(array('pro_id'=>$pro_id))->get()->result_array();
		$num=0;
		$v=count($data);
		foreach ($data as $v) {
		
		}
		return $num;
	}
	
}

/* End of file pro_info.php */
/* Location: ./application/models/pro_info_model.php */
