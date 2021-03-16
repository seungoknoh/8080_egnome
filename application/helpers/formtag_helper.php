<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('bt_dropdown_box'))
{
	function ft_dropdown_box($name, $options, $items=array(), $size="10"){
		$html = "<div class=\"SelectBox\" style=\"width:{$size}em\">";
		$html .= "<select name=\"{$name}\" class=\"form-control\">";
		$html .= "<option value=''>선택</option>";
		if ( is_array($options))
		{
			foreach($options as $key => $val)
			{
				if(!$val) continue;
				$html .= "<option value='" . $key ."'";
				if(is_array($items) && in_array($key, $items)) $html .=" selected=\"selected\"";
				else $html .="";
				$html .=">" . $val . "</option>\n";
			}
		}
		$html .="</select>";
		$html .="</div>";
		return $html;
	}
}

if ( ! function_exists('ft_checkbox')){
	function ft_checkbox($name, $array, $items=array()){
		$html ="";
		if ( is_array($array))
		{
			foreach($array as $key => $val)
			{
				if(!$val) continue;
				$html .="<div class='Checkbox'>";
				$html .= "<label class='Checkbox__label'>";				
				$html .= "<input type='checkbox' name='{$name}'' value='{$key}'";
				if(is_array($items) && in_array($key, $items)){
					$html .=" checked=\"checked\"";
				} else { 
					$html .=""; 
				};
				$html .=" /><span class='Checkbox__text'>{$val}</span>";
				$html .="</label></div>";
			}
		}
		
		return $html;	
	}
}

if ( ! function_exists('ft_set_value')){
	function ft_set_value($_array=null, $_key, $is_date=false){
		$result = "";
		if( $_array != null ){
			if( property_exists($_array, $_key) ){
				$result = $_array->$_key;
				if( $is_date ){
					$result = substr($_array->$_key, 0, 10);
				}
			}
		}else{
			$result = set_value($_key);
		}
		return $result;
	}
}
?>