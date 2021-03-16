<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

// 뷰 : api/board/lists/idx/305?op_table=gallery
// 목록 : api/board/lists?op_table=gallery

class Board extends REST_Controller {
    public $op_table = "";
    public $page = 0;
    public $option = null;
    public $query = ""; //쿼리스트링
    public $stx = ""; //검색어
    public $sfl = ""; // 검색필드
    public $sst = ""; //정렬필드
    public $sod = ""; //정렬순
    public $sca = ""; //카테고리
    public $total = ""; //전체 리스트
    public $category_list = "";
    
    function __construct(){
        // Construct the parent class
        parent::__construct();
        
        $this -> load -> model("board/board_m");
        $this -> load -> model("board/file_m");
        $this -> load -> model("board/board_option_m");
        $this -> load -> model("board/board_category_m");
        
        //이미지 썸네일
        $this -> load -> library("image_lib");
        $this -> load -> helper( array('alert','editor','thumbnail')); 
        $this -> query = "?page={$this ->page}&sca={$this->sca}&stx={$this -> stx}&sfl={$this -> sfl}&sst={$this->sst}&sod={$this->sod}";
        
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    
    public function lists_get(){
        $page = $this -> input -> get('page') != null ? $this -> input -> get('page') : 1; //페이지
        $this -> sfl = $this -> input ->get("sfl", FALSE);
        $this -> stx = $this -> input ->get("stx", FALSE);
        $this -> sst = $this-> input ->get("sst", FALSE);
        $this -> sod = $this-> input ->get("sod", FALSE);
        $this -> sca = $this -> input -> get("sca", False);
        $this -> op_table = $this -> input -> get('op_table');

        //카테고리리스트
        $this -> category_list = $this -> board_category_m -> list($this -> op_table);
        //옵션
        $this -> option = $this -> board_option_m -> get_board_option($this -> op_table);

        $limit = 10;
        if( $page > 1 ){
            $start = (($page/$limit)) * $limit;
        }else{
            $start = ($page - 1) * $limit;
        }
        $board_list = $this-> board_m -> board_list( '', $this->op_table, $start, $limit, $this -> stx, $this -> sfl, $this -> sst, $this->sod , $this->sca );

        //갤러리 썸네일
        if( $this -> option -> op_is_gallery == 1){
            $config['width'] = $this->option->op_thumb_width;
            $config['height'] = $this->option->op_thumb_height;
            $config['image_library'] = 'gd2';
            $config['create_thumb'] = TRUE;
            $config['thumb_marker'] = "_thumb{$config['width']}x{$config['height']}";
        }
        
        $lists = array();
        foreach ($board_list as $ls){
            $ls->op_table = $this -> op_table;

            //갤러리 썸네일
            if( $this -> option -> op_is_gallery == 1){
                $matchs = get_editor_image($ls->bo_content);
                
                if( isset( $matchs[1][0]) ){
                    $img_path = explode("/", "".$matchs[1][0]);
                    
                    $source_image = UPLOAD_IMG_DIR.'/'.$img_path[count($img_path)-1];
                    $config['source_image'] = $source_image;
                    
                    $filename = preg_replace("/\.[^\.]+$/i", "", basename($source_image));
                    $files = glob(UPLOAD_IMG_DIR."/".$filename."{$config['thumb_marker']}*");
                    
                    //썸네일이 없는경우 만들기
                    if( count($files) <= 0 ){
                        $this->image_lib->initialize($config);
                        if( ! $this->image_lib->resize() ){
                            echo $this->image_lib->display_errors();
                        }
                        $path = pathinfo($source_image);
                        $thumbnail = UPLOAD_IMG_URL."/".$path['filename']."{$config['thumb_marker']}.".$path['extension'];
                        $this->image_lib->clear();
                    }else{
                        $thumbnail = str_replace(UPLOAD_IMG_DIR, UPLOAD_IMG_URL, $files[0]);
                    }
                    $alt = $ls->bo_subject." 썸네일";
                    $ls->thumbnail = "<span class='image'><img src='{$thumbnail}' alt='{$alt}' style='height:{$config['height']}px;width:100%;display:block' /></span>";
                }else{
                    $ls->thumbnail = "<span class='image' style='height:{$config['height']}px;width:100%;display:block' ><span class='no_image'>No Image</span></span>";
                }
            }
            array_push($lists, $ls);
        }
        
        $bo_idx = $this->get('idx');

        if ($bo_idx == NULL){
            if ($lists){
                $this->response($lists, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        
        if ($bo_idx <= 0){
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        
        $board = NULL;
        if (!empty($lists)){
            foreach ($lists as $ls){
                if (isset($ls->bo_idx) && $ls->bo_idx === $bo_idx){
                    $board = $ls;
                }
            }
        }

        if (!empty($board)){
            $this->set_response($board, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

}
