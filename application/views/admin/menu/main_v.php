<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li class="active">메뉴관리</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>메뉴관리<small> 메뉴에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<div class="well text-center col-md-12">
			왼쪽 메뉴 목록의 [<span class='glyphicon glyphicon-cog'></span> 버튼]을 클릭하면 상세정보를 볼 수 있습니다. <br />
			왼쪽 메뉴 목록을 드래그하여 순서를 변경한 뒤 반드시 [<span class='glyphicon glyphicon-floppy-disk'></span> 순서변경저장] 버튼을 클릭해주세요.
		</div>
		<div class="row">
			<div class="col-md-5">
				<?php 
					$attr = array('id'=>'frm_list');
					echo form_open('', $attr); 
				?>
				<input type="hidden" name="mn_type" value="<?php echo $mn_type ?>" />
				<ul class="list-group" id="menu_list_content">
					<li class="list-group-item menu-item">등록된 메뉴가 없습니다.</li>
				</ul>
				</form>
			</div>
			<div class="col-md-7" >
				<div id="menu_view_content"></div>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-12 text-right">
			<button type="button" id="btn-save" class="btn-lg btn btn-danger" disabled="disabled" ><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> 순서변경저장</button>
			<button type="button" id="btn-write" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 대메뉴추가</button>
		</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>

