<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
최신글 모델
 */
class Latest_m extends CI_Model{
    private $board_table = "board";
    private $file_table = "file";
    private $category_table = "board_category";
    private $board_option_table = "board_option";
    
    function __construct(){
        parent::__construct();
        
        $this -> load -> helper("common");
        $this -> load -> model("board/file_m");
    }

    //게시판 최신글 내보내기
    function admin_total_list($size=0, $is_reply = false){
        $sql = "SELECT d.op_name, a.bo_idx, a.bo_writer, a.bo_is_view, a.bo_regdate, a.bo_level, a.op_table, a.bo_is_secret, a.bo_is_file_thumb, c.bc_name, a.bo_subject, a.bo_content , IFNULL(count, 0) AS file_count
        FROM {$this->board_table} a LEFT JOIN
        ( SELECT bo_idx, count(*) as count FROM {$this->file_table} GROUP BY bo_idx ) b
        ON a.bo_idx = b.bo_idx
        LEFT JOIN  ( SELECT bc_idx, bc_name FROM {$this->category_table}  ) c
        ON c.bc_idx = a.bc_idx 
        LEFT JOIN {$this->board_option_table} d 
        ON a.op_table = d.op_table ";
        if( !$is_reply ){
            $sql .= " WHERE a.bo_level = '' ";
        }
        $sql .= " ORDER BY a.bo_ref DESC, a.bo_level ASC LIMIT 0, {$size} ";

        $query = $this -> db -> query($sql);
        $result = $query -> result();
        
        $lists = array();
        $i = 0;
        foreach ($result as $ls){
            $lists[$i] = $ls;
            $lists[$i] -> regdate = date("Y-m-d", strtotime($ls->bo_regdate));
            $lists[$i] -> adminlink = "/backS1te/board/update/{$ls->op_table}/{$ls->bo_idx}";
            $lists[$i] -> link = "/board/view/{$ls->op_table}/{$ls->bo_idx}";
            $lists[$i] -> content = common_content($ls->bo_content, 200);
            $lists[$i] -> subject = common_content($ls->bo_subject, 100);
            $lists[$i] -> is_new = strtotime("{$ls->bo_regdate} +24 hours") > strtotime("Now");
            $lists[$i] -> is_file =  $ls->file_count > 0;
            $lists[$i] -> is_secret = $ls->bo_is_secret == 1 ? true : false;
            $lists[$i] -> index = $i;
            if( $is_reply ){
                $lists[$i] -> is_reply = $ls -> bo_level != "" ? true : false;
            }
            $i++;
        }
        
        return $lists;
    }

