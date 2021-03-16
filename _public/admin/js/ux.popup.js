/*
	ux 팝업
*/

var popup = {
	baseurl : "/backS1te/popup",
	init : function(){
	
	},
	preview : function( po_idx ){
		var thisObj = this;
		$.ajax({
			type : "GET",
			dataType : "json",
			url : thisObj.baseurl+"/json_view/"+po_idx
		}).done(function(data){
			var popup_id = "popup"+data.data.po_idx;
			if( $("#"+popup_id).size() > 0 ) return false;
			var layer_popup = $("<div></div>").addClass("layer_popup").attr("id", popup_id).draggable();
			var popup_content = $("<div></div>").addClass("popup_content").html(data.data.po_content);
			popup_content.css({
				"width" : data.data.po_width+"px",
				"height" : data.data.po_height+"px",
				"overflow" : "hidden"
			});
			if( data.data.po_link != '' ){
				popup_content.on("click", function(){
					if( data.data.po_is_target == 1 ){
						window.open(data.data.po_link, "_blank");
					}else{
						window.location.href = data.data.po_link;
					}
				});
			}

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
			});
			var btn_close = $("<a href='javascript:;'></a>").text("닫기");
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
				"background" : data.data.po_color
			});
			popup_footer.append(btn_today);
			popup_footer.append(btn_close);

			layer_popup.css({
				"position":"fixed",
				"z-index" : 1500,
				"background" : "#fff",
				"border" : "5px solid "+data.data.po_color,
				"left":data.data.po_left+"px",
				"top":data.data.po_top+"px",
				"box-shadow" :"1px 1px 10px #555"
			});
			layer_popup.append(popup_content);
			layer_popup.append(popup_footer)
			$('body').append(layer_popup);
		});
	}
}