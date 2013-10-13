<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 小猪猪
 * Date: 13-8-12
 * Time: 下午3:11
 * To change this template use File | Settings | File Templates.
 * @property mixed user
 */
class User extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','user');
    }

    /*
     * 前台登入
     */
    public function login(){
        //获取表单数据，php://input
        $allposts = file_get_contents("php://input");
        //json解码
        $all = json_decode($allposts);


        $info = array(
            "username"=>$all->user_name,
            "userpassword "=>md5($all->user_password)
        );
         echo $this->user->login($info);


    }




    /*
    前台注册
    */
    public function register(){
        //以json对象形式输出
        header("Content-type:application/json");
        //首先判断是否有重名
        if($this->user->is_exists($this->input->post('userName'))){
            echo json_encode(new Ret('no','用户名已经被注册'));
            exit;
        }

        //检查用户名长度

        if(mb_strlen($this->input->post("userName"),'utf8')>8 ){
            echo json_encode(new Ret('no','用户名长度不得超过八位'));
            exit;
        }

        //检查密码
        if(mb_strlen(($this->input->post("user_password")),'utf8')<=2){
            echo json_encode(new Ret('no','密码长度不得短于两位'));
            exit;
        }

        //验证密码是否一致
        if($this->input->post("user_password") <> $this->input->post("confirm_psw")){
            echo json_encode(new Ret('no','密码不一致'));
            exit;
        }

        //上传头像
        if($_FILES["upload_avatar"]["name"]){
            $config['upload_path'] = 'uploads/head/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = date("Ymd").mt_rand(1000,9999);

            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('upload_avatar')) {
                $error = array('error' => $this->upload->display_errors());
                $obj = new Ret('no',strip_tags($error["error"]));
                echo json_encode($obj);
                exit;
            } else{
                $info["userhead"] = 'head/'.$config['file_name'].'.'.$this->upload->get_ext();
                //图片处理
                $this->img__thumb($config['upload_path'].$config['file_name'].'.'.$this->upload->get_ext(),100,100,100);
            }

        }else{
            $info["userhead"] = 'head/head_normal.png';
        }

        $info['username'] =$this->input->post("userName") ;
        $info['userpassword'] = md5($this->input->post("user_password"));
        $info['useremail'] = $this->input->post("user_email");
        $info['createtime'] = date('Y-m-d H:i:s',time());

        echo $this->user->register($info);
    }


    /*
     * 前台退出
     */
    public function logout(){
        if(!$this->session->userdata("user_id")){
            header('http/1.0 401');
            exit;
        }
        $this->session->unset_userdata('user_id');
        if(!$this->session->userdata("user_id")){
            echo json_encode(new Ret('ok','退出成功'));
        }else{
            echo json_encode(new Ret('no','退出失败'));
        }
    }


   /*
    * 个人中心，获取个人信息
    */
    public function get_info(){
        if(!$this->session->userdata("user_id")){
            header('http/1 401');
            exit;
        }


        $user_info = $this->user->getInfoById($this->session->userdata("user_id"));
        $sid = $user_info["session_id"];
        if($sid != $this->session->userdata('session_id')){
            header('http/1 401');
            exit;
        }

        $id = $this->session->userdata("user_id");
		//我发布的帖子和我参与的帖子的提醒数
		$this->load->model('Remind_model','remind');
		$postcount =  $this->remind->get_owner_remind($id);
		$commentcount = $this->remind->get_join_remind($id);
//		$this->load->model('Post_model','post');
//		$postcount =  $this->post->select_post_count(array('ylz_post.uid'=> $id));
//		$this->load->model('Comment_model','comment');
//		$commentcount = $this->comment->selectpostcount($id);
       
        $arr_info = $this->user->get_info($id);
        if($arr_info){
            echo json_encode(new Ret('ok',$arr_info,array('postcount' => $postcount,'commentcount' => $commentcount)));
        }else{
            echo json_encode(new Ret('no','未查到相关信息'));
        }


    }








