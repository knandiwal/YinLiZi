<?php
class Comment_model extends CI_Model{
    private $db_name = "ylz_comment";

    public function __construct(){
        parent::__construct();
        
    }

    /*
     * 查询所有回复
     */
	public function select_comment($fetch = array('*'), $limit = 100, $offset = 0) {
        $this->db->order_by("id", "asc");       
        $this->db->join('ylz_user', ' ylz_user.uid = ylz_comment.uid');
        $this->db->select("userhead,username,id,tid,ylz_user.uid,content,ylz_comment.createtime");
        $query = $this->db->get_where($this->db_name, $fetch, $limit, $offset);
        //echo $query;
        return $query->result_array();
    }

   
  //删除回复
    public function delete_comment($id) {
        $this->db->delete($this->db_name, array('id' => $id));
        return $this->db->affected_rows();
    }
    //添加修改回复
    public function edit_comment($data, $id = '') {
        if($id) { //修改
            $this->db->where('id', $id);
            return $this->db->update($this->db_name, $data) ? true : false;
        } else { //添加
            return $this->db->insert($this->db_name, $data) ? true : false;
        }
    }
 
	 public function get($id){
	 	 $query = $this->db->get_where($this->db_name,array('id' => $id));
         return $query->row_array();
	 }

  //返回贴子id
  	public function selectpostid($uid, $limit = 100, $offset = 0){
  		$this->db->order_by("ylz_post.commenttime", "desc");       
        $this->db->join('ylz_user', ' ylz_user.uid = ylz_comment.uid');
        $this->db->join('ylz_post', ' ylz_post.pid = ylz_comment.tid','left');
        $this->db->distinct();
        $this->db->select("tid");
//
        $this->db->where('ylz_post.uid !=', $uid);
        $this->db->where('ylz_comment.uid', $uid);
        $query = $this->db->get($this->db_name, $limit, $offset);

        
        //$query = $this->db->get_where($this->db_name, array('ylz_comment.uid' => $uid),$limit, $offset);
        return $query->result_array();
  	}
  //返回我参与贴子的数量
  public function selectpostcount($uid){
        $this->db->join('ylz_user', ' ylz_user.uid = ylz_comment.uid');
        $this->db->join('ylz_post', ' ylz_post.pid = ylz_comment.tid');
        $this->db->distinct();
        $this->db->select("tid");
        $this->db->where('ylz_post.uid !=', $uid);
        $this->db->where('ylz_comment.uid', $uid);
        $query = $this->db->get($this->db_name);
        //$query = $this->db->get_where($this->db_name, array('ylz_comment.uid' => $uid));
        $result =  $query->result_array();
        return count($result);
  }
  //查询数量
	public function select_comment_count($fetch = array('*')) {
		$this->db->where($fetch);
		$this->db->join('ylz_user', ' ylz_user.uid = ylz_comment.uid');
		$this->db->from($this->db_name);
		return $this->db->count_all_results();
    }
	 

}
?>
