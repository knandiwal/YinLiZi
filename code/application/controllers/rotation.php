<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-22
 * Time: 上午10:31
 * To change this template use File | Settings | File Templates
 * */

class Rotation extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model("Rotation_model",'rotation');
    }


    /*
     * 所有启用的图片
     */
    public function all_on(){
        echo $this->rotation->all_on();
    }



    /*
     * 轮播器列表
     */
    public function index(){
       $res_arr = $this->rotation->get_all();
       $data["list"] = $res_arr;
       $this->load->view("header.php");
       $this->load->view("rotation.php",$data);


    }


    public function admin_add_show(){
        $this->load->view("header.php");
        $this->load->view("rotation_add.php");

    }


   /*
    * 添加图片
    */
    public function admin_add(){
        if(!empty($_FILES["img_rotation"]["name"])){
            $config['upload_path'] = 'uploads/welcome/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = date("Ymd",time()).mt_rand(1000,9999);


            $this->load->library('upload', $config);
            if ($this->upload->do_upload("img_rotation")){
                $info["url"] = 'welcome/'.$config['file_name'].'.'.$this->upload->get_ext();
                $this->img__thumb('uploads/'.$info["url"],90,500,500);
            }
            else{
                $error = array('error' => $this->upload->display_errors());
                alert_back(strip_tags($error["error"]));
                exit;
            }

        }else{
            alert_back("还未选择图片");
            exit;
        }
        $info["name"] = $this->input->post("img_name");
        $info["intro"] = $this->input->post("img_intro");
        $info["create_time"] = date("Y-m-d H:i:s",time());

        $this->rotation->admin_add($info);

    }


    /*
     * 修改是否启用状态
     */
    public function admin_if_on($id){
        $this->rotation->admin_if_on($id);
    }

    /*
     * 删除轮播图片
     */
    public function admin_delete($id){
        $this->rotation->admin_delete($id);

    }


    /*
     * 处理上传的图片，进行压缩
     */
    public function img__thumb($source_img,$quality,$width,$height){
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source_img;
        $config['quality'] = $quality;
        $config['maintain_ratio'] = TRUE;
        $config['master_dim'] = "auto";
        $config['width'] = $width;
        $config['height'] = $height;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();

    }




}

?>
