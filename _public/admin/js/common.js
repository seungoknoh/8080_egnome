/*
	Data 처리 javascript
	common.js
*/
var cookie = {
	setCookie : function(cookie_name, value, days){
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + days);
		// 설정 일수만큼 현재시간에 만료값으로 지정
		var cookie_value = escape(value) + ((days == null) ? '' : '; expires=' + exdate.toUTCString());
		document.cookie = cookie_name + '=' + cookie_value;	
	},
	getCookie : function(cookie_name){
		var x, y;
		var val = document.cookie.split(';');

		for (var i = 0; i < val.length; i++) {
			x = val[i].substr(0, val[i].indexOf('='));
			y = val[i].substr(val[i].indexOf('=') + 1);
			x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤의 공백 제거하기
			if (x == cookie_name) {
				return unescape(y); // unescape로 디코딩 후 값 리턴
			}
		}
	}
}

//카테고리
function Category(){
	this.list = null;
	this.url = null;
	this.name = "bc_name";
}
Category.prototype.init = function(url, list){
	var objThis = this;
	objThis.url = url;
	objThis.list = list;
	
	objThis.list.sortable({
		axis: "y",
		cancel: "a, button, label, input"
	});
}
Category.prototype.add = function(input){
	var objThis = this;
	//인풋박스
	var input = input;
	if( input.val() == "" ){
		alert("입력해주세요");
		input.focus();
		return;
	}
	
	//추가 리스트
	var list = $("<li></li>").addClass('list-group-item form-inline list-item');
	//추가 카테고리
	var cate_input = "<input class='form-control' value='"+input.val()+"' name='"+objThis.name+"[]' />";
	
	//삭제버튼
	var btn_del = $("<a></a>").addClass('badge').text('삭제');
	btn_del.on("click", function(){
		$(this).parent().parent('li').remove();
	});
	var group = $("<div></div>").addClass("form-group");
	group.append(cate_input);
	group.append(btn_del);
	list.append(group);

	objThis.list.append( list );
	input.val('');
}


var Common = {
	//체크박스 전체체크
	// @obj : element : element
	// @name : string : 체크 할 input name 값
	fncCheckAll : function(obj, name){
		var input = $('input[name="'+name+'"]');
		if($(obj).is(':checked')){
			input.prop( "checked", true );
		}else{
			input.prop( "checked", false );
		}
	
	},
	fncCheckValueGetArray : function(name){
		var input = $('input[name="'+name+'"]:checked');
		var arr = [];
		input.each(function(index){
			arr[index] = $(this).val();
		});
		return arr;
	},
	fncChangeZeroOne : function(value){
		return Math.abs(parseInt(value) - 1);
	},
	funGetValueArray : function(selector){
		var arr = [];
		selector.each(function(){
			arr.push( $(this).val() );
		});
		return arr;
	}
}

//ajax 로딩
$(document).ajaxStart(function(){
	$(".loading").css("display", "block");
});

$(document).ajaxComplete(function(){
	$(".loading").css("display", "none");
});	

$(function(){
	setDatePicker($(".datePicker"));
});


//정규식
function chkRegEpx(str, value){
	var regexp = "/"+str+"/g";
	regexp.trim();
	console.log(regexp+"/"+value+"/" +regexp.test(value) );
	return ;
}

//datepicker 설정
function setDatePicker(el){
	el.datepicker({
		dateFormat : 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		monthNamesShort : [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ],
		dayNamesShort: [ "일", "월", "화", "수", "목", "금", "토" ],
		gotoCurrent : true,
		showButtonPanel: true
	});
}

//파일추가
function addFile(file_max_count){
	var file_list = $("#file_list");
	var file_count = $("#file_count");
	var count = file_count.val();
	var output = "";
	count++;
	if( file_max_count >= count ){
		output += '<li><span class="num">'+count+'.</span>';
		output += '<input type="file" name="file'+count+'" value="File Search" onChange="fn_previewImg(this, \'preImg'+count+'\')" />';
		output +='<span class="prevImg"><img id="preImg'+count+'" /></span></li>';
		file_count.val(count);
		file_list.append(output);
	}else{
		alert("더이상 추가할 수 없습니다.");
	}
}

function fn_previewImg( input, preImg ) {
   // param : input - 파일업로드 input 객체 change 이벤트에서 this 로 받아온다
   // preImg - 미리보기 이미지를 보여줄 img 태그  ID 
	if ($(input).val()!="") {
		//확장자 확인
		var ext = $(input).val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) != -1) {
			if ( window.FileReader ) {
				 /*IE 9 이상에서는 FileReader  이용*/
				var reader = new FileReader();
					reader.onload = function (e) {
						$('#'+preImg).attr('src', e.target.result); 
					};
					reader.readAsDataURL(input.files[0]);
					return input.files[0].name;  // 파일명 return
			} else {
				/* IE8 전용 이미지 미리보기 */ 
				input.select();
				var src = document.selection.createRange().text;
				$('#'+preImg).attr('src', src);  
				var n = src.substring(src.lastIndexOf('\\') + 1);
				return n; // 파일명 return
			}
			return;
		}
	}
}

//콤마찍기
function comma(str) {
	str = String(str);
	return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

//ie 버전체크
function getInternetExplorerVersion() {
	var rv = -1; // Return value assumes failure.    
	if (navigator.appName == 'Microsoft Internet Explorer') {
		var ua = navigator.userAgent;
		var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null)            
		rv = parseFloat(RegExp.$1);    
	}
	return rv; 
}

//모바일체크
function isMobile(){
	var mobileInfo = new Array('Android', 'iPhone', 'iPod', 'BlackBerry', 'Windows CE', 'SAMSUNG', 'LG', 'MOT', 'SonyEricsson');
	for (var info in mobileInfo){
		if (navigator.userAgent.match(mobileInfo[info]) != null){
			// 모바일 수행
			return true;
		}else{
			return false;
		}
	}
}