<?php
class Faq extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Faq_model','question');
        
    }

    /*
     * 获取所有的问答列表
     * @param
     */
    public function index($page = 1){

    	$this->load->helper('form');
    	$this->load->library('pagination');
        $res = $this->question->select_questions(array('pid' => '0'));
        $config['base_url'] = site_url('Faq/index');
		$config['total_rows'] = count($res);
		$config['per_page'] = '5';
		$config['use_page_numbers']=TRUE;		
		$this->pagination->initialize($config); 
		$limit ='5';
		$offset = $limit *($page-1);
		
		$result['lists'] = $this->question->select_questions(array('pid' => '0'),$limit,$offset);
		$this->load->view('header');
        $this->load->view('question',$result);
    }

  
    /*
     * 添加问题
     */
    public function edit(){
        //$data['aid'] = $this->input->post('aid');
        $data['message'] = $this->input->post('message');
        $data['uid'] = $this->input->post('uid');
        $data['create_time'] = date('y-m-d H:i:s',time());

        if($this->question->edit_question($data)){
            $this->session->set_flashdata('flashmessage', '问题添加成功');
            redirect('Faq/index');
        }
    }

   	/*
    * 回答问题
    */
    public function solve_question($pid){
    	
    	$data['pid'] =$pid;
    	$data['message'] = $this->input->post('message');
       // $data['uid'] = $this->input->post('uid');
        $data['create_time'] = date('y-m-d H:i:s',time());
   		 if($this->question->edit_question($data)){
            $this->session->set_flashdata('flashmessage', '操作成功');
            redirect('Faq/index');
        }
    }
    //
    public function get_solve($id){
    	if ($id){
    		$result = $this->question->get($id);
    		if ($result){
    			foreach ($result as $re){
    				$re['create_time'] = date("Y-m-d",strtotime($re['create_time']));
    			}
    		}
    		//$result['create_time'] = date("Y-m-d",strtotime($result['create_time']));
    		echo json_encode($result);
    	}
    }
    /*
     * 删除问题
     */
    public function delete($id){
        $this->question->delete_question($id);
        $this->session->set_flashdata('flashmessage', '删除成功');
        redirect('Faq/index');
    }
	//删除回答
	public function delete_answer($id){
		$this->question->delete_question($id);
        echo 1;
	}
	//app端返回所有问题列表
	 public function get_questions($page = 1){
        $count = $this->question->select_question_count(array('pid' => '0'));
		$limit ='10';
		$offset = $limit *($page-1);
		
		$result = $this->question->select_questions(array('pid' => '0'),$limit,$offset);
		if ($result){
			foreach ($result as &$r){
				$anwser = $this->question->select_answer(array('pid' => $r['id']));
				if ($anwser){
					$r['answer'] = $anwser['message'];
				}
			}
			$return = new Ret('ok',$result,$count);
			echo json_encode($return);
		}else{
			$return = new Ret('ok',array());
			echo json_encode($return);
		}
    }
    //app端获取问题答案
//    public function get_answer($id){
//    	 
//		$result['lists'] = $this->question->select_questions(array('pid' => $id));
//		if ($result){
//			$return = new Ret('ok',$result);
//			echo json_encode($return);
//		}else{
//			$return = new Ret('ok',array());
//			echo json_encode($return);
//		}
//    }
	
   
}





?>
