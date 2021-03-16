<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploader extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this -> load -> helper( array('alert', 'uploader'));
		$this -> load -> model('board/file_m');
	}
	public function index(){
		$this -> upload();
	}

	public function upload(){
		if ($_FILES["upload"]["size"] > 0 ){

			// 현재시간 추출
			$current_time = time();
			$time_info	 = getdate($current_time);
			$date_filedir	 = date("YmdHis");

			//오리지널 파일 이름.확장자
			$ext = substr(strrchr($_FILES["upload"]["name"],"."),1);
			$ext = strtolower($ext);
			$savefilename = $date_filedir."_".str_replace(" ", "_", $_FILES["upload"]["name"]);

			$uploadpath = $_SERVER['DOCUMENT_ROOT']."/upload/images";
			$uploadsrc = $_SERVER['HTTP_HOST']."/upload/images/";

			$http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
			$tmp_file = $_FILES['upload']['tmp_name'];

			if (is_uploaded_file($tmp_file)) {
				//php 파일업로드하는 부분
				if($ext=="jpg" or $ext=="gif" or $ext =="png"){
					$image_path = $uploadpath."/".iconv("UTF-8","EUC-KR",$savefilename);
					if(move_uploaded_file($tmp_file, $image_path)){
						$uploadfile = $savefilename;

						$config['image_library'] = 'gd2';
						$config['source_image'] = $image_path;
						$config['quality']         = '80%';
						
						$this->load->library('image_lib', $config);
						
						$this->image_lib->resize();
					}
					echo '{"filename" : "'.$savefilename.'", "uploaded" : 1, "url":"'.$http.$uploadsrc.$uploadfile.'"}';
				}else{
					echo '{"filename" : "'.$savefilename.'", "uploaded" : 0, "url":""}';
				}
			}else{
				echo '{"filename" : "'.$savefilename.'", "uploaded" : 0, "url":""}';
			}
		}else{
			exit;
		}
	}

	public function browse(){
		echo "browse";
	}
	
	public function fileList(){
	    $bo_idx = $this -> uri -> segment(3);
	    
	    $file_data = array(
	        'bo_idx' => $bo_idx
	    );
	    $fileList = $this -> file_m -> list("", $file_data);
	    echo json_encode($fileList);
	}
	
	public function fileUpload(){
	    $bo_idx = $this -> input -> post("bo_idx");
	    $op_table = $this -> input -> post("op_table");
	    $bf_delete = $this->input ->post("bf_delete");
	    
	    makeDir($op_table);
	    
	    // 가변 파일 업로드
	    $file_upload_msg = '';
	    $upload = array();
	    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

	    for( $i=0;$i<count($bf_delete);$i++){
	        //삭제체크시 파일삭제
	        if( isset( $bf_delete[$i]) ){
	            $upload[$i]['del_check'] = true;

	            $file = $this -> file_m -> get_file_idx($bf_delete[$i]);
	            log_message("debug", "//삭제체크시 파일삭제".UPLOAD_FILE_DIR.'/'.$file->op_table.'/'.$file->bf_filename);
	            
	            //파일삭제
	            @unlink(UPLOAD_FILE_DIR.'/'.$file->op_table.'/'.$file->bf_filename);
	            
	            //DB삭제
	            $file = $this -> file_m -> delete_idx($bf_delete[$i]);
	        }else{
	            log_message("debug", "//삭제체크안함");
	            $upload[$i]['del_check'] = false;
	        }
	    }
	
	    for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
	        $upload[$i]['file']     = '';
	        $upload[$i]['source']   = '';
	        $upload[$i]['filesize'] = 0;
	        $upload[$i]['image']    = array();
	        $upload[$i]['image'][0] = '';
	        $upload[$i]['image'][1] = '';
	        $upload[$i]['image'][2] = '';
	        
	        $file_data = array(
	            'op_table' => $op_table,
	            'bo_idx' => $bo_idx,
	            'bf_num' => $i
	        );
	        
	        $tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
	        $filesize  = $_FILES['bf_file']['size'][$i];
	        $filename  = $_FILES['bf_file']['name'][$i];
	        $filename  = get_safe_filename($filename);
	        
	        if (is_uploaded_file($tmp_file)) {
	            $timg = @getimagesize($tmp_file);
	            $upload[$i]['image'] = $timg;
	            
	            // 프로그램 원래 파일명
	            $upload[$i]['source'] = $filename;
	            $upload[$i]['filesize'] = $filesize;
	            
	            // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
	            $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);
	            
	            shuffle($chars_array);
	            $shuffle = implode('', $chars_array);
	            
	            // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
	            $upload[$i]['file'] = time().'_'.substr($shuffle,0,8).'_'.trim($filename);
	            $dest_file = UPLOAD_FILE_DIR."/{$op_table}/".$upload[$i]['file'];
	            
	            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
	            $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);
	            
	            // 올라간 파일의 퍼미션을 변경합니다.
	            chmod($dest_file, 0644);
	            
	            //DB저장
	            $bf_num = $this -> file_m -> get_file_num($file_data);
	            $upload_data = array(
	                'op_table' => $op_table,
	                'bo_idx' => $bo_idx,
	                'bf_num' => $bf_num,
	                'bf_filename' => $upload[$i]['file'],
	                'bf_source' => $upload[$i]['source'],
	                'bf_type' => $upload[$i]['image']['2'],
	                'bf_filesize' => $upload[$i]['filesize']
	            );
	            $this -> file_m -> insert($upload_data);
	        }
	    }
	}

}
