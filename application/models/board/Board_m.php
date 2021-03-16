<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Board_m extends CI_Model{
	private $table = "board";
	private $member = null;
	function __construct(){
		parent::__construct();
		
		//파일모델
		$this -> load -> model("board/file_m");
		$this -> load -> helper("common");
		$this -> member = $this -> session -> userdata("member");
		
	}

	/* 게시물 리스트 */
	function board_list( $type='' , $op_table='', $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC', $sca='',$bo_yearmd='',$bo_scf1='',$bo_scf2='' ){
		
		$search_query = " WHERE 1 ";
		if( $op_table != '' ){
			$search_query .= " and a.op_table = '{$op_table}' ";
		}
		if( $stx != "" && $sfl != "" ){
			$search_query .= " and a.{$sfl} LIKE '%{$stx}%' ";
		}
		if( $sca != "" ){
		    $search_query .= " and a.bc_idx = {$sca} ";
		}
		if( $bo_yearmd != "" ){
			$search_query .= " and a.bo_yearmd LIKE '%{$bo_yearmd}%' ";
		 }
		 if( $bo_scf1 != "" ){
			 $search_query .= " and a.bo_scf1 = '{$bo_scf1}' ";
		 }
		 if( $bo_scf2 != "" ){
			 $search_query .= " and a.bo_scf2 = '{$bo_scf2}' ";
		 }		
		$order_query = " ORDER BY a.bo_ref DESC, a.bo_level ASC ";
		if( $sst != ""){
		    $order_query = " ORDER BY a.{$sst} {$sod}";
		}

		$limit_query = '';
		if( $limit != '' OR $offset != ''){
			$limit_query = " LIMIT {$offset}, {$limit}";
		}

		$sql = "SELECT a.* , b.bc_name, IFNULL(c.count, 0) AS file_count FROM {$this->table} a LEFT JOIN board_category b 
                ON a.bc_idx = b.bc_idx 
                LEFT JOIN (SELECT bo_idx, count(*) AS count FROM file GROUP BY bo_idx ) c 
                ON a.bo_idx = c.bo_idx  
                {$search_query} {$order_query} {$limit_query}";

		$query = $this -> db -> query($sql);
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

	/* big data 게시물 리스트 */
	function board_front_big_list( $type='' , $op_table='', $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC', $sca=''){
		$search_query = " WHERE a.op_table = '{$op_table}' AND a.bo_is_view = 1";
		
	    // if( $stx != "" && $sfl != "" ){
	    //     $search_query .= " and a.{$sfl} LIKE '%{$stx}%' ";
		// }
	    if( $sca != "" ){
	        $search_query .= " and a.bc_idx = {$sca} ";
	    }
	    
	    $order_query = " ORDER BY a.bo_ref DESC, a.bo_level ASC ";
	    if( $sst != ""){
	        $order_query = " ORDER BY a.{$sst} {$sod}";
	    }
		if( $sst == "cate" ){
			$order_query = " ORDER BY b.bc_order asc, a.bo_ref desc , a.bo_level asc ";
		}
	    
	    $limit_query = '';
	    if( $limit != '' OR $offset != ''){
	        $limit_query = " LIMIT {$offset}, {$limit}";
	    }
	    //sonoh 20210125 bf_idx추가
	    $sql = "SELECT a.* , b.bc_name, c.bf_idx, IFNULL(c.count, 0) AS file_count FROM {$this->table} a LEFT JOIN board_category b
                ON a.bc_idx = b.bc_idx
                LEFT JOIN (SELECT bo_idx, bf_idx, count(*) AS count FROM file GROUP BY bo_idx, bf_idx ) c
                ON a.bo_idx = c.bo_idx
                {$search_query} {$order_query} {$limit_query}";
		//log_message("DEBUG", "bigdataLIST-{$sql}");
		//var_dump($sql);
		$query = $this -> db -> query($sql);
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
	
	/* 게시물 리스트 */
	function board_front_list( $type='' , $op_table='', $offset='', $limit='' , $stx='', $sfl='', $sst='', $sod='DESC', $sca='',$bo_yearmd='',$bo_scf1='',$bo_scf2='', $lang='ko' ){
		$search_query = " WHERE a.op_table = '{$op_table}' AND a.bo_is_view = 1 AND bo_lang = '{$lang}'";
	    if( $stx != "" ){//sfl이 전체선택일때
	        $search_query .= " and a.bo_subject LIKE '%{$stx}%' or a.bo_content LIKE '%{$stx}%'";
		}		
	    if( $stx != "" && $sfl != "" ){
	        $search_query .= " and a.{$sfl} LIKE '%{$stx}%' ";
		}
		//year='',$analysis_type='',$species
		if( $bo_yearmd != "" ){
	       $search_query .= " and a.bo_yearmd LIKE '%{$bo_yearmd}%' ";
		}
		if( $bo_scf1 != "" ){
			$search_query .= " and a.bo_scf1 = '{$bo_scf1}' ";
		}
		if( $bo_scf2 != "" ){
			$search_query .= " and a.bo_scf2 = '{$bo_scf2}' ";
		}
	    if( $sca != "" ){
	        $search_query .= " and a.bc_idx = {$sca} ";
	    }
	    
	    $order_query = " ORDER BY a.bo_ref DESC, a.bo_level ASC ";
	    if( $sst != ""){
	        $order_query = " ORDER BY a.{$sst} {$sod}";
	    }
		if( $sst == "cate" ){
			$order_query = " ORDER BY b.bc_order asc, a.bo_ref desc , a.bo_level asc ";
		}
	    
	    $limit_query = '';
	    if( $limit != '' OR $offset != ''){
	        $limit_query = " LIMIT {$offset}, {$limit}";
	    }
	    //sonoh 20210125 bf_idx추가
	    $sql = "SELECT a.* , b.bc_name, c.bf_idx, IFNULL(c.count, 0) AS file_count FROM {$this->table} a LEFT JOIN board_category b
                ON a.bc_idx = b.bc_idx
                LEFT JOIN (SELECT bo_idx, min(bf_idx) AS bf_idx, count(*) AS count FROM file GROUP BY bo_idx ) c
                ON a.bo_idx = c.bo_idx 
                {$search_query} {$order_query} {$limit_query}";
		//log_message("DEBUG", "frontLIST-{$sql}");
		//var_dump($sql);
		$query = $this -> db -> query($sql);
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
	
	/* 게시물 삭제 */
	function board_delete($data){
		if( !empty($data->bo_level) ){
			//하위 갯수 조정
			$sql =  "UPDATE {$this->table}  SET bo_child = bo_child-1 
                     WHERE bo_ref={$data->bo_ref} and ";
			$sql .= " bo_level = SUBSTR('{$data->bo_level}', 1, LENGTH('{$data->bo_level}')-2)";
			$this->db->query($sql);
		}
		//썸네일삭제
		delete_thumbnail( $data -> bo_content );
		
        //이미지삭제
		delete_editor_image( $data-> bo_content );

		$sql = "DELETE FROM {$this->table} WHERE bo_idx={$data->bo_idx} and bo_child = 0";
		$query = $this -> db -> query($sql);

		//log_message("DEBUG", "DELETE - bo_idx:{$data->bo_idx} ".serialize($this->member));
	}
	
	/* 해당 게시판 게시물 모두 삭제 */
	function board_all_delete($op_table){
	    $sql = "select * from {$this->table} where op_table = '{$op_table}'";
	    $query = $this -> db -> query($sql);
	    
	    $result = $query -> result();
	    foreach ($result as $list){
	        //썸네일삭제
	        delete_thumbnail( $list -> bo_content );
	        //이미지삭제
	        delete_editor_image( $list -> bo_content );
	    }
	    $sql = "delete from {$this->table} where op_table = '{$op_table}'";
		$result = $this -> db -> query($sql);
		
	    //log_message("DEBUG", "ALL DELETE - {$sql}".serialize($this->member));
	    return $result;
	}
	

	/* 게시물 조회수증가 */
	function board_update_hit($data){
		$sql = "UPDATE {$this->table} SET bo_hit=bo_hit+1 WHERE bo_idx = ".$data['bo_idx']." and op_table ='".$data['op_table']."'";
//log_message("DEBUG", "board_update_hit-{$sql}");
		$this->db->query($sql);
	}
	
	/* 게시물 뷰 */
	function board_view($data){
		$sql = "SELECT * FROM {$this->table} 
				WHERE bo_idx = {$data['bo_idx']} and op_table ='{$data['op_table']}'";
		if( !isset($data['bo_idx']) ) return false;
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}
/* big data 게시물 뷰 */
	function board_big_view($data){

		$sql = "SELECT  a.*, b.bc_name, c.bf_filename FROM {$this->table} a LEFT JOIN board_category b
					ON a.bc_idx = b.bc_idx
					LEFT JOIN (SELECT bo_idx, bf_filename, count(*) AS count FROM file GROUP BY bo_idx, bf_filename  ) c 
					ON a.bo_idx = c.bo_idx
				WHERE a.bc_idx = b.bc_idx and a.bo_idx = c.bo_idx and a.bo_idx = {$data['bo_idx']} and a.op_table ='{$data['op_table']}'";
		if( !isset($data['bo_idx']) ) return false;
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}
	
	//제일 작은 값
	function board_view_min_idx($op_table){
		$sql = "SELECT min(bo_idx) as bo_min_idx FROM {$this -> table} 
				WHERE op_table ='{$op_table}'";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}
	
	/* 게시물 뷰 리스트 */
	function board_view_list($data, $type="prev"){
	    $sql ="";
	    $search_sql = "AND bo_is_view = 1 ";
	    if( $data['sca'] != null ){
	        $search_sql .= " AND bc_idx = {$data['sca']} ";
	    }
	    if( $type == "prev" ){
	        $sql = "SELECT * FROM {$this->table} 
                    WHERE bo_ref   > {$data['bo_ref']} 
                    AND   bo_idx  != {$data['bo_idx']} 
                    AND   op_table ='{$data['op_table']}' {$search_sql}
                    ORDER BY bo_ref ASC, bo_level ASC limit 1
                    ";
	    }
	    if( $type=="next"){
	        $sql = "SELECT * FROM {$this->table} 
                    WHERE bo_ref   < {$data['bo_ref']}
                    AND   bo_idx  != {$data['bo_idx']}
                    AND   op_table ='{$data['op_table']}' {$search_sql}
                    ORDER BY bo_ref DESC, bo_level ASC limit 1
                    ";
	    }
		//log_message("DEBUG", "board_view_list - {$sql} ");
	    $query = $this -> db -> query($sql);
	    $result = $query -> row();

	    return $result;
	}
	
	/* 게시물입력 */
	function board_insert($data){
		$output_result = array();
		$mb_id = $this -> session -> userdata("mb_id");
		$sql = "";
		$insert_data = array(
			'op_table'   => $data['op_table'],
		    'bo_writer'  => $mb_id,
		    'bc_idx'     => $data['bc_idx'],
			'bo_subject' => $data['bo_subject'],
			'bo_client' => $data['bo_client'],
			'bo_company' => $data['bo_company'],						
			'bo_caption' => $data['bo_caption'],
			'bo_content' => $data['bo_content'],
			'bo_regdate' => date("Y-m-d H:i:s"),
			'bo_is_view' => $data['bo_is_view'],
			'bo_lang' => 'ko',
			'bo_is_secret' => empty($data['bo_is_secret']) ? null : $data['bo_is_secret'],
			'bo_is_file_thumb' => empty($data['bo_is_file_thumb']) ? null : $data['bo_is_file_thumb'],
			'bo_passwd' => empty($data['bo_passwd']) ? null : $data['bo_passwd'],
			'bo_ip' => get_client_ip(),
			'mb_id' => $data['mb_id'],
			'bo_url' => empty($data['bo_url']) ? null : $data['bo_url'],
			'bo_url2' => empty($data['bo_url2']) ? null : $data['bo_url2'],
			'bo_yearmd' => empty($data['bo_yearmd']) ? null : $data['bo_yearmd'],
			'bo_yearmd2' => empty($data['bo_yearmd2']) ? null : $data['bo_yearmd2'],
			'bo_scf1' => empty($data['bo_scf1']) ? null : $data['bo_scf1'],
			'bo_scf2' => empty($data['bo_scf2']) ? null : $data['bo_scf2'],
			'bo_subtitle' => empty($data['bo_subtitle']) ? null : $data['bo_subtitle']
		);
		if( $data['bo_ref'] != null ){
			//답변입력
			$insert_data['bo_ref'] = $data['bo_ref'];
			$insert_data['bo_level'] = $data['bo_level'];
			$result = $this -> db -> insert($this->table, $insert_data);
			
			if( $result ){
				$output_result['result'] = $result;
				$output_result['bo_idx'] = $this -> db -> insert_id();
				$sql = "UPDATE ".$this->table." SET bo_child=bo_child+1 WHERE bo_idx=".$data['bo_parent'];
				$this -> db -> query($sql);
			}
		}else{
			//새글입력
			$result = $this -> db -> insert($this->table, $insert_data);
			$bo_idx = $this -> db -> insert_id();
			
			//bo_ref 삽입
			if( $result ){
				$update_data = array('bo_ref' => $bo_idx );
				$where_data = array('bo_idx' => $bo_idx );
				$result = $this->db->update($this->table, $update_data , $where_data);
				
				$output_result['result'] = $result;
				$output_result['op_table'] = $data['op_table'];
			}
			$output_result['bo_idx'] = $bo_idx;
		}
		//log_message("DEBUG", "INSERT - op_table:{$insert_data['op_table']} bo_idx:{$insert_data['bo_idx']} ".serialize($this->member));
		return $output_result;
	}
	
	/* 게시물수정 */
	function board_update($data){
		$update_data = array(
		    'bo_is_view' => $data['bo_is_view'],
			'bo_subject' => $data['bo_subject'],
			'bo_client' => $data['bo_client'],
			'bo_company' => $data['bo_company'],				
			'bo_content' => $data['bo_content'],
			'bo_caption' => $data['bo_caption'],
			'bo_lang' => 'ko',
			'bo_is_secret' => $data['bo_is_secret'],
			'bo_is_file_thumb' => empty($data['bo_is_file_thumb']) ? null : $data['bo_is_file_thumb'],
			'bc_idx' => $data['bc_idx'],
			'bo_url' => empty($data['bo_url']) ? null : $data['bo_url'],
			'bo_url2' => empty($data['bo_url2']) ? null : $data['bo_url2'],
			'bo_yearmd' => empty($data['bo_yearmd']) ? null : $data['bo_yearmd'],
			'bo_yearmd2' => empty($data['bo_yearmd2']) ? null : $data['bo_yearmd2'],			
			'bo_scf1' => empty($data['bo_scf1']) ? null : $data['bo_scf1'],
			'bo_scf2' => empty($data['bo_scf2']) ? null : $data['bo_scf2'],
			'bo_subtitle' => empty($data['bo_subtitle']) ? null : $data['bo_subtitle']
		);
		$where = array(
			'bo_idx' => $data['bo_idx'],
			'op_table' => $data['op_table']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		//log_message("DEBUG", "UPDATE - op_table : {$where['op_table']} bo_idx : {$where['bo_idx']} ".serialize($this->member));
		return $result;
	}
	
	/* 게시물 내용 url 수정 */
	function board_update_url($data){

	    $result = false;
	    //2. replace로 치환될 데이터 확인
	    $sql = "SELECT REPLACE(bo_content, '{$data['url_prev']}', '{$data['url_next']}') AS bo_content 
                FROM {$this->table} WHERE bo_content LIKE '%{$data['url_prev']}%' ";
	    
	    if( count($result) > 0){
    	    //3. 데이터치환실행하기-게시판
    	    $sql = "UPDATE {$this->table} 
                    SET bo_content = REPLACE(bo_content, '{$data['url_prev']}', '{$data['url_next']}') ";
    	    $result = $this -> db -> query($sql);
    	    
    	    //3. 데이터치환실행하기-컨텐츠
    	    $sql = "UPDATE contents
                    SET cn_content = REPLACE(cn_content, '{$data['url_prev']}', '{$data['url_next']}') ";
    	    $result = $this -> db -> query($sql);
	    }
	    return $result;
	}
	/* 검색컬럼 수정 */
	function get_board_search_list($table='board_search_field', $type='', $page='', $rows=''){
		$limit_query = "";
		if( $page != '' ){
			$limit_query = " limit ".(($page-1) * $rows).", ".$rows;
		}
		$sql = "select bs_idx, bs_name from ".$table." order by bs_idx asc ".$limit_query;
		$query = $this -> db -> query($sql);
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
	/* 카테고리수정 */
	function board_update_category($data){
		$sql = " UPDATE {$this -> table} SET bc_idx = {$data['bc_idx']} WHERE op_table = '{$data['op_table']}' AND bo_idx in ({$data['bo_idxs']})" ;

		$result = $this -> db -> query($sql);
		//log_message("DEBUG", "카테고리수정 ".$sql.serialize($this -> member));

		return $result;
	}

	/* End of file board_m.php */
	/* Location : ./application/models/board/board_m.php */
}
?>