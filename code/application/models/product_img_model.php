<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-15
 * Time: 上午10:01
 * To change this template use File | Settings | File Templates.
 */
class Product_img_model extends CI_Model{
    private $db_name = "ylz_product_img";

    public function __construct(){
        parent::__construct();
    }
/*
 * 添加商品图片
 */
    public function add_img($info){
        $this->db->insert($this->db_name,$info);
    }

    /*
     * 删除商品图片
     */
    public function delete_img($id){
        $this->db->where("id",$id);
        $this->db->delete($this->db_name);
        return $this->db->affected_rows();
    }

    /*
     * 获取某商品的所有图片
     */
    public function get_all_img($product_id){
        $this->db->select("img_url,id");
        $this->db->where("product_id",$product_id);
        $query = $this->db->get($this->db_name);
        return $query->result_array();
    }



}



?>
