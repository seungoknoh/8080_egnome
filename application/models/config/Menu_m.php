<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
	공통게시판 모델
*/
class Menu_m extends CI_Model{
	private $table = "menu";
    private $mb_id = "";
    private $config = null;
    
	function __construct(){
		parent::__construct();
		$this -> load -> model('config/config_m');
		$this-> config = $this -> config_m -> get_config();
	}
	
	/* 메뉴정보 
	 * @$current_url : 현재 링크 
	 * */
	
	function get_currentURL(){
	    //$current = $_SERVER['REDIRECT_URL'];
	    $current = $this -> uri -> slash_segment(1, 'leading');
	    $current .= $this -> uri -> slash_segment(2, 'leading');

	    if( $this -> uri -> segment(2) == "board" ){
	        $current .= "/lists";
	    }
	    if( $this -> uri -> segment(2) == "board" && $this -> uri -> segment(4) != "" ){
	        $current .= $this -> uri -> slash_segment(4, 'leading');
	    }
	    
	    //메뉴 쿼리추가할것 추가.
	    if( $this -> input -> get("mn_type") != ""){
	        $current = $current."?mn_type=".$this -> input -> get("mn_type");
		}

	    return $current;
	}
    
	//현재메뉴
	function get_front_currentURL(){

	    $current = $this -> uri -> slash_segment(1, 'leading');
	    
	    //게시판인경우
	    if( $current == "/board" || $current == "/eduBoard"){
			if( urldecode($this -> uri -> segment(2)) == 'contents'){
				$current .= "/contents";
			}else{
				$current .= "/lists";
			}
	    }else{
	        $current .= urldecode($this -> uri -> slash_segment(2, 'leading'));
	    }

	    //중간 게시판 인경우 확인 /board/lists
	    $middle_path = $current;
	    
	    if( $this -> uri -> segment(3) != "" ){
	        $current .= urldecode($this -> uri -> slash_segment(3, 'leading'));
		}
		
	    if( $middle_path != "/board/lists" && $middle_path != "/board/contents" && $middle_path != "/eduBoard/lists"){
    	    if( $this -> uri -> segment(4) != "" ){
    	        $current .= urldecode($this -> uri -> slash_segment(4, 'leading'));
    	    }
	    }
	    
	    //메뉴 쿼리추가할것 추가.
	    if( $this -> input -> get("mn_type") != ""){
	        $current = $current."?mn_type=".$this -> input -> get("mn_type");
		}
		
	    //메뉴 쿼리추가할것 추가.
	    if( $this -> input -> get("bo_type") != ""){
	        $current = $current."?bo_type=".$this -> input -> get("bo_type");
		}
		
		
	    return $current;
	}
	
	/* 현재 메뉴 정보 */
	function get_front_current_menu($mn_type="FR"){
		$current_url = $this -> get_front_currentURL();
	    $sql = "select mn_link, mn_name, mn_code, mn_idx, mn_name_en, mn_is_view from {$this->table}
                where  mn_link = '{$current_url}' and mn_type = '{$mn_type}' 
				and    length(mn_code) > 2 ";
		$sql .= " ORDER BY mn_code desc " ;//content_location_v 중간 메뉴 출력을 위해 추가 sonoh
	    $query = $this -> db -> query($sql);
	    $reuslt = $query -> result();

	    if( $reuslt == null ){
	        return "";
	    }else{
	        return $reuslt[0];
	    }
	}

	/* visual 메뉴 정보 */
	function get_visual_current_menu($mn_type="FR",$mn_code){
	    $sql = "SELECT * FROM {$this->table} WHERE mn_type='{$mn_type}' AND mn_code = SUBSTR('{$mn_code}',1,2) ";
	    $query = $this -> db -> query($sql);
	    $reuslt = $query -> result();

	    if( $reuslt == null ){
	        return "";
	    }else{
	        return $reuslt[0];
	    }
	}
	
	/* 현재 메뉴 정보 */
	function get_current_menu($field=""){
	    $current_url = $this -> get_currentURL();
		$sql = "select mn_idx, mn_link, mn_name, mn_code, mn_idx, mn_name_en, mn_is_view from {$this->table}  where  
						mn_link = '{$current_url}'  and    
						length(mn_code) > 2 ";
	    $query = $this -> db -> query($sql);
		$reuslt = $query -> result();

	    if( $reuslt == null ){
	        return "";
	    }else{
	        return $reuslt[0];
	    }
	}
	
