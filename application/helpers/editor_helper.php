<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
     *  $name : textarea name, id
     *  $content : 값
     * */
	function create_editor($name, $content=''){
		$CI = &get_instance();
		echo "<textarea name='".$name."' id='".$name."' rows='10' cols='80'>".$content."</textarea>".PHP_EOL;

		echo "<script type='text/javascript'>".PHP_EOL;
		echo "$(document).ready(function(){".PHP_EOL;
		echo "var editor = null;".PHP_EOL;
		echo "editor = CKEDITOR.replace( '".$name."' , {".PHP_EOL;
		echo "		height:500, ";
		echo "		filebrowserBrowseUrl: '/uploader/browse', filebrowserUploadUrl: '/uploader/upload'".PHP_EOL;
		echo "});".PHP_EOL;
		echo "editor.on('instanceReady', function(e){
              });".PHP_EOL;
		echo "});".PHP_EOL;
		echo "</script>".PHP_EOL;
	}
	
    /* 폼을 넘길때 꼭 필요. */
	function update_editor($name){
		echo "CKEDITOR.instances.{$name}.updateElement();".PHP_EOL;
	}

	/* 체크 */
	function edit_radio($name, $value, $input=""){
		$is_checked = "";
		if($input != "" && $value == $input ){
			$is_checked = "checked";
		}
		$result = "<label>";
		$result .= "<input type='radio' name='".$name."' value='".$value."' ".$is_checked." />";
		$result .= "<span class='".$value."'></span>";
		$result .= "</label>";

		return $result;
	}
	
	/* 에디터 이미지 얻기 */
	function get_editor_image($content){
	    if( !$content ) return false;
	    $pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
	    preg_match_all($pattern, $content, $matchs);
	    return $matchs;
	}
	
	/* 에디터 이미지 삭제 */
	function delete_editor_image($content){
	    if( !$content ) return false;
	    $matchs = get_editor_image($content);
	    for( $i=0;$i<count($matchs[1]); $i++ ){
	        $imgurl = @parse_url($matchs[1][$i]);
	        $srcfile = $_SERVER['DOCUMENT_ROOT'].$imgurl['path'];
	        if( is_file($srcfile) ){
	           unlink($srcfile);
	        }
	    }
	}
	
?>
