<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//썸네일 삭제
function delete_thumbnail($content){
    global $config;
    if( !$content ) return ;
    $matchs = get_editor_image($content);
    
    if( !$matchs ) return;
    $matchs = get_editor_image($content);
    
    for( $i=0;$i<count($matchs[1]); $i++ ){
        $imgurl = @parse_url($matchs[1][$i]);
        $srcfile = $_SERVER['DOCUMENT_ROOT'].$imgurl['path']; 
        $filename = preg_replace("/\.[^\.]+$/i", "", $srcfile);
        $thumb_files = glob($filename."_thumb*");
        foreach($thumb_files as $file){
            @unlink($file);
        }
    }
}

//파일 썸네일 삭제
function delete_file_thumbnail($op_table, $_filename){
    $delete_dir = UPLOAD_FILE_DIR.'/'.$op_table.'/';
    $filename = preg_replace("/\.[^\.]+$/i", "", $delete_dir.$_filename);
    $thumb_files = glob($filename."_thumb*");
    //log_message("debug", "DELETE _FILE THUMB TEST -".$filename.implode(",",$thumb_files));
    foreach($thumb_files as $thumb){
        @unlink($thumb);
    }
}

//파일안 썸네일파악
function is_file_thumbnail($file_list, $thumb_width=300, $thumb_height=400, $alt='', $no_image = true){
    $thumbFile = null;
    $thumb = array();
    foreach($file_list as $file){
        if( strpos($file->bf_type, "image") > -1 ){
            $thumbFile = $file;
            break;
        }
    }
    if($thumbFile != null){
        $CI = &get_instance();
        if(!isset($CI->image_lib)){
            $CI -> load -> library("image_lib");
        }   
        $config['width'] = $thumb_width;
        $config['height'] = $thumb_height;
        $config['image_library'] = 'gd2';
        $config['create_thumb'] = TRUE;
        $config['thumb_marker'] = "_thumb{$config['width']}x{$config['height']}";
        $filePath = UPLOAD_FILE_DIR.'/'.$thumbFile->op_table."/";
        $config['source_image'] = $filePath.$thumbFile->bf_filename;
        $filename = preg_replace("/\.[^\.]+$/i", "", basename($config['source_image']));

        $files = glob($filePath.$filename."{$config['thumb_marker']}*");
    
        //썸네일이 없는경우 만들기
        if( count($files) <= 0 ){
            $CI->image_lib->initialize($config);
            //썸네일생성
            if( ! $CI->image_lib->resize() ){
                echo $CI->image_lib->display_errors();
            }
            $path = pathinfo($config['source_image']);
            $thumbnail = $filePath.$path['filename']."{$config['thumb_marker']}.".$path['extension'];
            $thumbnail = str_replace(UPLOAD_FILE_DIR, UPLOAD_FILE_URL, $thumbnail);
            $CI->image_lib->clear();
        }else{
            $thumbnail = str_replace(UPLOAD_FILE_DIR, UPLOAD_FILE_URL, $files[0]);
        }
        $thumb_alt = $alt."썸네일";
        $thumb['is_thumbnail'] = true;
        $thumb['src'] = "{$thumbnail}";
        $thumb['alt'] = "{$thumb_alt}";
        $thumb['height'] = $thumb_height;
        $thumb['width'] = $thumb_width;
    }else{
        if( $no_image == true){
            $thumb['is_thumbnail'] = false;
            $thumb['src'] = ADM_IMG_URL."/no_image.png";
            $thumb['alt'] = "등록된 썸네일이 존재하지 않습니다.";
            $thumb['height'] = $thumb_height;
            $thumb['width'] = $thumb_width;
        }else{
            return null;
        }
    }
    //var_dump($thumb);
    return $thumb;
}

//썸네일 존재여부파악
function is_thumbnail($content, $thumb_width=300, $thumb_height=400, $alt='', $no_image = true){
    $thumb = array();
    $matchs = get_editor_image($content);

    if( !$matchs ) return;

    $CI = &get_instance();
	if(!isset($CI->image_lib)){
		$CI -> load -> library("image_lib");
	}
    $config['width'] = $thumb_width;
    $config['height'] = $thumb_height;
    $config['image_library'] = 'gd2';
    $config['create_thumb'] = TRUE;
    $config['thumb_marker'] = "_thumb{$config['width']}x{$config['height']}";
    
    if( isset( $matchs[1][0]) ){
        $img_path = explode("/", "".$matchs[1][0]);

       //업로드한이미지가 아닌경우 썸네일 생성 안함
       if( strpos($matchs[1][0], SITE_URL) < -1 ){
            copy($matchs[1][0], UPLOAD_IMG_DIR."/".$img_path[count($img_path)-1]);
        }

        $source_image = UPLOAD_IMG_DIR.'/'.$img_path[count($img_path)-1];
        $config['source_image'] = $source_image;
        $filename = preg_replace("/\.[^\.]+$/i", "", basename($source_image));
        $files = glob(UPLOAD_IMG_DIR."/".$filename."{$config['thumb_marker']}*");
      
        //썸네일이 없는경우 만들기
        if( count($files) <= 0 ){
            $CI->image_lib->initialize($config);
            //썸네일생성
            if( ! $CI->image_lib->resize() ){
                echo $CI->image_lib->display_errors();
            }
            $path = pathinfo($source_image);
            $thumbnail = UPLOAD_IMG_URL."/".$path['filename']."{$config['thumb_marker']}.".$path['extension'];
            $CI->image_lib->clear();
        }else{
            $thumbnail = str_replace(UPLOAD_IMG_DIR, UPLOAD_IMG_URL, $files[0]);
        }
        $thumb_alt = $alt."썸네일";
        $thumb['is_thumbnail'] = true;
        $thumb['src'] = "{$thumbnail}";
        $thumb['alt'] = "{$thumb_alt}";
        $thumb['height'] = $thumb_height;
        $thumb['width'] = $thumb_width;
    }else{
        if( $no_image == true){
            $thumb['is_thumbnail'] = false;
            $thumb['src'] = ADM_IMG_URL."/no_image.png";
            $thumb['alt'] = "등록된 썸네일이 존재하지 않습니다.";
            $thumb['height'] = $thumb_height;
            $thumb['width'] = $thumb_width;
        }else{
            return null;
        }
    }
    
    return $thumb;
}
?>