<script type="text/javascript">
	$(document).ready(function(){
		Board.init();
	});
	
	var Board = {
		mn_type : "<?php echo $mn_type ?>",
		url : "/backS1te/menu",
		init : function(){
			var thisObj = this;
			this.lists();
			this.updateGET();
			//대메뉴추가
			$("#btn-write").on("click", function(e){
				e.preventDefault();
				thisObj.writeGET();
			});
			//추가
			$("#btn-save").on("click", function(e){
				e.preventDefault();
				thisObj.listsPOST();
			});			
		},
		listsSubMenu : function(mn_code, el){
			var thisObj = this;
			var el = el;
			$.ajax ({
				method : "GET",
				dataType : "json",
				url : thisObj.url +"/json_listSubMenu",
				data : {
					mn_type : thisObj.mn_type,
					mn_code : mn_code
				},
				success : function(data){
					if( data.lists.length == 0 ) return false;
					var mn_list = $("<ul></ul>").addClass("list-group");
					for( var i =0;i<data.lists.length;i++ ){
						var badge = $("<a></a>").attr('href', 'javascript:;').addClass("btn badge").html("<span class='glyphicon glyphicon-cog'></span>");
						if(data.lists[i].mn_code.length == 6){
							var list = $("<li></li>").attr('data-idx',data.lists[i].mn_idx ).addClass("list-group-item menu-item").html("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp<span class='"+data.lists[i].mn_icon+"'></span> "+data.lists[i].mn_name);
						} else {
							var list = $("<li></li>").attr('data-idx',data.lists[i].mn_idx ).addClass("list-group-item menu-item").html("<span class='"+data.lists[i].mn_icon+"'></span> "+data.lists[i].mn_name);
						}
						var mn_code = $("<input />").attr("type", "hidden").attr("name", "mn_code[]").val(data.lists[i].mn_code);
						var mn_idx = $("<input />").attr("type", "hidden").attr("name", "mn_idx[]").val(data.lists[i].mn_idx);

						(function(){
							var mn_idx = data.lists[i].mn_idx;
							badge.on("click", function(){
								thisObj.updateGET(mn_idx);
							});
						})();
						list.append(mn_code);
						list.append(mn_idx);
						list.append(badge);
						mn_list.append(list);
						mn_list.sortable();
					}
					el.append(mn_list);
					mn_list.sortable({
						out : function(event, ui){
							$("#btn-save").removeAttr("disabled");
							$(ui.sender[0]).children('li').each(function(index){
								var value = $(this).children('input[name="mn_code[]"]').val();
								console.log(value);
								var pre_code = value.substr(0, value.length-2);
								var code = ""+pre_code+((index+1)*10);
								$(this).children('input[name="mn_code[]"]').val(code);
								var child = $(this).children('ul').find('input[name="mn_code[]"]');
								if( child.length > 0 ){
									child.each(function(index){
										$(this).val(""+code+((index+1)*10));
									});
								}
							});
						},
						cancel : "a, button"
					});
				}
			});
		},
		listsPOST : function(){
			var thisObj = this;
			var formData = $("#frm_list").serialize();
			$.ajax ({
				method : "POST",
				dataType : "json",
				url : thisObj.url +"/json_listSave",
				data : formData,
				success : function(data){
					alert('순서변경되었습니다.');
					thisObj.lists();
					$("#btn-save").attr('disabled','disabled');
				}
			});
		},
		lists : function(){
			var thisObj = this;
			$.ajax ({
				method : "GET",
				dataType : "json",
				url : thisObj.url +"/json_lists",
				data : {
					"mn_type" : thisObj.mn_type
				},
				success : function(data){
					$("#menu_view_content").empty();
					var mn_list = $("#menu_list_content");
					if( data.lists.length == 0 ) return false;
					mn_list.empty();
					for( var i =0;i<data.lists.length;i++ ){
						var badge = $("<a></a>").attr('href', 'javascript:;').addClass("btn badge").html("<span class='glyphicon glyphicon-cog'></span>");
						var mn_code = $("<input />").attr("type", "hidden").attr("name", "mn_code[]").val(data.lists[i].mn_code);
						var mn_idx = $("<input />").attr("type", "hidden").attr("name", "mn_idx[]").val(data.lists[i].mn_idx);
						var list = $("<li></li>").attr('data-idx',data.lists[i].mn_idx ).addClass("list-group-item menu-item").html("<span class='"+data.lists[i].mn_icon+"'></span> "+data.lists[i].mn_name);
						
						(function(){
							var mn_idx = data.lists[i].mn_idx;
							badge.on("click", function(){
								thisObj.updateGET(mn_idx);
							});
						})();

						list.append(mn_code);
						list.append(mn_idx);
						list.append(badge);
						thisObj.listsSubMenu(data.lists[i].mn_code, list );
						mn_list.append(list);
					}
					mn_list.sortable({
						out : function(event, ui){
							$("#btn-save").removeAttr("disabled");
							$(ui.sender[0]).children('li').each(function(index){
								console.log( $(this) );
								var code = (index+1)*10;
								$(this).children('input[name="mn_code[]"]').val(code);
								var child = $(this).children('ul').find('input[name="mn_code[]"]');
								if( child.length > 0 ){
									child.each(function(index){
										$(this).val(""+code+((index+1)*10));
									});
								}
							});
						},
						cancel : "a, button"
					});
				}
			});
		},
		writeGET : function(idx){
			var thisObj = this;
			$.ajax ({
				method : "GET",
				dataType : "html",
				url : thisObj.url +"/json_write",
				data : {
					"mn_idx" : idx,
					"mn_type" : thisObj.mn_type
				},
				success : function(data){
					$("#modal-content").empty().append(data).modal('show');
					//입력
					$("#btn-write-submit").on("click", function(e){
						e.preventDefault();
						thisObj.writePOST();
					});
				}
			});
		},
		writePOST : function(){
			var thisObj = this;
			var formData = $("#frm_write").serialize();
			$.ajax ({
				method : "POST",
				data : formData,
				contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
				dataType : "json",
				url : thisObj.url +"/json_write",
				success : function(data){
					if( data.error ){
						var alert = $("#alert-area").empty();
						$.each(data.error, function(key, value){
							alert.append( '<div class="alert alert-danger" role="alert">'+value+'</div>');
						});
					}
					if( data.data == 'success' ){
						$("#modal-content").modal('hide');
						thisObj.init();
					}
				}
			});
		},
		updateGET : function(idx){
			var thisObj = this;
			if( ! idx ) return false;
			$.ajax ({
				method : "GET",
				dataType : "html",
				data : {
					"mn_type" : thisObj.mn_type
				},
				url : thisObj.url+"/json_view/"+idx ,
				success : function(data){
					$("#menu_view_content").empty().append(data);
					
					$(".menu-item").removeClass("active").filter(function(){
						return $(this).attr("data-idx") == idx
					}).addClass("active");
								
					
					//수정
					$("#btn-submit").on("click", function(e){
						e.preventDefault();
						thisObj.updatePOST(idx);
					});

					//추가
					$("#btn-add-menu").on("click", function(e){
						e.preventDefault();
						thisObj.writeGET(idx);
					});

					//삭제
					$("#btn-delete").on("click", function(e){
						e.preventDefault();
						thisObj.deletePOST(idx);
					});
				}
			});
		},
		updatePOST : function(idx){
			var thisObj = this;
			var formData = $("#frm").serialize();
			$.ajax({
				type : "POST",
				data : formData,
				contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
				url : thisObj.url+"/json_view/"+idx ,
				dataType : "json"
			}).done(function(data){
				if( data.error ){
					var alert = $("#alert-area").empty();
					$.each(data.error, function(key, value){
						alert.append( '<div class="alert alert-danger" role="alert">'+value+'</div>');
					});
				}
				if( data.data == 'success' ){
					thisObj.init();
				}
			});
		},
		deletePOST : function( idx ){
			var thisObj = this;
			$.ajax({
				type : "POST",
				data : { "mn_idx" : idx, "mn_type" : $("input[name='mn_type']").val() },
				url : thisObj.url +"/json_delete",
				dataType : "json"
			}).done(function(data){
				alert(data.msg);
				$("#modal-content").modal('hide');
				thisObj.init();
			});
		}
	}
</script>