    //게시판 최신글 내보내기
    function list($op_table, $size=0, $content_length=200, $subject_length=100, $is_reply = false){
        $sql = "SELECT a.bo_idx, a.bo_writer, a.bo_regdate, a.bo_level, a.op_table, a.bo_is_secret, a.bo_is_file_thumb, c.bc_name, a.bo_subject, a.bo_content , IFNULL(count, 0) AS file_count
        FROM {$this->board_table} a LEFT JOIN
        ( SELECT bo_idx, count(*) as count FROM {$this->file_table} GROUP BY bo_idx ) b
        ON a.bo_idx = b.bo_idx
        LEFT JOIN  ( SELECT bc_idx, bc_name FROM {$this->category_table}  ) c
        ON c.bc_idx = a.bc_idx 
        WHERE a.op_table = '{$op_table}' and a.bo_is_view = 1 ";
        if( !$is_reply ){
            $sql .= " and a.bo_level = '' ";
        }
        $sql .= " ORDER BY a.bo_ref DESC, a.bo_level ASC LIMIT 0, {$size} ";

        $query = $this -> db -> query($sql);
        $result = $query -> result();
        
        $lists = array();
        $i = 0;
        
        foreach ($result as $ls){
            $lists[$i] = $ls;
            //썸네일
            if( $lists[$i] -> bo_is_file_thumb == 1){
                $lists[$i] -> thumbnail = is_file_thumbnail($this->file_m->list("", array( 'bo_idx' => $lists[$i] -> bo_idx )), 300, 400, $ls->bo_subject);
            }else{
                $lists[$i] -> thumbnail = is_thumbnail($ls->bo_content, 300, 400, $ls->bo_subject);
            }
            $lists[$i] -> regdate = date("Y-m-d", strtotime($ls->bo_regdate));
            $lists[$i] -> link = "/board/view/{$ls->op_table}/{$ls->bo_idx}";
            $lists[$i] -> content = common_content($ls->bo_content, $content_length);
            $lists[$i] -> subject = common_content($ls->bo_subject, $subject_length);
            $lists[$i] -> is_new = strtotime("{$ls->bo_regdate} +24 hours") > strtotime("Now");
            $lists[$i] -> is_file =  $ls->file_count > 0;
            $lists[$i] -> is_secret = $ls->bo_is_secret == 1 ? true : false;
            $lists[$i] -> index = $i;
            if( $is_reply ){
                $lists[$i] -> is_reply = $ls -> bo_level != "" ? true : false;
            }
            $i++;
        }
        
        return $lists;
    }
    //게시판 최신글 내보내기
    function latest_list_by_lang($op_table, $lang="ko", $size=0, $content_length=200, $subject_length=100, $is_reply = false, $thumb_width=300, $thumb_height=300){
        $sql = "SELECT a.bo_idx, a.bo_writer, a.bo_regdate, a.bo_yearmd, a.bo_level, a.op_table, 
        a.bo_is_secret, a.bo_is_file_thumb, c.bc_name, a.bo_subject, a.bo_content, a.bo_hit, 
        IFNULL(count, 0) AS file_count
        FROM {$this->board_table} a LEFT JOIN
        ( SELECT bo_idx, count(*) as count FROM {$this->file_table} GROUP BY bo_idx ) b
        ON a.bo_idx = b.bo_idx
        LEFT JOIN  ( SELECT bc_idx, bc_name FROM {$this->category_table}  ) c
        ON c.bc_idx = a.bc_idx 
        WHERE a.op_table = '{$op_table}' and a.bo_is_view = 1 ";
        if( !$is_reply ){
            $sql .= " and a.bo_level = '' ";
        }
        if( $lang != "" ){
            $sql .= " AND bo_lang = '{$lang}'";
        }
        $sql .= " ORDER BY a.bo_ref DESC, a.bo_level ASC LIMIT 0, {$size} ";

        $query = $this -> db -> query($sql);
        $result = $query -> result();
        
        $lists = array();
        $i = 0;
        foreach ($result as $ls){
            $lists[$i] = $ls;
            //썸네일
            if( $lists[$i] -> bo_is_file_thumb == 1){
                $lists[$i] -> thumbnail = is_file_thumbnail($this->file_m->list("", array( 'bo_idx' => $lists[$i] -> bo_idx )), $thumb_width, $thumb_height, $ls->bo_subject);
            }else{
                $lists[$i] -> thumbnail = is_thumbnail($ls->bo_content, $thumb_width, $thumb_height, $ls->bo_subject);
            }
            $lists[$i] -> regdate = date("Y-m-d", strtotime($ls->bo_regdate));
            $lists[$i] -> yearmd = strtotime($ls->bo_yearmd);            
            $lists[$i] -> link = "/board/view/{$ls->op_table}/{$ls->bo_idx}";
            $lists[$i] -> content = common_content(strip_tags($ls->bo_content), $content_length);
            $lists[$i] -> subject = common_content($ls->bo_subject, $subject_length);
            $lists[$i] -> is_new = strtotime("{$ls->bo_regdate} +24 hours") > strtotime("Now");
            $lists[$i] -> is_file =  $ls->file_count > 0;
            $lists[$i] -> is_secret = $ls->bo_is_secret == 1 ? true : false;
            $lists[$i] -> index = $i;
            if( $is_reply ){
                $lists[$i] -> is_reply = $ls -> bo_level != "" ? true : false;
            }
            $i++;
        }
        
        return $lists;
    }    
    /* End of file Latest_m.php */
    /* Location : ./application/models/board/Latest_m.php */
}
?>