	/* 메뉴 정보  */
	function get_menu_info($mn_type, $mn_code){
	    $sql = "select mn_name, mn_link, mn_name_en, mn_code, mn_type, mn_is_view from {$this->table} where mn_type = '{$mn_type}' and mn_code = '{$mn_code}' ";
	    $query = $this -> db -> query($sql);

	    $result = $query -> row();
	    return $result;
	}

	/* 메뉴 정보 idx  */
	function get_menu_idx($mn_idx){
	    $sql = "SELECT * FROM {$this->table} WHERE mn_idx = '{$mn_idx}'  ";
	    $query = $this -> db -> query($sql);

	    $result = $query -> row();
	    return $result;
	}
	
	/* 메뉴레벨  */
	function get_menu_level($mn_type, $mn_code){
	   $sql = "select mn_level from {$this->table} where mn_type = '{$mn_type}' and mn_code = '{$mn_code}' ";
	   $query = $this -> db -> query($sql);
	   $result = $query -> result();
	   return $result -> mn_level;
	}

	/* 메뉴 리스트 */
	function menu_list($mn_type){
		$sql = "  SELECT * FROM {$this->table} 
                  WHERE mn_type = '{$mn_type}' and length(mn_code) = 2 ";
        $sql .= " ORDER BY mn_code asc " ;
		$query = $this -> db -> query($sql);
		$result = $query -> result();
		return $result;
	}

	/* 서브메뉴 리스트 */
	function menu_sub_list($mn_type, $mn_code){
		$sql = " SELECT * FROM {$this->table} 
				 WHERE mn_type = '{$mn_type}' and ";
		$sql .=" left(mn_code, 2) = left('{$mn_code}',2) and length(mn_code) > 2";
        $sql .=" ORDER BY mn_code asc " ;
		$query = $this -> db -> query($sql);
		$result = $query -> result();
		//var_dump($sql);
		return $result;
	}
	
	/* 코드에 해당하는 서브메뉴 리스트 */
	function menu_code_sub_list($mn_type, $mn_code){
		$sql = "SELECT * FROM {$this->table}  WHERE mn_type = '{$mn_type}' and left(mn_code, LENGTH({$mn_code})) = LEFT('{$mn_code}', LENGTH({$mn_code})) and length(mn_code) = LENGTH('{$mn_code}')+2
		ORDER BY mn_code asc;
		" ;
		$query = $this -> db -> query($sql);
		$result = $query -> result();

		return $result;
	}

	/* 모든메뉴 */
	function all_menu_list($mn_type){
	    $sql = "SELECT *, concat(mn_type, mn_code) as mn_total FROM ".$this->table."
                WHERE mn_type = '".$mn_type."' 
                ORDER BY mn_code asc " ;
	    $query = $this -> db -> query($sql);
	    $result = $query -> result();
		return $result;
	}

	
	/* 게시물 리스트 업데이트 */
	function menu_list_update($data){
	    //변경되었는가 판단.
	    $sql = "SELECT mn_idx, mn_code, mn_type FROM {$this->table} WHERE mn_idx = {$data['mn_idx']}";
	    $menu_result = $this->db->query($sql)->row();
	    
	    if( $menu_result->mn_code != $data['mn_code'] ){
    		//코드변경
    		$sql = "UPDATE {$this->table} SET
                    mn_code = '{$data['mn_code']}'
                    WHERE mn_idx = {$data['mn_idx']} ";
    		$this->db->query($sql);
    		return $menu_result;
	    }
	}

	/* 게시물 리스트 */
	function menu_code_list( $mn_type, $mn_code ='', $mb_id=''){
	    
	    //아무값도 없는 경우 에러가 뜨기때문에 임의값으로 0을 넣어줌.
	    $mn_idx = "0";
	    // 슈퍼관리자가 아닌경우만 체크
	    if( $mb_id != "" && $mb_id != $this-> config-> cf_admin ){
    	    $sql = "SELECT mn_idxs FROM auth WHERE mb_id = '{$mb_id}' ";
    	    $query = $this -> db -> query($sql);
    	    $result = $query -> row();
    	    if( $result != null ){
    	        $mn_idx = $result->mn_idxs;
    	    }
	    }
		$mn_length = strlen($mn_code);
		$sql = "SELECT * FROM {$this->table} 
                WHERE mn_type = '{$mn_type}' and 
                left(mn_code, {$mn_length}) = '{$mn_code}' and 
                length(mn_code) > ".$mn_length." ";
		if( $mb_id != "" && $mb_id != $this-> config-> cf_admin ){
		    $sql .= "and mn_idx in ({$mn_idx}) ";
		}
		$sql .= " ORDER BY mn_code ASC " ;
		$query = $this -> db -> query($sql);
		$result = $query -> result();

		return $result;
	}

