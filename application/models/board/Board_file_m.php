<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Board_file_m extends CI_Model{
    public $table = "file";

    function __construct(){
        parent::__construct();
        $this -> load -> helper('thumbnail');
    }
    
    //파일리스트
    function list($type="", $data){
        $sql = "select * from {$this->table} 
                where bo_idx = {$data['bo_idx']} and bf_is_delete != 1";
        $query = $this->db->query($sql);
        if( $type == "" ){
            $result = $query -> result();
        }else if($type == "count"){
            $result = $query -> num_rows();
        }
        return $result;
    }
    
    //게시물에 해당하는 파일찾기
    function get_file_board($data){
        $sql = "select * from {$this->table}
                where bo_idx = {$data->bo_idx} and op_table = '{$data->op_table}' and bf_is_delete != 1";
        $result = $this-> db -> query($sql) -> result();

        log_message("debug", "get_file()-".$sql);
        return $result;
    }
    
    //파일갯수
    function get_file_num($op_table, $bo_idx){
        $sql = "select count(*) as bf_max_num from {$this->table}
                where bo_idx = {$bo_idx} and op_table = '{$op_table}' ";
        $result = $this->db->query($sql)->row();
        return $result->bf_max_num;
    }
    
    //파일찾기
    function get_file_idx($bf_idx){
        $sql = "select * from {$this->table}
                where bf_idx = {$bf_idx} ";
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    //파일등록
    function insert($data){
        $sql = "INSERT INTO {$this->table} 
                SET  op_table = '{$data['op_table']}',
					 bo_idx = '{$data['bo_idx']}',
					 bf_num = '{$data['bf_num']}',
					 bf_filename = '{$data['bf_filename']}',
					 bf_download = 0,
					 bf_filesize = '{$data['bf_filesize']}',
					 bf_type = '{$data['bf_type']}',
                     bf_source = '{$data['bf_source']}',
					 bf_regdate = '".date("Y-m-d H:i:s")."' ";
        $result = $this-> db ->query($sql);
        return $this->db->insert_id();
    }

    //업데이트
    function update($data){
        $sql = "UPDATE {$this->table}
                SET  
					 bf_filename = '{$data['bf_filename']}',
					 bf_download = 0,
					 bf_filesize = '{$data['bf_filesize']}',
					 bf_type = '{$data['bf_type']}',
                     bf_source = '{$data['bf_source']}',
					 bf_regdate = '".date("Y-m-d H:i:s")."' 
                WHERE
                     op_table = '{$data['op_table']}' and
					 bo_idx = '{$data['bo_idx']}' and
					 bf_num = '{$data['bf_num']}' ";
        $result = $this-> db ->query($sql);
        return $result;
    }
    
    //파일삭제
    function delete($data){
        $files = $this -> get_file_board($data);
        if( count($files) > 0 ){
            foreach( $files as $file ){
                $delete_dir = UPLOAD_FILE_DIR.'/'.$file->op_table.'/';
                delete_file_thumbnail($file->op_table, $file->bf_filename);
                $delete_file = $delete_dir.$file->bf_filename;                 
                @unlink($delete_file);
                log_message("debug", $delete_file);
                $this->delete_idx($file->bf_idx);
                log_message("debug", "삭제-".$file->bf_idx);
            }
        }else{
            log_message("debug", "삭제할 파일없음");
        }
    }

    
    //파일삭제
    function delete_select($data){
        $files = $this -> get_file($data);
        if( count($files) > 0 ){
            foreach( $files as $file ){
                @unlink(UPLOAD_FILE_DIR.'/'.$file->op_table.'/'.$file->bf_filename);
                log_message("debug", UPLOAD_FILE_DIR.'/'.$file->op_table.'/'.$file->bf_filename);
                $this->delete_idx($file->bf_idx);
                log_message("debug", "삭제-".$file->bf_idx);
            }
        }else{
            log_message("debug", "삭제할 파일없음");
        }
    }
    
    //게시물에 해당하는 파일찾기
    function get_file($data){
        $bf_idxs = implode( $data['bf_idxs'], "," );
        $sql = "SELECT * FROM {$this->table}
                WHERE bf_idx IN ({$bf_idxs}) AND op_table = '{$data['op_table']}' ";
        $result = $this-> db -> query($sql) -> result();
        
        log_message("debug", "get_file()-".$sql);
        return $result;
    }
    
    //파일삭제
    function delete_idx($bf_idx){
        $sql = "delete from {$this->table} WHERE
                 bf_idx = '{$bf_idx}' ";
        $result = $this-> db ->query($sql);
        
        log_message("debug", $sql);
        return $result;
    }
    
    //게시판삭제시 해당 파일 전체 삭제
    function file_all_delete($op_table){
        $sql = "select * from {$this->table}
                where op_table = '{$op_table}' ";
        $files = $this-> db -> query($sql) -> result();
        if( count($files) > 0 ){
            foreach( $files as $file ){
                @unlink(UPLOAD_FILE_DIR.'/'.$file->op_table.'/'.$file->bf_filename);
                log_message("debug", UPLOAD_FILE_DIR.'/'.$file->op_table.'/'.$file->bf_filename);
                $this->delete_idx($file->bf_idx);
                log_message("debug", "삭제-".$file->bf_idx);
            }
        }else{
            log_message("debug", "삭제할 파일없음");
        }
    }

    //파일등록
    function item_update($bf_idx, $data){
		$add_data = array(
		);
        $where = array(
            "bf_idx" => $bf_idx
        );
		$update_data = array_merge($data, $add_data);
		$result = $this->db->update($this->table, $update_data, $where);
		return $bf_idx;
    }

    //파일삭제(파일도 같이삭제)
    function item_with_file_delete($bf_idx){
        $file = $this->get_file_idx($bf_idx);
        if( $file != null ){
            $delete_dir = UPLOAD_FILE_DIR.'/'.$file->op_table.'/';
            $delete_file = $delete_dir.$file->bf_filename;
            @unlink($delete_file);
            log_message("debug", "삭제-".$file->bo_idx);    
            
            $this->item_update($bf_idx, array("bf_is_delete"=>1));
        }
    } 
}

/* End of file Board_file_m.php */
/* Location : ./application/models/board/Board_file_m.php */
?>