<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-12
 * Time: 下午3:36
 * To change this template use File | Settings | File Templates.
 */
class Product_category_model extends CI_Model{
    private $db_name = "ylz_product_category";

    public function __construct(){
        parent::__construct();
    }

    /*
     * 查询所有的分类
     */
    public function select_categories($fetch = array('*'), $limit = 100, $offset = 0) {
       // $this->db->order_by("id", "desc");
        $query = $this->db->get_where($this->db_name, $fetch, $limit, $offset);
        return $query->result_array();
    }

    /*
     * 根据category的id 获取category的name
     */
    public function getNanme_by_id($id){
        $this->db->where("id",$id);
        $query = $this->db->get($this->db_name);
        return $query->row();
    }






/*--------------------admin 后天管理方法 请以admin_ 开头--------------------------------*/
    /*
     * 查询所有的类别
     */
    public function admin_get_all(){
        $this->db->where("par_id",'0');
        $query = $this->db->get($this->db_name);
        return $query->result_array();
    }


    /*
     * 根据父类id 查找子类id，二级菜单联动 专用
     */
    public function admin_get_child($par_id){
        $this->db->select("id");
        $this->db->select("name");
        $this->db->where("par_id",$par_id);
        $query = $this->db->get($this->db_name);
        echo json_encode($query->result());
//        print_r($query->result());
    }

    /*
     * 查询所有的顶级菜单
     */
    public function admin_get_top(){
        $this->db->where('par_id',0);
        $query = $this->db->get($this->db_name);
        return $query->result_array();
    }

    /*
 * 查询所有的er级菜单
 */
    public function admin_get_two(){
        $sql = "select one.name as one_name,two.name as two_name,two.id as two_id,one.id as one_id from ylz_product_category as one JOIN ylz_product_category as two on one.id=two.par_id";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*
     * 添加等级分类
     */
    public function admin_add_top($info){
        $this->db->insert($this->db_name,$info);
        if($this->db->affected_rows() == 1){
            alert_location('添加成功',site_url("Product_category/index"));
        }else{
            alert_location('添加失败',site_url("Product_category/index"));
        }
    }


    /*
     * 删除顶级分类
     */
    public function admin_delete_top($id){
        $this->db->where("id",$id);
        $this->db->delete($this->db_name);
        $affected_row = $this->db->affected_rows();
        //删除其下所有的子分类
        $this->db->where("par_id",$id);
        $this->db->delete($this->db_name);
        if($affected_row == 1){
            alert_location('删除成功',site_url('Product_category/index'));
        }else{
            alert_location('删除失败',site_url('Product_category/index'));
        }
    }


    /*
     * 添加二级分类
     */
    public function admin_add_two($info){
        $this->db->insert($this->db_name,$info);
        if($this->db->affected_rows() == 1){
            alert_location('添加成功',site_url("Product_category/index"));
        }else{
            alert_location('添加失败',site_url("Product_category/index"));
        }
    }
    /*
     * 删除顶级分类
     */
    public function admin_delete_two($id){
        $this->db->where("id",$id);
        $this->db->delete($this->db_name);
        if($this->db->affected_rows() == 1){
            alert_location('删除成功',site_url('Product_category/index'));
        }else{
            alert_location('删除失败',site_url('Product_category/index'));
        }




    }

}


?>
