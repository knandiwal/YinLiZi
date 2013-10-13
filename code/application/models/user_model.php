<?php
class User_model extends CI_Model{
    private $db_name = "ylz_user";

    public function __construct(){
        parent::__construct();

        
    }

    /*
     * 前台登入
     */
    public function login($info){
        if(!$this->is_exists($info["username"])){
            return json_encode(new Ret('no','用户名不存在'));
            exit;
        }

        $this->db->where($info);
        $query = $this->db->get($this->db_name);
        if($query->num_rows()){

            $row_array = $query->row_array();
            //session记录用户id
            $this->session->set_userdata('user_id', $row_array["uid"]);
            //保存此刻的session_id
            $this->db->where("uid",$row_array["uid"]);
            $this->db->update($this->db_name,array('session_id'=>$this->session->userdata('session_id')));
            return json_encode(new Ret("ok",$row_array));
        }else{
            return json_encode(new Ret("no",'密码错误'));
        }
    }

    /*
     * 前台注册
     */
    public function register($info){
        if(time()-$this->session->userdata('last_reg_time')<60){
            return json_encode(new Ret('no','注册过于频繁'));
            exit;
        }
        $this->db->insert($this->db_name,$info);
        if($this->db->affected_rows()){
            $id = $this->db->insert_id();
            $user_info = $this->getInfoById($id);
            $obj = new Ret('ok','注册成功');
            //记录发帖时间，防止爆贴
            $this->session->set_userdata("last_reg_time",time());
        }else{
            $obj = new Ret('no','注册失败');
        }
        return json_encode($obj);
    }


    public function get_info($id){

        $this->db->where('uid',$id);
        $this->db->select("uid,username,userhead");
        $query = $this->db->get($this->db_name);
        return $query->row_array();
    }

/*-------------------------------------后台管理方法---------------------------------------------------*/
    /*
     * 查询所有用户
     */
	public function select_users($fetch = array('*'), $limit = 100, $offset = 0,$value = '') {
		if ($value){
			$this->db->like('username', $value);
		}
        $this->db->order_by("uid", "desc");
        $query = $this->db->get_where($this->db_name, $fetch, $limit, $offset);
        return $query->result_array();
    }


	
	/*
     * 管理员登入
     */
	public function admin_login($data) {
		$query = $this->db->get_where($this->db_name,$data);
		if($result = $query->row()){
			return $result->uid;
		}
	}

     /*
     * 某用户名是否存在
     */
    public function is_exists($account) {
        if(trim($account)) {
            $query = $this->db->get_where($this->db_name, array('username' => $account));
            return $query->num_rows() > 0 ? true : false;
        } else {
            return false;
        }
    }

   
    /*
     * logout
     */
    public function admin_logout() {
        $this->session->unset_userdata('uid');
    }
        
        
        
   //删除会员
    public function deleteById($id){
        $this->db->delete($this->db_name,array('uid'=>$id));
        return $this->db->affected_rows();

    }
    
    //管理员添加修改
    function edit_user($data, $id = '') {
        if($id) { //修改
            $this->db->where('uid', $id);
            return $this->db->update($this->db_name, $data) ? true : false;
        } else { //添加
            return $this->db->insert($this->db_name, $data) ? true : false;
        }
    }
 //根据id获取会员的信息
    public function getInfoById($id){
        $query = $this->db->get_where($this->db_name,array('uid' => $id));
        $row = $query->row_array();
        return $row;
    }
    //根据username获取会员的信息
 	public function getInfoByname($name){
        $query = $this->db->get_where($this->db_name,array('username' => $name));
        $row = $query->row_array();
        return $row;
    }




}
?>