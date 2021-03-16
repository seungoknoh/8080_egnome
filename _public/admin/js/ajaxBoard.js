
/* ajaxBoard.js */
(function($){
	$.boardOptions = {
		url : "",
		col : [],
		col_name : [],
		rows : 10,
		input : [false, false],
		tableClass : ""
	}
	$.fn.ajaxBoard = function( options ){
		//기본값설정
		options = $.extend( null, $.boardOptions, options );
		this.each(function(index){
			var ajaxBoard = new AjaxBoard();
			ajaxBoard.init($(this), options.url, options.col, options.col_name ,options.rows, options.input, options.tableClass);
		});
		return this;
	};
})(jQuery)


function AjaxBoard(){
	this.board = null;
	this.url = null;
	this.jsonData = null;
	this.col_name = null;
	this.col = null;
	this.page = 1;
	this.rows = 0;
	this.input = null;
	this.tableClass = null;
}

AjaxBoard.prototype.init = function( selector, url, col , col_name, rows, input, tableClass ){
	this.board = $(selector);
	this.url = url;
	this.col_name = col_name;
	this.col = col;
	this.rows = rows;
	this.input = input;
	this.tableClass = tableClass;
	this.setListData();
}

AjaxBoard.prototype.initEvent = function(){
	var objThis = this;
	$(".pagging_area").children('a').each(function(){
		$(this).on('click', function(e){
			e.preventDefault();
			objThis.page = $(this).text();
			objThis.setListData();
		});
	});
}

AjaxBoard.prototype.setListData = function(){
	var objThis = this;
	objThis.board.empty();
	$.ajax ({
		url : objThis.url,
		data : {
			"page" : objThis.page,
			"rows" : objThis.rows
		}
	}).done(function(data){
		objThis.board.append( $("<table class='"+objThis.tableClass+"'></table>") );
		objThis.board.children('table').append(objThis.setTableHead(objThis.col_name));
		objThis.board.children('table').append(objThis.setTableBody(data.list));
		if( data.total > objThis.rows ){
		objThis.board.append( objThis.setPage( data.total) );
		}
		objThis.initEvent();
	});
}

AjaxBoard.prototype.setTableHead = function(data){
	var objThis = this;
	var output = "";
	output += "<colgroup>";
	for( var i = 1; i<= data.length; i++ ){
		output +="<col class=col"+i+" />";
	}
	output += "</colgroup>";
	output += "<thead><tr>";
	for(var i =0; i<data.length; i++){
		output += "<th>";
		output += data[i];
		output += "</th>";
	}
	output += "</tr></thead>";
	return output;
}

AjaxBoard.prototype.getObjValue = function(data, colName){
	for(key in data) {
		if( colName == key ){
			return data[key];
		}
	}
}

AjaxBoard.prototype.setPage = function(total){
	var output = "<div class='pagging_area'>";
	for( var i=1; i<= Math.ceil(total/this.rows);i++){
		if( i == this.page ){
			output += "<strong>"+i+"</strong>";
		}else{
			output += "<a href='javascript:;'>"+i+"</a>";
		}
	}
	output += "</div>";

	return output;
}

AjaxBoard.prototype.setTableBody = function(data){
	var objThis = this;
	//data = data.sort(function(a, b){return a.cnt - b.cnt });
	var output = "";
	output += "<tbody>";
	for( var i = 0; i < data.length; i++ ){
		output += "<tr>";
		for(var j=0; j< objThis.col.length; j++) {
			var value = objThis.getObjValue(data[i], objThis.col[j]);
			if( objThis.input[j] == true ){
				output += "<td class='left'>"+"<input type='text' id='col"+i+"' class='form-control' name='"+objThis.col[j]+"[]' value='"+value+"' /></td>";
			}else{
				output += "<td>";
				output += "<input type='hidden' class='form-control' name='"+objThis.col[j]+"[]' value='"+value+"' />"+ "<label for='col"+i+"' class='control-label'>"+value+"</label>";
				output += "</td>";
			}
		}
		output += "</tr>";
	}

	output += "</tbody>";
	return output;
}