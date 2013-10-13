<?php
class Faq_model extends CI_Model{
    private $db_name = "ylz_faq";

    public function __construct(){
        parent::__construct();
        
    }

    /*
     * 查询所有的分类
     */
	public function select_questions($fetch = array('*'), $limit = 100, $offset = 0) {
        $this->db->order_by("id", "desc");       
       // $this->db->join('ylz_user', 'ylz_user.uid = ylz_faq.uid');
        $query = $this->db->get_where($this->db_name, $fetch, $limit, $offset);
        return $query->result_array();
    }

   
  //删除内容
    public function delete_question($id) {
        $this->db->delete($this->db_name, array('id' => $id));
        return $this->db->affected_rows();
    }
    //添加修改内容
    public function edit_question($data, $id = '') {
        if($id) { //修改
            $this->db->where('id', $id);
            return $this->db->update($this->db_name, $data) ? true : false;
        } else { //添加
            return $this->db->insert($this->db_name, $data) ? true : false;
        }
    }
 // 
	 public function get($id){
	 	 $query = $this->db->get_where($this->db_name,array('pid' => $id));
         return $query->result_array();
	 }
 //查询数量
	public function select_question_count($fetch = array('*')) {
		$this->db->where($fetch);
		$this->db->from($this->db_name);
		return $this->db->count_all_results();
    }
//搜索答案
	public function select_answer($fetch = array('*'), $limit = 100, $offset = 0) {
       // $this->db->join('ylz_user', 'ylz_user.uid = ylz_faq.uid');
        $query = $this->db->get_where($this->db_name, $fetch, $limit, $offset);
        return $query->row_array();
    }
}
?>