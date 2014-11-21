<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  管理模型
 */
class Article_model extends CI_Model{
	/**
	 * 发表 
	 */
	public function add($data){
		$this->db->insert('article', $data);
	}

	/**
	 * 查看 
	 */
	public function article_category(){
		$data = $this->db->select('pro_id,pro_title,pro_status,pro_goal,pro_start,pro_end,pro_remark')->from('pro_info')->get()->result_array();
		return $data;
	}


	/**
	 * 首页查询 
	 */
	public function check(){
		$data['art'] = $this->db->select('aid,thumb,title,info')->order_by('time', 'desc')->get_where('article', array('type'=>0))->result_array();

		$data['hot'] = $this->db->select('aid,thumb,title,info')->order_by('time', 'desc')->get_where('article', array('type'=>1))->result_array();

		return $data;
	}

	/**
	 * 右侧 标题调取
	 */
	public function title($limit){
		$data = $this->db->select('title,aid')->order_by('time', 'desc')->limit($limit)->get('article')->result_array();
		return $data;
	}

	/**
	 * 通过栏目调取 
	 */
	public function category_article($cid){
		$data = $this->db->select('aid,thumb,title,info')->order_by('time', 'desc')->get_where('article', array('cid'=>$cid))->result_array();
		return $data;
	}


	/**
	 * 通过aid 调取 
	 */
	
	public function aid_article($aid){
		$data = $this->db->join('category', 'article.cid=category.cid')->get_where('article', array('aid'=>$aid))->result_array();
		return $data;
	}












}