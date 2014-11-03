<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donate_model extends CI_Model{	
	/**
	 * 通过用户ID查询所捐助的项目
	 */
	public function check_my_don($uid)
	{	
		$data=$this->db->where(array('user_id'=>$uid))->get('donate')->result_array();
		$pro1=array();
		$pro2='';
		foreach ($data as $key => $value) {
			$pro2=$this->db->select('pro_title')->where(array('pro_id'=>$value['pro_id']))->get('pro_info')->result_array();
			$pro2[1]['don_time']=$data[$key]['don_time'];
			$pro2[2]['don_money']=$data[$key]['don_money'];
			$pro1[$key]=$pro2 ;
		}
		if(is_null($pro1)){
			return $pro1=array('尚未捐助项目');
		}else{
		return $pro1;
		}
	}
	/**
	 * 通过用户ID查询所捐助项目个数
	 */
	public function check_don_num($uid){
		$this->db->select('don_id')->from('donate')->where(array('user_id'=>$uid));
		$num = $this->db->count_all_results();
		if ($num) {
			return $num;
		}else{
			return 0;
		}
	}
	/**
	 * 查询某个项目总共捐助金额
	 */
	function donate_check($pro_id){
		$money=$this->db->select_sum('don_money')->from('donate')->where(array('pro_id'=>$pro_id))->get()->result_array();
		if ($money) {
			return $money;
		}else{
			$money = array('money' => 0);
			return $money;
		}
		
	}
	/**
	 * 查询某个项目总共捐助人数
	 */
	public function donater_sum($pro_id)
	{
		$data=$this->db->select_sum('don_money')->from('donate')->where(array('pro_id'=>$pro_id));
		if($data){
			return $this->db->count_all_results();
		}else{
			return 0;
		}
	}
	public function add_donate($data){
		$this->db->insert('donate',$data);
	}
	
}         

/* End of file finace_model.php */
/* Location: ./application/models/finace_model.php */