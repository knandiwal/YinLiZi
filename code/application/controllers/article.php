<?php
class Article extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Article_model','article');
        
    }

    /*
     * 获取所有的文章列表
     * @param
     */
    public function index($page = 1){
    	$this->load->model('Article_category_model','category');
    	//获取分类
    	$res = $this->category->select_categories(array());
    	if ($res){
    		$category[] = '--请选择分类--';
    		foreach ($res as $re){
    			$category[$re['id']] = $re['name'];
    		}
    		$result['cats'] = $category;
    	}
    	
    	$this->load->helper('form');
    	$this->load->library('pagination');
        $res = $this->article->select_article(array());
        $cat = $this->input->post('cat_id');
        $config['base_url'] = site_url('Article/index');
		$config['total_rows'] = count($res);
		$config['per_page'] = '5';
		$config['use_page_numbers']=TRUE;		
		$this->pagination->initialize($config); 
		$limit = '5';
		$offset = $limit *($page-1);
		if ($cat){
			$result['catid'] = $cat;
			$result['list'] = $this->article->select_article(array('cat_id' => $cat),$limit,$offset);
		}else{
			$result['catid'] = '';
			$result['list'] = $this->article->select_article(array(),$limit,$offset);
		}
		 $this->load->view('header');
        $this->load->view('article',$result);
    }

   public function showeidt($id = ''){
   		$this->load->helper('form');
   		$this->load->model('Article_category_model','category');
   		//获取所有分类
   		$categories = $this->category->select_categories(array());
   		if ($categories){
   			foreach ($categories as $cat){
   				$cate[$cat['id']] = $cat['name'];
   				
   			}
   		}
   		$data = array();
   			$article = array_pop($this->article->select_article(array('aid' => $id))); 
   			$data = array(
   				'aid' => $article['aid'],
   				'title' => $article['title'],
   				'shortdesc' => $article['shortdesc'],
   				'content' => $article['content'],
   				'cat_id' => $article['cat_id'],
   				'category' => $cate
   			);
   		 $this->load->view('header');
   		$this->load->view('editarticle',$data);
   		
   }
    /*
     * 添加修改文章
     */
    public function edit(){
    	header("content-type:text/html;charset=utf8");
        $data['aid'] = $this->input->post('aid');
        $data['title'] = $this->input->post('title');
        $data['shortdesc'] = $this->input->post('shortdesc');
        $data['content'] = $this->input->post('content');
        $data['cat_id'] = $this->input->post('cat_id');
        if (!$this->input->post('aid')){
        	$data['createtime'] = date('y-m-d H:i:s',time());
        }
        $data['updatetime'] = date('y-m-d H:i:s',time());

        if($this->article->edit_article($data, $this->input->post('aid'))){
            if ($data['aid']){
                $this->session->set_flashdata('flashmessage', '文章编辑成功');
            }else{
                $this->session->set_flashdata('flashmessage', '文章添加成功');
            }
            redirect('Article/index');
        }else{
            $this->session->set_flashdata('flashmessage', '操作失败');
            redirect('Article/index');
        }
    }

   
    /*
     * 删除文章
     */
    public function delete($id){
        $this->article->delete_article($id);
        $this->session->set_flashdata('flashmessage', '删除成功');
        redirect('Article/index');
    }


   /*
     * 手机端获取银离子知识
     */

	public function get_ylz_knowledge($id = 1){
       	//获取分类号
       	 $this->load->model('Article_category_model','category');
       	 if ($id == 1){
       	 	$aid = $this->category->get_id('银离子知识');
       	 	$article = $this->article->get_article(array('cat_id' => $aid[0]['id']));
       	 }elseif ($id == 2){
       	 	$aid = $this->category->get_id('银离子故事');
       	 	$article = $this->article->get_article(array('cat_id' => $aid[0]['id']));
       	 }
       	
		if ($article){
	       	 $result = new Ret('ok',$article);
	       	 echo json_encode($result);
       	 }else{
       	 	$result = new Ret('ok',array());
	       	 echo json_encode($result);
       	 }
    }
    
