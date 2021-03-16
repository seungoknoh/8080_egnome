
/* ux.js */

//초기실행
$(document).ready(function(){
	Menu.init();
	
	//달력 jquery ui (한글화)
	$(".datepicker").datepicker({
		dateFormat : 'yy-mm-dd',
		nextText: '다음 달', // next 아이콘의 툴팁.
		prevText: '이전 달', // prev 아이콘의 툴팁.
		changeMonth: true,
		changeYear: true,
		dayNamesShort: [ "일", "월", "화", "수", "목", "금", "토" ],
		dayNamesMin: [ "일", "월", "화", "수", "목", "금", "토" ],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		monthNames: [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ],
		gotoCurrent : true,
		showButtonPanel: true
	});

});

var Menu = {
	element : $("#sidebar > ul"),
	init : function(){

		var objThis = this;
		objThis.element.children('li').each(function(index){
			$(this).children('a').on("click", function(e){
				e.preventDefault();
				if( $(this).parent().children('ul').css('display') == 'none'){
					objThis.open(index);
				}else{
					objThis.close(index);
				}
			});
		});

		$("#btn-admin-menu").on('click', function(e){
			e.preventDefault();
			var container = $(".container-wrap");
			if( container.hasClass('on') ){
				container.removeClass('on');
				$('body').attr("style", "");
			}else{
				container.addClass('on');
				$('body').css("overflow", "hidden");
			}
		});
	},
	open : function(i){
		var objThis = this;
		objThis.element.children('li').eq(i).children('ul').slideDown();
	},
	close : function(i){
		var objThis = this;
		objThis.element.children('li').eq(i).children('ul').slideUp();
	}
}