	/* admin 모든메뉴 */
	function admin_all_menu_list($mn_type, $mb_id){
		$result = null;
		$menu_list = [];

		//최고관리자인경우
		$sql = "SELECT *, concat(mn_type, mn_code) as mn_total FROM {$this->table}
		WHERE mn_type = '{$mn_type}' 
		ORDER BY mn_code asc " ;
		$query = $this -> db -> query($sql);
		$result = $query -> result();

		//최고관리자가 아닌경우
		if( $mb_id != "" && $mb_id != $this-> config-> cf_admin ){
			$sql = "SELECT mn_idxs FROM auth WHERE mb_id='{$mb_id}'";
			$query = $this -> db -> query($sql);
			$auth_result = $query -> row() -> mn_idxs;
			$auth_list = explode(",", $auth_result);
			
			$i = 0;
			foreach($result as $menu){
				if( strlen($menu -> mn_code) < 3 ){
					$menu_list[$i] = $menu;
				}else if(strlen($menu -> mn_code)  >= 4 && array_search($menu->mn_idx, $auth_list ) > -1){
					$menu_list[$i] = $menu;
				}
				$i++;
			}
			
		}else if($mb_id == $this-> config-> cf_admin){
			$menu_list = $result;
		}
	    return $menu_list;
	}

	/* 메뉴 삭제 */
	function menu_delete($mn_idx){
	    $sql = "DELETE FROM {$this->table} WHERE mn_idx={$mn_idx}";
	    log_message("debug", $sql);
		$query = $this -> db -> query($sql);
		return $query;
	}

	/* 메뉴 뷰 */
	function menu_view($mn_type, $mn_idx){
		$sql = "SELECT * FROM ".$this->table." WHERE mn_idx = {$mn_idx} AND mn_type ='{$mn_type}'";
		$query = $this -> db -> query($sql);
		$result = $query -> row();
		return $result;
	}

	/* 메뉴 입력 */
	function menu_insert($data){
		$insert_data = array(
		    'mn_type' => $data['mn_type'],
			'mn_name' => $data['mn_name'],
			'mn_name_en' => $data['mn_name_en'],
			'mn_link' => $data['mn_link'],
			'mn_code' => $data['mn_code'],
			'mn_icon' => $data['mn_icon'],
			'mn_is_view' => $data['mn_is_view'],
			'mn_is_alert' => $data['mn_is_alert']
		);
		//새글입력
		$result = $this->db->insert($this->table, $insert_data);
		return $result;
	}
	
	/* 메뉴 코드 */
	function menu_next_code($mn_type, $mn_code){
		//대메뉴인경우
		//대메뉴인경우 20210111 sonoh query 수정count(*)+1 as cnt-> max(left(mn_code, 1))+1 as cnt
	    if( strlen($mn_code) < 2 ){
			$sql = "SELECT count(*)+1 as cnt FROM ".$this->table." 
                    WHERE length(mn_code) = 2 and mn_type = '{$mn_type}' ";
			$query = $this -> db -> query($sql);
			$result = $query -> row();
			//log_message("debug", $sql);
			return $result->cnt*10;
		}else{
		//대메뉴 밑 하위메뉴인 경우
		    $code_length = strlen($mn_code);
		    $sql = "SELECT count(*)+1 as cnt FROM {$this->table} 
                    WHERE mn_type = '{$mn_type}' 
                    and  length(mn_code) > length('{$mn_code}') 
                    and  left(mn_code, {$code_length}) = '{$mn_code}' ";
		    
			$query = $this -> db -> query($sql);
			$result = $query -> row();
			//log_message("debug", $sql);
			return "".$mn_code.($result->cnt*10);
		}
	}

	/* 메뉴 수정 */
	function menu_update($data){
		$update_data = array(
			'mn_name' => $data['mn_name'],
			'mn_name_en' => $data['mn_name_en'],
			'mn_link' => $data['mn_link'],
			'mn_code' => $data['mn_code'],
			'mn_icon' => $data['mn_icon'],
		    'mn_level' => $data['mn_level'],
			'mn_is_view' => $data['mn_is_view'],
			'mn_is_alert' => $data['mn_is_alert']
		);
		$where = array(
			'mn_idx' => $data['mn_idx']
		);
		$result = $this->db->update($this->table, $update_data, $where);
		return $result;
	}

	/* End of file menu_m.php */
	/* Location : ./application/models/config/menu_m.php */
}
?>