//	public function get_ylz_story($page = 1){
//       	//获取分类号
//       	 $this->load->model('Article_category_model','category');
//       	 $aid = $this->category->get_id('银离子故事');
//       	 $limit = '10';
//       	 $offset = $limit *($page-1);
//       	 $count = $this->article->select_article_count(array('cat_id' => $aid[0]['id']));
//       	 $article = $this->article->select_article(array('cat_id' => $aid[0]['id']),$limit,$offset);
//       	 
//       	 if ($article){
//       	 	 foreach ($article as $ar){
//       	 	 	$return[$ar['aid']]['aid'] = $ar['aid'];
//       	 	 	$return[$ar['aid']]['title'] = $ar['title'];
//       	 	 	$return[$ar['aid']]['createtime'] = $ar['createtime'];
//       	 	 }
//       	 	foreach ($return as $r){
//       	 	 	$re[] = $r;
//       	 	 }
//	       	 $result = new Ret('ok',$re,$count);
//	       	 echo json_encode($result);
//       	 }else{
//       	 	 $result = new Ret('ok',array());
//	       	 echo json_encode($result);
//       	 }
//    }
//
//
//	 /*
//     * 手机端获取银离子故事
//     */
//
//	public function get_ylz_story(){
//       	//获取分类号
//       	 $this->load->model('Article_category_model','category');
//       	 $aid = $this->category->get_id('银离子故事');
//       	 $article = $this->article->select_article(array('cat_id' => $aid[0]['id']));
//       	 $result = new Ret('ok',$article);
//       	 echo json_encode($result);
//    }
//    
// 	/*
//     * 手机端获取品牌故事
//     */
//
//	public function get_story(){
//       	//获取分类号
//       	 $this->load->model('Article_category_model','category');
//       	 $aid = $this->category->get_id('品牌故事');
//       	 $article = $this->article->select_article(array('cat_id' => $aid[0]['id']));
//       	 $result = new Ret('ok',$article);
//       	 echo json_encode($result);
//    }
//
//	/*
//     * 手机端获取企业文化
//     */
//
//	public function get_culture(){
//       	//获取分类号
//       	 $this->load->model('Article_category_model','category');
//       	 $aid = $this->category->get_id('企业文化');
//       	 $article = $this->article->select_article(array('cat_id' => $aid[0]['id']));
//       	 $result = new Ret('ok',$article);
//       	 echo json_encode($result);
//    }

	/*
     * 手机端获取公司动态
     */

	public function get_company_news($page = 1,$limit = 5){
       	//获取分类号
       	 $this->load->model('Article_category_model','category');
       	 $aid = $this->category->get_id('公司新闻');
       	// $limit = '5';
       	 $offset = $limit *($page-1);
       	 $count = $this->article->select_article_count(array('cat_id' => $aid[0]['id']));
       	 $article = $this->article->select_article(array('cat_id' => $aid[0]['id']),$limit,$offset);
		 if ($article){
	       	  foreach ($article as $ar){
       	 	 	$return[$ar['aid']]['aid'] = $ar['aid'];
       	 	 	$return[$ar['aid']]['title'] = $ar['title'];
       	 	 	$return[$ar['aid']]['createtime'] = $ar['createtime'];
       	 	 }
       	 	 foreach ($return as $r){
       	 	 	$re[] = $r;
       	 	 }
	       	 $result = new Ret('ok',$re,array('totalItem' => $count));
	       	 echo json_encode($result);
       	 }else{
       	 	 $result = new Ret('ok',array());
	       	 echo json_encode($result);
       	 }
    }

	/*
     * 手机端获取行业动态
     */

	public function get_industry_news($page = 1,$limit = 5){
       	//获取分类号
       	 $this->load->model('Article_category_model','category');
       	 $aid = $this->category->get_id('行业动态');
       	// $limit = '5';
       	 $offset = $limit *($page-1);
       	 $count = $this->article->select_article_count(array('cat_id' => $aid[0]['id']));
       	 $article = $this->article->select_article(array('cat_id' => $aid[0]['id']),$limit,$offset);
		 if ($article){
	       	  foreach ($article as $ar){
       	 	 	$return[$ar['aid']]['aid'] = $ar['aid'];
       	 	 	$return[$ar['aid']]['title'] = $ar['title'];
       	 	 	$return[$ar['aid']]['createtime'] = $ar['createtime'];
       	 	 }
		 	foreach ($return as $r){
       	 	 	$re[] = $r;
       	 	 }
	       	 $result = new Ret('ok',$re,array('totalItem' => $count));
	       	 echo json_encode($result);
       	 }else{
       	 	 $result = new Ret('ok',array());
	       	 echo json_encode($result);
       	 }
    }
    //显示文章详细
    public function get_articlecontent($id){
    	if ($id){
    		$this->load->model('Article_category_model','category');
    		$content = $this->article->get_article(array('aid' => $id));
    		if ($content[0]['cat_id']){
    			$cname = $this->category->get_name($content[0]['cat_id']);
    		}
    		$result = new Ret('ok',$content,$cname[0]['name']);
	       	echo json_encode($result);
    	}
    }

}





?>
