/*
	ux 팝업
*/
$(document).ready(function(){
	uiPopup.init();
});
var uiPopup = (function(){
	return {
		init : function(){
			var objThis = this;
			$.ajax({
				type : "GET",
				dataType : "json",
				url : "/main/popup"
			}).done(function(data){
				for( var i =0; i< data.length;i++ ){
					objThis.setting(data[i]);
				}
			});
		},
		setting : function( data ){
			var popup_id = "popup"+data.po_idx;
			if( uiPopup.getCookie(popup_id) == 'end' ) return;
			if( $("#"+popup_id).size() > 0 ) return false;
			var layer_popup = $("<div></div>").addClass("layer_popup").attr("id", popup_id).draggable();
			var popup_content = $("<div></div>").addClass("popup_content").html(data.po_content);
			popup_content.css({
				"width" : data.po_width+"px",
				"height" : data.po_height+"px",
				"overflow" : "hidden"
			});
			if( data.po_link != '' ){
				popup_content.on("click", function(){
					if( data.po_is_target == 1 ){
						window.open(data.po_link, "_blank");
					}else{
						window.location.href = data.po_link;
					}
				});
			}

			/*//var btn_today = $("<a href='javascript:;'></a>").text("Invisible for "+data.po_hour+"hours");*/
			var btn_today = $("<a href='javascript:;'></a>").text(data.data.po_hour+"시간 동안 보이지 않음");
			btn_today.css({
				"display" : "block",
				"float" : "left",
				"line-height" : "25px",
				"color" : "#fff",
				"padding-left" : "5px"
			});
			btn_today.on("click", function(){
				layer_popup.remove();
				uiPopup.setCookie( popup_id, "end" , 1);
			});
			var btn_close = $("<a href='javascript:;'></a>").text("Close");
			btn_close.on("click", function(){
				layer_popup.remove();
			});
			btn_close.css({
				"display" : "block",
				"float" : "right",
				"line-height" : "25px",
				"color" : "#fff",
				"padding-right" : "5px"
			});
			var popup_footer = $("<div></div>").addClass("popup_footer");
			popup_footer.css({
				"overflow" : "hidden",
				"padding-top" : "5px",
				"background" : data.po_color
			});
			popup_footer.append(btn_today);
			popup_footer.append(btn_close);

			layer_popup.css({
				"position":"fixed",
				"z-index" : 1500,
				"background" : "#fff",
				"border" : "5px solid "+data.po_color,
				"left":data.po_left+"px",
				"top":data.po_top+"px",
				"box-shadow" :"1px 1px 10px #555"
			});
			if( data.po_is_center == '1'){
				layer_popup.css({
					"left" : "50%",
					"top" : "50%",
					"margin-left" : -(parseInt(data.po_width)/2-5)+"px" ,
					"margin-top" : -(parseInt(data.po_height)/2)+"px",
				});
			}
			layer_popup.append(popup_content);
			layer_popup.append(popup_footer)
			$('body').append(layer_popup);
		},
		getCookie : function(name){
			var cookieName = name + "=";
			var x = 0;
			while ( x <= document.cookie.length ) { 
			  var y = (x+cookieName.length); 
			  if ( document.cookie.substring( x, y ) == cookieName) { 
				 if ((lastChrCookie=document.cookie.indexOf(";", y)) == -1) 
					lastChrCookie = document.cookie.length;
				 return decodeURI(document.cookie.substring(y, lastChrCookie));
			  }
			  x = document.cookie.indexOf(" ", x ) + 1; 
			  if ( x == 0 )
				 break; 
			  } 
			return "";
		},
		setCookie : function(cname, value, expire){
			var todayValue = new Date();
			// 오늘 날짜를 변수에 저장
			todayValue.setDate(todayValue.getDate() + expire);
			document.cookie = cname + "=" + encodeURI(value) + "; expires=" + todayValue.toGMTString() + "; path=/;";
		}
	}
})();