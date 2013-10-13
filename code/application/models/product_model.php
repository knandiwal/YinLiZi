<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-12
 * Time: 下午3:23
 * To change this template use File | Settings | File Templates.
 */
class Product_model extends CI_Model{
    private $db_name = "ylz_product";

    public function __construct(){
        parent::__construct();

    }



    /*
     * 根据分类category 查询商品
     * @param $category
     */
    public function get_by_category($id){
        $this->db->select('id,title,price,category,img1,img_thumb');
        $this->db->where('category',$id);
        $query = $this->db->get($this->db_name);
//        return $query->result();
        $this->load->model("Product_category_model","category");
        $res = $this->category->getNanme_by_id($id);
        $obj = new Ret("ok",$query->result_array(),$res->name);
        echo json_encode($obj);

    }

    public function get_detail($id){
        $this->db->where('id',$id);
        $query = $this->db->get($this->db_name);
        $result = $query->result_array();
        //加载公司信息QQ
        $this->load->model("Company_model",'company');
        $company_info = $this->company->select_company(array());
        $qq = unserialize($company_info[0]['qq_account']);
        $this->load->model("Product_img_model");
        $all_img = $this->Product_img_model->get_all_img($id);
        $tem = array();
        foreach($all_img as $new){
            array_push($tem,$new["img_url"]);
        }
        foreach($result as &$value){
            $value["all_img"] = $tem;
        };
        if($query->num_rows()>0){
            $obj = new Ret('ok',$result,$qq);
        }else{
            $obj = new Ret('no',"未查询到商品");
        }
//        header("HTTP/1.0 404 Not Found");
        echo json_encode($obj);
    }

    /*
     * 查询所有商品
     */
    public function get_all(){
        $query = $this->db->get($this->db_name);
//        return $query->result();
        if($query->num_rows()!=0){
            echo json_encode(new Ret("ok",$query->result()));
        }else{
            echo json_encode(new Ret("no","商品列表为空"));
        }
    }





    /*--------------------admin 后天管理方法 请以admin_ 开头--------------------------------*/

    /*
     * 列出所有的商品
     */
    public function admin_get_all($limit,$offset){
        $this->db->select("id,title,price,detail");
        $this->db->order_by('create_time','ASC');
        $query = $this->db->get($this->db_name,$limit,$offset);
        return $query->result_array();
    }

    /*
     * 获取商品总数目
     */
    public function admin_get_count(){
        return $this->db->count_all($this->db_name);
    }


    /*添加商品
     *
     */
    public function admin_add(){
        $img_count = $this->input->post("img_count");
        if(empty($_FILES["file"]["name"][0])){
            alert_back("至少上传一张图片");
            exit;
        }
        for($i=0;$i<$img_count;$i++){
            if (($_FILES["file"]["type"][$i] == "image/gif")|| ($_FILES["file"]["type"][$i] == "image/jpeg") || ($_FILES["file"]["type"][$i] == "image/png") ||($_FILES["file"]["type"][$i] == "image/pjpeg")){
                if ($_FILES["file"]["error"][$i] > 0){
                    alert_back("Error: " . $_FILES["file"]["error"][$i]);
                    exit;
                }

                if($_FILES["file"]["size"][$i]>1204*1024){
                    alert_back("文件超过预定大小");
                    exit;
                }
                $upload_floder = 'product/';
                $ext[$i] = array_pop(explode('.',$_FILES["file"]["name"][$i]));
                $uploadfile[$i] = $upload_floder .date("Ymd",time()).mt_rand(1000,9999).'.'.$ext[$i];
                if (move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/'.$uploadfile[$i])) {
                    //写入商品数据库
                    if($i==0){
                        $info["title"] = $this->input->post('title');
                        $info["category"] = $this->input->post("category");
                        $info["price"] = $this->input->post("price");
                        $info["intro"] = $this->input->post("intro");
                        $info["detail"] = $this->input->post("content");
                        $this->img_thumb('uploads/'.$uploadfile[$i],'uploads/'.$uploadfile[$i],64,64);
                        $tem_arr = explode('.',$uploadfile[$i]);
                        $info["img1"] = $tem_arr[0].'.'.$ext[$i];
                        $info["img_thumb"] = $tem_arr[0]."_thumb.".$ext[$i];
                        $info["create_time"] = date("Y-m-d H;i:s",time());
                        $this->db->insert($this->db_name,$info);
                        if($this->db->affected_rows()==1){
                            alert_location('添加成功',site_url("Product/index"));
                        }
                        $insert_id = $this->db->insert_id();


                    }
                    //写入商品图片数据库
                    $this->load->model("Product_img_model");
                    $img_info = array(
                        "product_id"=>$insert_id,
                        "img_url"=>$uploadfile[$i]
                    );
                    $this->Product_img_model->add_img($img_info);



                } else {
                    echo "文件上传有误!\n";
                }


            }else{
                echo "文件类型不合法";
            }
        }


    }