/*------------------------------------------后台管理方法-------------------------------------------------------*/

    /*
     * login 用户登入
     *
     */
    public function admin_login(){
        $info = array(
            'username' =>$this->input->post('username'),
            "userpassword"=>md5($this->input->post("password")),
            "is_admin"=>1
        );
        $res =  $this->user->admin_login($info);
        if($res){
            $this->session->set_userdata('user_id',$res);
            redirect("Index");
        }else{
            alert_location('用户名或者密码错误，请重新输入',site_url("User/return_login"));
        }

    }

    public function admin_logout(){
        $this->session->unset_userdata('user_id');
        redirect("User/return_login");
    }
    /*
     * 登入界面
     */
    public function return_login(){
        $this->load->view("login.php");
    }





    public function index($page = 1){
        $this->load->library('pagination');
        $n = $this->input->post('user_name');
        if ($n){
        	$list = $this->user->select_users(array(),'','',$n);
        }else{
       		 $list = $this->user->select_users(array());
        }
        $config['base_url'] = site_url('User/index');
		$config['total_rows'] = count($list);
		$config['per_page'] = '5';
		$config['use_page_numbers']=TRUE;		
		$this->pagination->initialize($config); 
		$limit = '5';
		$offset = $limit *($page-1);
		$res['admin'] = $this->user->getInfoById('1');
		$res['currentuser'] = $this->session->userdata('uid');
		if ($n){
			$res['list'] = $this->user->select_users(array(),$limit,$offset,$n);
		}else{
			$res['list'] = $this->user->select_users(array(),$limit,$offset);
		}
        $this->load->view('header');
        $this->load->view('user',$res);
    }



   public function add_user($id = ''){
   		$currentuser = $this->user->getInfoById($id);
   		if ($currentuser){
	   		$data = array(
	   		 'uid' => $currentuser['uid'],
	   		 'username' => $currentuser['username'],
	   		 'truename' => $currentuser['truename'],
	   		 'useremail' => $currentuser['useremail'],
	   		 'useraddress' => $currentuser['useraddress'],
	   		 'is_admin' => $currentuser['is_admin']
	   		
	   		);
   		}else{
   			$data = array(
		   		 'uid' => '',
		   		 'username' =>'',
		   		 'truename' => '',
		   		 'useremail' =>'',
		   		 'useraddress' => '',
		   		 'is_admin' => ''
		   		);
   		}
   
   	$this->load->view('header');
   	$this->load->view('edituser',$data);
   }
   //添加用户
    public function edit(){
    	$data['uid'] = $this->input->post('uid');
    	
        $data['username'] = $this->input->post('username');
        $cname = $this->user->getInfoByname($data['username']);
        //判断用户名是否重名
        $isexist = $this->user->getInfoByname($data['username']);
        if(($data['uid'] && $data['username'] != $cname['username']) || $data['uid'] == ''){
	        if ($isexist){
	        	$this->session->set_flashdata('flashmessage', '用户名已存在');
	            redirect('User/add_user');
	        }
        }
        $data['truename'] = $this->input->post('truename');
        $data['useremail'] = $this->input->post('useremail');
        $data['useraddress'] = $this->input->post('useraddress');
        if ($data['uid']){
        	if ($this->input->post('userpassword')){
        		$data['userpassword'] = md5($this->input->post('userpassword')); 
        	}
        }else{
        	$data['userpassword'] = md5($this->input->post('userpassword')); 
        }
        $data['createtime'] = date('Y:m:d H-i-s',time());
        $data['is_admin'] = $this->input->post('is_admin');
    	if(isset($_FILES['userhead']) && $_FILES['userhead']['name'] != '') { //上传图片
            $config = array(
                'upload_path' => 'uploads/userhead',
                'allowed_types' => 'gif|jpg|png',
                'file_name' => time(),
                'max_size' => '500',
                'max_width' => '1024',
                'max_height' => '768',
            );
            $this->load->library('upload', $config);
        	if (!$this->upload->do_upload('userhead')) {
        		$this->session->set_flashdata('flashmessage', '头像上传失败');
                redirect('User/add_user');
                exit;
            }
            $data['userhead'] = $config['upload_path'].'/'.$config['file_name'].'.'.$this->upload->get_ext();
        }

        if($this->user->edit_user($data,$data['uid'])){
        	if($data['uid']){
        		$this->session->set_flashdata('flashmessage', '用户编辑成功');
        	}else{
           		 $this->session->set_flashdata('flashmessage', '用户添加成功');
        	}
            redirect('User/index');
        }
   }
   //添加删除管理员
   public function makeadmin($id){
   		$data['is_admin'] = $this->input->post('is_admin');
   		if($this->user->edit_user($data, $id)){
   			$this->session->set_flashdata('flashmessage', '操作成功');
   		}else{
   			$this->session->set_flashdata('flashmessage', '操作成功');
   		}
   		redirect('User/index');
   }
   //修改密码
   public function edit_password($id = ''){
   	 if ($id){
   	 	$user = $this->user->getInfoById($id);
   	 	
   	 }
   	 $this->load->view('header');
   	 $this->load->view('editpassword',$user);
   }
   public function update_password(){
   	    $uid = $this->input->post('uid');
   	    $data['userpassword'] =md5( $this->input->post('userpassword'));
   	   // if ($this->session->userdata('uid') == '1'){
   	    	$this->user->edit_user($data,$uid);
   	    	$this->session->set_flashdata('flashmessage', '修改成功');
   	    	redirect('User/index');
   	   // }
   	     
   }
   //删除用户
   public function delete($id){
   	if($this->user->deleteById($id)){
   		$this->session->set_flashdata('flashmessage', '删除成功');
        redirect('User/index');
   	}else{
   		$this->session->set_flashdata('flashmessage', '删除失败');
        redirect('User/index');
   	}
   	 
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
