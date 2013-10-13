<?php
class Remind_model extends CI_Model{
    private $db_name = "ylz_remind";

    public function __construct(){
        parent::__construct();
        
    }

    /*
     * 查询所有回复
     */
	public function select_remind($fetch = array('*'), $limit = 100, $offset = 0) {
        $this->db->order_by("rid", "desc");       
        //$this->db->join('ylz_user', ' ylz_user.uid = ylz_post.uid','left');
        $query = $this->db->get_where($this->db_name, $fetch, $limit, $offset);
        //echo $query;
        return $query->result_array();
    }

   
  //删除提醒
    public function delete_remind($id,$uid) {
        $this->db->delete($this->db_name, array('tid' => $id,'touid' => $uid));
        return $this->db->affected_rows();
    }
    //添加修改提醒
    public function edit_remind($data,$id = '') {
    	if ($id){
    		$this->db->where('rid', $id);
            return $this->db->update($this->db_name, $data) ? true : false;
    	}else{
          return $this->db->insert($this->db_name, $data) ? true : false;
    	}
    }
 
	 public function getremind($tid,$uid){
	 	 $query = $this->db->get_where($this->db_name,array('tid' => $tid,'touid' => $uid));
         return $query->row_array();
	 }

  //查询数量
	public function select_remind_count($fetch = array('*')) {
		$this->db->where($fetch);
		$this->db->from($this->db_name);
		return $this->db->count_all_results();
    }
  //我发的帖子的提醒
  public function get_owner_remind($uid){
 	  $this->db->join('ylz_post', ' ylz_post.pid = ylz_remind.tid');
 	  $this->db->where('ylz_post.uid', $uid);
 	  $this->db->where('ylz_remind.touid', $uid);
 	  $query = $this->db->get($this->db_name);
 	  $result =  $query->result_array();
 	  $num = array();
 	  if($result){
 	  	foreach ($result as $r){
 	  		$num[] = $r['num'];
 	  	}
 	  	$count = array_sum($num);
 	  	return $count;
 	  }
   }
  //我回复帖子的提醒
	public function get_join_remind($uid){
 	  $this->db->join('ylz_post', ' ylz_post.pid = ylz_remind.tid');
 	  $this->db->where('ylz_post.uid !=', $uid);
 	  $this->db->where('ylz_remind.touid', $uid);
 	  $query = $this->db->get($this->db_name);
 	  $result =  $query->result_array();
 	  $num = array();
 	  if($result){
 	  	foreach ($result as $r){
 	  		$num[] = $r['num'];
 	  	}
 	  	$count = array_sum($num);
 	  	return $count;
 	  }else{
 	  	return '0';
 	  }
   }
}
?>