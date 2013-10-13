<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-20
 * Time: 上午9:34
 * To change this template use File | Settings | File Templates.
 */

class Product_img extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model("Product_img_model");
    }

    /*
     * 删除一张图片
     */

    public function delete_img($id){
        echo  $this->Product_img_model->delete_img($id);
    }





}
?>
