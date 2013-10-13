<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-12
 * Time: 下午3:10
 * To change this template use File | Settings | File Templates.
 */
class Index extends CI_Controller{
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('uid')){
        	//redirect('User/admin_login');
        	//header("location:http://www.baidu.com");
        }
    }

public function Index(){
	$this->load->model('Company_model','company');
	$company = array_shift($this->company->select_company(array()));
    if ($company){
         $data =array(
         'companyname' => $company['companyname'],
         'contact' => $company['contact'],
         'description' => strip_tags($company['description']),
         'tel' => unserialize($company['tel']),
         'qq_account' => $company['qq_account'],
         'address' => $company['address'],
         'wechat_account' => $company['wechat_account'],
         'micro_account' => $company['micro_account'],
         'url' => $company['url'],
         'logo' => $company['logo'],
         );
     }
    $this->load->view("header.php");
    $this->load->view("index",$data);

}




}




?>
