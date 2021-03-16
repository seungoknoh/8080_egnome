<?php
/**
 * 클래스 한글설명
 *
 * Created on 2018. 4. 30.
 * @package      application
 * @subpackage   models
 * @category     config
 * @author       leeminji25 <leeminji25@wixon.co.kr>
 * @link         <a href="http://codeigniter-kr.org" target="_blank">http://codeigniter-kr.org</a>
 * @version      0.1
 */

class AuthSet_m extends CI_Model{
    private $table = "auth";
    
    function __construct(){
        parent::__construct();
    }
    
    /* 리스트 */
    function auth_list($type='', $offset='', $limit='' , $stx='', $sfl=''){

        $search_query = "";
        if( $stx != "" && $sfl != "" ){
            $search_query = ' and '.$sfl.' LIKE "%'.$stx.'%" ';
        }

        $limit_query = '';
        if( $limit != '' OR $offset != ''){
            $limit_query = " LIMIT ".$offset.','.$limit;
        }
        
        $sql = "select *
                from {$this->table} 
                where 1 {$search_query} {$limit_query}";
        $query = $this -> db -> query($sql);
        log_message("debug", $sql);
        $result = null;
        if( $type == 'count' ){
            //전체 게시물 갯수반환
            $result = $query -> num_rows();
        }else{
            //게시물 리스트 변환
            $result = $query -> result();
        }

        return $result;
    }
    
    //관리자만 본인, 최고관리자 제외.
    function admin_list($mb_level, $mb_id){
        $sql = " select a.* from 
                 (select a.mb_id, a.mb_level from member a left join auth b 
                  on a.mb_id = b.mb_id where b.mb_id is NULL) a , config b 
                 where a.mb_level >= {$mb_level}
                 and b.cf_admin != a.mb_id
                 and a.mb_id != '{$mb_id}' ";
        log_message("debug", $sql);
        $query = $this -> db -> query($sql);

        $result = $query -> result();
        return $result;
    }
    
    //권한입력
    function auth_insert($data){
        $mn_idx = implode(",", $data['mn_idx']);
        $sql = "INSERT INTO {$this-> table} SET
                mn_idxs = '{$mn_idx}' ,
                au_id   = '{$data['au_id']}' ,
                mb_id   = '{$data['mb_id']}' ";
        log_message("debug", "dddd=".$mn_idx);
        $result = $this->db->query($sql);
        return $result;
    }
    
    //권한수정
    function auth_update($data){
        $result = null;
        if(count($data['mn_idx']) > 0){
            $update_data = array(
                'mn_idxs' => implode(",", $data['mn_idx']),
                'au_id' => $data['au_id']
            );
            $where = array(
                'mb_id' => $data['mb_id']
            );
            $result = $this->db->update($this->table, $update_data, $where);
        }else{
            $result = $this->auth_delete($data['mb_id']);
        }
        return $result;
    }
    
    //권한삭제 id
    function auth_delete($mb_id){
        $sql = "delete from {$this->table} where mb_id ='{$mb_id}'";
        $result = $this -> db -> query($sql);
        return $result;
    }

    //권한삭제 idx
    function auth_delete_idx($au_idx){
        $sql = "delete from {$this->table} where au_idx ='{$au_idx}'";
        $result = $this -> db -> query($sql);
        return $result;
    }
    
    function auth_view($mb_id){
        $sql = "select * from {$this->table} where mb_id = '{$mb_id}'";
        $query = $this -> db -> query($sql);
        $result = $query -> row();
        return $result;
    }

}

/* End of file authSet_m.php */
/* Location: /application/models/config/authSet_m.php */

?>