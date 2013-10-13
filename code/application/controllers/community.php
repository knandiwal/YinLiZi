<?php
class Community extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Forum_model','forum');
        $this->load->model('Post_model','post');
        $this->load->model('Comment_model','comment');
    }

    /*
     * 显示所有论坛
     * @param
     */
    public function index($page = 1){
    	$this->load->helper('form');
    	$this->load->library('pagination');
    	$name = $this->input->post('name');
    	if ($name){
    		$res = $this->forum->select_forum_by('','',$name);
    	}else{
        	$res = $this->forum->select_forum(array());
    	}
        $config['base_url'] = site_url('Community/index');
		$config['total_rows'] = count($res);
		$config['per_page'] = '5';
		$config['use_page_numbers']=TRUE;		
		$this->pagination->initialize($config); 
		$limit = '5';
		$offset = $limit *($page-1);
		if ($name){
			$result['list'] = $this->forum->select_forum_by($limit,$offset,$name);
		}else{
			$result['list'] = $this->forum->select_forum(array(),$limit,$offset);
		}
		       
        $this->load->view('header');
        $this->load->view('community',$result);
    }
    //app 论坛
    public function getforum(){
		$result = $this->forum->select_forum(array());
		if ($result){
			$result = new Ret('ok',$result);
	       	echo json_encode($result);
		}else{
			$result = new Ret('ok',$result);
	       	echo json_encode($result);
		}
    }
	public function edit_forum($id = ''){
		$forum =  array_pop($this->forum->select_forum(array('fid' => $id)));
		$data = array(
			'fid' => $forum['fid'],
			'name' => $forum['name']
		);
		$this->load->view('header');
        $this->load->view('editforum',$data);
	}
   
    /*
     * 添加修改论坛信息
     */
    public function edit($id = ''){
    	$data['fid'] = $this->input->post('fid');
    	$data['name'] = $this->input->post('name');
    
    	if (!$data['name']){
    		$this->session->set_flashdata('flashmessage', '请填写论坛名');
            redirect('Community/edit_forum');
    		exit;
    	}
    	
    	if(isset($_FILES['fimage']) && $_FILES['fimage']['name'] != '') { //上传图片
            $config = array(
                'upload_path' => 'uploads/background',
                'allowed_types' => 'gif|jpg|png',
                'file_name' => time(),
                'max_size' => '99999',
                'max_width' => '9999',
                'max_height' => '9999',
            );
            $this->load->library('upload', $config);
        	if (!$this->upload->do_upload('fimage')) {
        		$this->session->set_flashdata('flashmessage', '图片上传失败');
                 redirect('Community/edit_forum');
                exit;
            }
            $data['image'] = 'background/'.$config['file_name'].'.'.$this->upload->get_ext();
            //图片处理
            $this->img__thumb($config['upload_path'].$config['file_name'].'.'.$this->upload->get_ext(),100,700,350);
        }else{
        	$data['image'] = 'background/default/background.jpg';
        }
    	
    	$data['createtime'] = date('Y-m-d H:i:s');
       
        if ($this->forum->edit_forum($data,$data['fid'])){
        	if ($data['fid']){
        		$this->session->set_flashdata('flashmessage', '论坛信息修改成功');
	            redirect('Community/index');
	    		exit;
        	}else{
        		$this->session->set_flashdata('flashmessage', '论坛创建成功');
	            redirect('Community');
	    		exit;
        	}
        }
    }

    
    /*
     * 删除论坛
     */
    public function delete($id){
    	//管理员删除
    	
         $this->forum->delete_forum($id);
         $this->session->set_flashdata('flashmessage', '删除成功');
         redirect('Community');
    }

    //app 发帖
    public function edit_post($id = ''){
    	$allposts = file_get_contents("php://input");
		$all = json_decode($allposts);
		$data['uid'] = $this->session->userdata('user_id');
    	$data['fid'] = $all->fid;
		if ($data['fid'] && $data['uid'] ){
	    	$data['subject'] = $all->subject;
	    	$data['message'] = $all->message;
	    	$data['create_time'] = date('Y:m:d H-i-s',time());
	    	if($this->post->edit_post($data,$id)){
	    		$result = new Ret('ok',$data);
		       	  echo json_encode($result);
	    	}else{
	    		$result = new Ret('-1',array('error' => '创建失败'));
		       	  echo json_encode($result);
	    	}
		}else{
 			header('http/1.0 401');
            exit;
		}
    }
    
    
    //后台查看贴子及回复
    public function read($page = 1){
    	if (isset($_GET['fid'])){
    		$fid = $_GET['fid'];
    		$this->session->set_userdata('tiezi', $fid);
    	}
    	if (!isset($fid)){
    		$fid = $this->session->userdata('tiezi');
    	}
    	$this->load->library('pagination');
    	//回帖
    	$count = $this->post->select_post_count(array('fid'=> $fid));
    	$config['base_url'] = site_url('Community/read');
		$config['total_rows'] = $count;
		$config['per_page'] = '5';
		$config['use_page_numbers']=TRUE;		
		$this->pagination->initialize($config); 
		$limit = '5';
		$offset = $limit *($page-1);
    	$data['list'] = $this->post->select_posts(array('fid' => $fid),$limit,$offset);
    	$this->load->view('header');
    	$this->load->view('post',$data);
    }
    
    
    //查看所有帖子
    public function get_post($fid,$page = 1,$limit = 5){
    	$offset = $limit *($page-1);
    	$count = $this->post->select_post_count(array('fid'=> $fid));
    	$uid = $this->session->userdata('user_id');
    	//if ($uid){
    		//$posts = $this->post->select_post_by_comment(array('fid' => $fid),$uid,$limit,$offset);
    	//}else{
    		$posts = $this->post->select_post(array('fid' => $fid),$limit,$offset);
    	//}
    	if ($posts){
    		foreach ($posts as &$post){
    			$post['create_time'] = date("Y-m-d H:i",strtotime($post['create_time']));
    			$post['comment_num'] = $this->get_comment_num($post['pid']);
    		}
    		$result = new Ret('ok',$posts,array('totalItem' => $count));
	       	echo json_encode($result);
    	}else{
    		$result = new Ret('ok',array());
	       	echo json_encode($result);
    	}
    }
    //查看贴子详细
    public function get_post_content($pid){
    	$post = $this->post->get_post($pid);
    	$post['create_time'] = date("Y-m-d H:i",strtotime($post['create_time']));
    	if($post){
    		//提醒清零
    		$this->load->model('Remind_model','remind');
    		$uid = $this->session->userdata('user_id');
    		if ($uid){
	    		$row = $this->remind->getremind($pid,$uid);
		   		if ($row){
		   			$this->remind->delete_remind($pid,$uid);
		   		}
    		}
    		$return = new Ret('ok',$post);
    		echo  json_encode($return);
    	}else{
    		$return = new Ret('ok',array());
    		echo  json_encode($return);
    	}
    	
    }
    //删除帖子
    public function post_delete($id){
    	//管理员和创建者删除
    	$this->load->model('User_model','user');
    	$uid = $this->session->userdata('user_id');
    	$user = $this->user->getInfoById($uid);
    	$post = $this->post->get_post($id);
    	if ($uid == $post['uid'] || $user['is_admin']){
    		//删除回复
	    	$tcomments = $this->comment->select_comment(array('tid' => $id));
	    	if($tcomments){
	    		foreach ($tcomments as $t){
	    			$this->comment->delete_comment($t['id']);	
	    		}
	    	}
	    	$this->post->delete_post($id);
	    	
	        $this->session->set_flashdata('flashmessage', '删除成功');
	        redirect('Community');
    	}
    }
    
    //添加回复
    public function add_comment($tid){
    	$allposts = file_get_contents("php://input");
		$all = json_decode($allposts);
    	$data['tid'] = $tid;   //贴子id
    	$data['uid'] = $this->session->userdata('user_id');
    	if ($data['tid'] && $data['uid'] ){
	    	$data['content'] = $all->message;
	    	$data['createtime'] = date("Y-m-d H:i:s",time());
	    	$d['commenttime'] = date("Y-m-d H:i:s",time());
	    	$r = $this->comment->edit_comment($data);
	    	//改变贴子的发布评论时间
	    	$this->post->edit_post($d,$tid);
	    	//提醒  	
	    	$users = $this->get_user($tid);
	    	$currentuser = $this->session->userdata('user_id');
	    	if ($currentuser && $users){
	    		foreach ($users as $user){
	    			$re = array();
	    			if ($user != $currentuser){
	    				$re['tid'] =  $tid;
	    				$re['touid'] = $user ;
	    				//该贴子下用户有没有提醒
	    				
	    				$this->load->model('Remind_model','remind');
	    				$row = $this->remind->getremind($tid,$user);
	    				if($row){
	    					$re['num'] = $row['num'] +1;
	    					$this->remind->edit_remind($re,$row['rid']);
	    				}else{
	    					$this->remind->edit_remind($re);
	    				}
	    			}
	    		}
	    	}
	    	if ($r) {
		       	  echo json_encode(new Ret('ok','回复成功'));
	    	}else{
	    		echo json_encode(new Ret('-1','回复失败'));
	    	}
    	}else{
    		header('http/1.0 401');
            exit;
    	}
    }
    
    //查看回复
    public function get_comments($tid,$page = 1,$limit = 5){
    	//贴子下的回复
    	//sleep(5);
    	$offset = $limit *($page-1);
    	$count = $this->comment->select_comment_count(array('tid' => $tid));
    	$tcomments = $this->comment->select_comment(array('tid' => $tid),$limit,$offset);
    	$return = array();
    	if ($tcomments){
    		foreach ($tcomments as &$tcomment){
    			$tcomment['createtime'] = date("m-d H:i",strtotime($tcomment['createtime']));
    		}
    		  $result = new Ret('ok',$tcomments,array('totalItem' => $count));
	       	  echo json_encode($result);
    	}else{
    		 $result = new Ret('ok',array());
	       	  echo json_encode($result);
    	}
    	
    }
    //删除回复
    public function delete_comment($id){
    	//管理员和创建者删除
    	$this->load->model('User_model','user');
    	$uid = $this->session->userdata('user_id');
    	$user = $this->user->getInfoById($uid);
    	$comment = $this->comment->get($id);
    	if ($uid == $comment['uid'] || $user['is_admin'] ){
    		$this->comment->delete_comment($id);	
    	}
    }
    //我的帖子列表
    public function get_owner_posts($page = 1,$limit = 5){
    	$offset = $limit *($page-1);
    	$uid = $this->session->userdata('user_id');
    	$count =  $this->post->select_post_count(array('ylz_post.uid'=> $uid));
    	
    	//$posts = $this->post->select_post(array('ylz_post.uid' => $uid),$limit,$offset);
    	
    	$posts = $this->post->select_post_by_comment(array('ylz_post.uid' => $uid),$limit,$offset);
    	if($posts){
    		foreach ($posts as &$post){
    			$post['create_time'] = date("Y-m-d H:i",strtotime($post['create_time']));
    			$post['comment_num'] = $this->get_comment_num($post['pid']);
    			$num = $this->get_one_message($post['pid']);
    			$post['alertmessage'] = $num;
    		}
    	}
    	//$alertmessage = $this->get_remind();
    	$result = new Ret('ok',$posts,array('totalItem' => $count));
       	echo json_encode($result);

    }
    //我参与的帖子列表
    public function get_join_posts($page = 1,$limit = 5){
    	//与我相关的评论
    	$offset = $limit *($page-1);
    	$uid = $this->session->userdata('user_id');
    	//$count = $this->comment->selectpostcount(array('ylz_comment.uid' => $uid));
    	$count = $this->comment->selectpostcount($uid);
    	$postids = $this->comment->selectpostid($uid, $limit, $offset);
    	$posts = array();
    	if ($postids){
    		foreach ($postids as $postid){
    			$num = $this->get_one_message($postid['tid']);
    			$p = $this->post->get_simple_post($postid['tid']);
    			$p['comment_num'] = $this->get_comment_num($postid['tid']);
    			$p['alertmessage'] = $num;
    			$p['create_time'] = date("Y-m-d H:i",strtotime($p['create_time']));
    			$posts[] = $p;
    		}
    		$result = new Ret('ok',$posts,array('totalItem' => $count));
    		echo json_encode($result);
    	}else{
    		$result = new Ret('ok',array());
    		echo json_encode($result);
    	}
    }
    //参与贴子的用户
    public function get_user($tid){
    	//获取贴子创建者
    	$post = $this->post->get_post($tid);
    	$users[] = $post['uid'];
    	$tcomments = $this->comment->select_comment(array('tid' => $tid));
    	if ($tcomments){
    		foreach ($tcomments as $tcomment){
    			$users[] = $tcomment['uid'];
    		}
    	}
    	$result = array_unique($users);
    	return $result;
    }
    //返回回复数
    public function get_comment_num($tid){
    	$tcomments = $this->comment->select_comment(array('tid' => $tid));
    	return count($tcomments);
    }
    
   //获取当前用户提醒
   public function get_remind(){
   		$this->load->model('Remind_model','remind');
   		$currentuser = $this->session->userdata('user_id');
   		$reminds = $this->remind->select_remind(array('touid' => $currentuser));
   		$count = array();
   		if ($reminds){
   			foreach ($reminds as $r){
   				$count[] = $r['num'];
   			}
   			$num = array_sum($count);
   			$result = new Ret('ok',array('alertmessage' => $num));
	        echo json_encode($result);
   		}else{
   			$result = new Ret('ok',array('alertmessage' => '0'));
	        echo json_encode($result);
   		}
   } 
   //每条帖子收到的回复数
   public function get_one_message($tid){
   		$this->load->model('Remind_model','remind');
   		$uid = $this->session->userdata('user_id');
   		$reminds = $this->remind->getremind($tid,$uid);
   		if ($reminds){
   			return $reminds['num'];
   		}else{
   			return '0';
   		}
   }
   
   //查看提醒
   public function read_remind($tid){
   		$this->load->model('Remind_model','remind');
   		$uid = $this->session->userdata('user_id');
   		$this->remind->delete_remind($tid,$uid);
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