    /*
      * 修改商品显示修改页面
      * @param $id
      */
    public function admin_update_show($id){
        $this->db->where("id",$id);
        $query = $this->db->get($this->db_name);
        return  $query->row_array();
    }

    /*
     * 商品修改，插入数据库
     */
    public function admin_update($id){
        /* 开始上传缩略图*/
        if($_FILES["img_thumb"]["name"]){
            //检查文件类型
           if(($_FILES["img_thumb"]["type"] == "image/gif")|| ($_FILES["img_thumb"]["type"] == "image/jpeg") || ($_FILES["img_thumb"]["type"] == "image/png") ||($_FILES["img_thumb"]["type"] == "image/pjpeg")){
               //检查错误
               if($_FILES["img_thumb"]["error"] > 0 ){
                   alert_back("缩略图上传有误");
                   exit;
               }
                //检查文件大小
               if($_FILES["img_thumb"]["size"]>1204*1024){
                   alert_back("文件超过预定大小");
                   exit;
               }

               $upload_floder = 'product/';
               $ext = array_pop(explode('.',$_FILES["img_thumb"]["name"]));
               $uploadfile = $upload_floder .date("Ymd",time()).mt_rand(1000,9999).'.'.$ext;
               if (move_uploaded_file($_FILES['img_thumb']['tmp_name'], 'uploads/'.$uploadfile)) {
                    //创建缩略图
                   $this->img_thumb('uploads/'.$uploadfile,'uploads/'.$uploadfile,64,64);
                   $tem_arr = explode('.',$uploadfile);
                   $info["img1"] = $tem_arr[0].'.'.$ext;
                   $info["img_thumb"] = $tem_arr[0]."_thumb.".$ext;
               }else{
                alert_back("缩略图上传有误");
                exit;
               }


            }else{
                alert_back("缩略图文件类型不合法");
                exit;
            }


        }
        /* 上传缩略图结束*/

        $info["title"] = $this->input->post("title");
        $info["price"] = $this->input->post("price");
        $info["detail"] = $this->input->post("content");
        $info["category"] = $this->input->post("category");
        $info["intro"] = $this->input->post("intro");
        $this->db->where("id",$id);
        $this->db->update($this->db_name,$info);



        /*上传详图开始*/
        $img_count = $this->input->post("img_count");
        for($i=0;$i<$img_count;$i++){
            if($_FILES["file"]["name"][$i]){
                if (($_FILES["file"]["type"][$i] == "image/gif")|| ($_FILES["file"]["type"][$i] == "image/jpeg") || ($_FILES["file"]["type"][$i] == "image/png") ||($_FILES["file"]["type"][$i] == "image/pjpeg")){
                    if ($_FILES["file"]["error"][$i] > 0){
                        alert_back("Error: " . $_FILES["file"]["error"][$i]);
                        exit;
                    }

                    if($_FILES["file"]["size"][$i]>1204*1024){
                        alert_back("详图文件超过预定大小");
                        exit;
                    }
                    $upload_floder = 'product/';
                    $ext[$i] = array_pop(explode('.',$_FILES["file"]["name"][$i]));
                    $uploadfile[$i] = $upload_floder .date("Ymd",time()).mt_rand(1000,9999).'.'.$ext[$i];
                    if (move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/'.$uploadfile[$i])) {

                        //写入商品图片数据库
                        $this->load->model("Product_img_model");
                        $img_info = array(
                            "product_id"=>$id,
                            "img_url"=>$uploadfile[$i]
                        );
                        $this->Product_img_model->add_img($img_info);
                    } else {
                        echo "文件上传有误!\n";
                    }
                }else{
                    echo "文件类型不合法";
                }
            }
            }

        /*上传详图结束*/

        if($this->db->affected_rows()){
            alert_location("修改成功",$this->input->post("reference_url"));
        }else{
            alert_location("修改失败",$this->input->post("reference_url"));
        }

    }

    /*
     *删除商品
     * @param $id
     */
    public function admin_delete($id){
        /*
         * 删除商品图片
         */
        $this->load->model("Product_img_model");
        $this->Product_img_model->delete_img($id);
        /*
         *删除商品
         */
        $this->db->where("id",$id);
        $this->db->delete($this->db_name);
         if($this->db->affected_rows()){
             alert_location('删除成功',$this->config->item("base_url")."index.php/Product/index");

         }else{
             alert_location('删除失败',"Product/index");
         };
    }


    /*
     * 创建缩略图
     */
    public function img_thumb($source_img,$thumb_img,$width,$height){
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source_img;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = $thumb_img;
        $config['master_dim'] = "auto";
        $config['thumb_marker'] = "_thumb";

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();



    }


    /*
     * 后台搜索
     */
    public function admin_search($limit,$offset){
        $this->db->like("title",$this->input->post("input_title"));
        $query = $this->db->get($this->db_name,$limit,$offset);
        return $query->result_array();

    }

    /*
     * 后台搜索count
     */
    public function admin_search_count(){
        $this->db->like("title",$this->input->post("input_title"));
        $query =  $this->db->get($this->db_name);
        return $query->num_rows();

    }


}



?>
