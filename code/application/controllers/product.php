<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-12
 * Time: 上午10:43
 * To change this template use File | Settings | File Templates.
 */
class Product extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','product');

    }


    /*
     * 根据分类category 查询商品
     * @param $category
     */
    public function get_by_category($id){
        $this->product->get_by_category($id);
    }

    /*
     * 根据商品id,查询详情
     */
    public function get_detail($id){
        $this->product->get_detail($id);
    }

    /*
     * 查询所有的商品
     */
    public function get_all(){
        $this->product->get_all();
    }




 /*--------------------admin 后天管理方法 请以admin_ 开头--------------------------------*/
    public function index($page = 1){
        $this->load->library('pagination');
        $config['base_url'] = site_url("Product/index");
        $config['total_rows'] = $this->product->admin_get_count();
        $config['per_page'] = '5';
        $config['use_page_numbers']=TRUE;
        $offset = $config['per_page']*($page-1);
        $this->pagination->initialize($config);
        $arr = $this->product->admin_get_all($config['per_page'],$offset);
        $date["product"] = $arr;
        $this->load->view("header.php");
        $this->load->view("product.php",$date);



    }




    /*添加商品,显示页面
     *
     */
    public function admin_add_show(){
        $this->load->model("Product_category_model","category");
        $data['list'] = $this->category->admin_get_all();
        $this->load->view("header.php");
        $this->load->view("product_add.php",$data);
//
    }
    /*添加商品，插入数据
     *
     */
    public function admin_add(){
        $this->product->admin_add();
    }

    /*
     * 修改商品显示页面
     */
    public function admin_update_show($id){
       $result =  $this->product->admin_update_show($id);
        $data["info"] = $result;
        $this->load->model("Product_category_model","category");
        $category = $this->category->getNanme_by_id($result["category"]);
        $data["category"] = $category->name;
        $data["category_id"] = $category->id;
        $data['list'] = $this->category->admin_get_all();
        $this->load->model("Product_img_model");
        $img_list = $this->Product_img_model->get_all_img($id);
        $data["img_list"] =$img_list;
        $this->load->view("header.php");
        $this->load->view("product_edit.php",$data);
    }
    /*
      * 修改商品
      * @param $id
      */
    public function admin_update(){
        $id = $this->input->post("id");
        $this->product->admin_update($id);
    }

    /*
     *删除商品
     * @param $id
     */
    public function admin_delete($id){
        $this->product->admin_delete($id);
    }


    /*
     * 后台搜索商品
     */
    public function admin_search($page=1){
        $this->load->library('pagination');
        $config['base_url'] = site_url("Product/admin_search");
        $config['total_rows'] = $this->product->admin_search_count();
        $config['per_page'] = '5';
        $config['use_page_numbers']=TRUE;
        $offset = $config['per_page']*($page-1);
        $this->pagination->initialize($config);
        $arr = $this->product->admin_search($config['per_page'],$offset);
        $data["product"] = $arr;
        $data["if_page"] = false;
        $this->load->view("header.php");
        $this->load->view("product.php",$data);


    }

}






?>