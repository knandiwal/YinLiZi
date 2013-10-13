<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-22
 * Time: 上午10:36
 * To change this template use File | Settings | File Templates.
 */
class Rotation_model extends CI_Model{
    private $db_name = "ylz_rotation";
    public function __construct(){
        parent::__construct();
    }



    /*
     * 所有启用的图片
     */
    public function all_on(){
        $this->db->where("is_on",1);
        $this->db->select("id,name,url,href");
        $query = $this->db->get($this->db_name);
        if($query->num_rows()){
            $obj = new Ret('ok',$query->result_array());
        }else{
            $obj = new Ret('no','未查到数据');
        }

        return json_encode($obj);
    }

    /*
     * 所有的轮播图片
     */
    public function get_all(){
        $query = $this->db->get($this->db_name);
        return $query->result_array();
    }



    /*
    * 添加图片
    */
    public function admin_add($info){
        $this->db->insert($this->db_name,$info);
        if($this->db->affected_rows() == 1){
            alert_location("添加成功",site_url("Rotation"));
        }else{
            alert_location("添加失败",site_url("Rotation"));
        }


    }


    /*
     * 修改是否启用状态
     */
    public function admin_if_on($id){
        $this->db->where('id',$id);
        $query = $this->db->get($this->db_name);
        $res = $query->row_array();
        if($res["is_on"] == 1){
            $this->db->where('id',$id);
            $this->db->update($this->db_name,array("is_on"=>0));
        }else{
            $this->db->where('id',$id);
            $this->db->update($this->db_name,array("is_on"=>1));
        }
        if($this->db->affected_rows() == 1){
            alert_location('',site_url("Rotation"));
        }
    }

    /*
     * 删除轮播图片
     */
    public function admin_delete($id){
        $this->db->where("id",$id);
        $this->db->delete($this->db_name);
        if($this->db->affected_rows() == 1){
            alert_location('删除成功',site_url("Rotation"));
        }else{
            alert_location('删除失败',site_url("Rotation"));
        }

    }




}
?>
