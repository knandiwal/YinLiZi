<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-12
 * Time: 下午3:36
 * To change this template use File | Settings | File Templates.
 */
class Article_category_model extends CI_Model{
    private $db_name = "ylz_article_category";

    public function __construct(){
        parent::__construct();
    }

    /*
     * 查询所有的分类
     */
  	public function select_categories($fetch = array('*'), $limit = 100, $offset = 0) {
        $this->db->order_by("id", "desc");
        $query = $this->db->get_where($this->db_name, $fetch, $limit, $offset);
        return $query->result_array();
    }
    

    /*
     * 添加修改分类
     */
    public function edit($data,$id = ''){
        if ($id){
        	$this->db->where('id', $id);
            return $this->db->update($this->db_name, $data) ? true : false;
        }else{
        	return $this->db->insert($this->db_name, $data) ? true : false;
        }
    }



    /*
     * 删除类别
     */
    public function delete($id){
    	$this->db->delete($this->db_name,array('id'=>$id));
       //return $this->db->affected_rows();
    }

    /*
     * 获取所有分类名
     */
    public function getnames(){
    	$this->db->select('name');
		$query = $this->db->get($this->db_name);
        return $query->result_array();
    	
    }
    /*
     * 根据id获取分类名
     */
    public function get_name($id){
    	$query = $this->db->get_where($this->db_name, array('id' => $id));
        return $query->result_array();
    }
    
	/*
     * 根据分类名获取id
     */
    public function get_id($name){
    	$query = $this->db->get_where($this->db_name, array('name' => $name));
        return $query->result_array();
    }
}


?>
