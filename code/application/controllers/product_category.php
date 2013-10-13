<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-12
 * Time: 上午10:51
 * To change this template use File | Settings | File Templates.
 */
class Product_category extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_category_model','category');
    }

    /*
     * 获取所有的分类
     * @param
     */
    public function get_all(){
       $parentcats = $this->category->select_categories(array('par_id' => '0'));
//        print_r($parentcats);
       $result = array();
       if($parentcats){
            foreach($parentcats as $pcat){
                $result[$pcat['id']]['id'] = $pcat['id'];
                $result[$pcat['id']]['name'] = $pcat['name'];
                $result[$pcat['id']]['icon_class'] = $pcat['ico_class'];
                $subcats =  $this->category->select_categories(array('par_id' => $pcat['id']));
                if($subcats){
                    $res = '';
                    foreach($subcats as $sub){
                        $res[$sub['id']]['id'] = $sub['id'];
                        $res[$sub['id']]['name'] = $sub['name'];
                    }
                    $final = '';
                    if($res){
                        foreach($res as $re){
                            $final[] = $re;
                        }
                        $result[$pcat['id']]['sub'] = $final;
                    }

                }
            }
           if($result){
               foreach($result as $value){
                    $return[] = $value;
               }
           }

       }
        echo json_encode($return);

    }








 /*--------------------admin 后天管理方法 请以admin_ 开头--------------------------------*/

    public function index(){
        $this->load->view("header.php");
        $this->load->model("Product_category_model","category");
        $data["list"] = $this->category->admin_get_top();
        $data["list_two"] = $this->category->admin_get_two();
        $this->load->view("product_category.php",$data);
    }



    /*
     * 根据父类id 查找子类id
     */
    public function admin_get_child($par_id){
        $this->category->admin_get_child($par_id);
    }



    /*
     * 添加等级分类
     */
    public function admin_add_top(){
        $info = array(
            "par_id" => 0,
            "name"=>$this->input->post("top")
        );
        $this->category->admin_add_top($info);
    }



    /*
     * 删除顶级分类
     */
    public function admin_delete_top($id){
        $this->category->admin_delete_top($id);
    }

    /*
     * 添加顶级菜单
     */
    public function admin_add_two(){
        $info =array(
            "par_id"=>$this->input->post("par"),
            "name"=>$this->input->post("two_name")
        );

        $this->category->admin_add_two($info);


    }
    /*
     * 删除二级分类
     */
    public function admin_delete_two($id){
        $this->category->admin_delete_two($id);
    }


}





?>