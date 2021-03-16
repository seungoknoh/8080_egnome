<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">환경설정</a></li>
<li class="active">팝업관리</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>팝업관리<small> 팝업에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<!-- board-top -->
		<div class="text-center board-top">
			<div class="well mt20">
				<form class="form-inline" action="<?php echo $this -> list_href ?>" method="get" onsubmit="return boardSearch(this)">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								<span class="skip">검색</span>
							</div>
							<select class="form-control" name="sfl" id="sfl" >
								<option value="po_subject" <?php if($this -> sfl == 'po_subject') echo "selected"; ?> >제목</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="stx" class="skip">검색어입력</label>
						<input type="text" class="form-control" placeholder="검색어입력" name="stx" id="stx" value="<?php echo $this -> stx;?>">
					</div>
					<div class="form-group clearfix">
						<button type="submit" class="col-xs-12 btn btn-primary">검색</button>
					</div>
				</form>
			</div>
		</div>
		<!-- //board-top -->
	</div>
	<div class="panel-body" id="board-content">
        <p>전체 게시물 수 : <strong><?php echo $total ?></strong></p>
        <!-- board-list -->
        <div class="table-responsive board-list mt10">
        	<table class="table table-bordered table-striped table-hover table-center">
        		<colgroup>
        			<col style="width:50px" />
        			<col />
        			<col style="width:120px"/>
        			<col style="width:120px"/>
        			<col style="width:80px" />
        			<col style="width:120px" />
        			<col style="width:100px" />
        		</colgroup>
        		<thead>
        			<tr>
        			<th scope="col">번호</th>
        			<th scope="col">제목</th>
        			<th scope="col">시작시간</th>
        			<th scope="col">종료시간</th>
        			<th scope="col">사용</th>
        			<th scope="col">등록일</th>
        			<th scope="col">미리보기</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php foreach($list as $lt){ ?>
        			<tr>
        			<td><?php echo $lt -> num; ?></td>
        			<td><a class="link" href="/backS1te/popup/update/<?php echo $lt -> po_idx; ?>"><?php echo $lt -> po_subject; ?></a></td>
        			<td><?php echo $lt -> po_begin_time; ?></td>
        			<td><?php echo $lt -> po_end_time; ?></td>
        			<td><?php echo $lt -> po_is_view == 0 ? "NO":"YES" ; ?></td>
        			<td><?php echo date("Y-m-d", strtotime($lt -> po_regdate)); ?></td>
        			<td><button class="btn btn-default" onclick="popup.preview(<?php echo $lt -> po_idx; ?>);">미리보기</button></td>
        			</tr>
        			<?php } ?>
        			<?php if( count($list) == 0 ) echo "<tr><td colspan='7'>no data</td></tr>"; ?>
        		</tbody>
        	</table>
        </div>
        <!-- //board-list -->
        <!-- pagination -->
        <nav class="text-center">
        	<ul class="pagination">
        	<?php echo $pagination; ?>
        	</ul>
        </nav>
        <!-- pagination -->
        	
	</div>
	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-12 text-right">
			<a href="<?php echo $this->write_href; ?>" id="btn-write" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Write</a>
		</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){

	});
	
	var Board = {
		url : "/backS1te/popup",
		init : function(){
			
		},
		writeGET : function(){
			var thisObj = this;
			$.ajax ({
				method : "GET",
				dataType : "html",
				url : thisObj.url+"/write",
				success : function(data){
					$("#modal-content").empty().append(data);
					$("#modal-content").modal('show');

					//입력
					$("#btn-submit").on("click", function(e){
						e.preventDefault();
						<?php update_editor("po_content"); ?>
						thisObj.writePOST();
					});
				}
			});
		},
		writePOST : function(){
			var thisObj = this;
			var formData = $("#frm").serialize();
			$.ajax ({
				method : "POST",
				data : formData,
				contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
				dataType : "json",
				url : thisObj.url+"/write",
				success : function(data){
					if( data.error ){
						var alert = $("#alert-area").empty();
						$.each(data.error, function(key, value){
							alert.append( '<div class="alert alert-danger" role="alert">'+value+'</div>');
						});
					}
					if( data.data == 'success' ){
						$("#modal-content").modal('hide');
						thisObj.lists();
					}
				}
			});
		},
		updateGET : function(url){
			var thisObj = this;
			$.ajax ({
				method : "GET",
				dataType : "html",
				url : url,
				success : function(data){
					$("#modal-content").empty().append(data);
					$("#modal-content").modal('show');
					
					//수정
					$("#btn-submit").on("click", function(e){
						e.preventDefault();
						<?php update_editor("po_content"); ?>
						thisObj.updatePOST(url);
					});
					
					//삭제
					$("#btn-delete").on("click", function(e){
						e.preventDefault();
						if( confirm("삭제하시겠습니까?") ){
							thisObj.deletePOST($('input[name="po_idx"]').val());
						}
					});
				}
			});
		},
		updatePOST : function(url){
			var thisObj = this;
			var formData = $("#frm").serialize();

			$.ajax({
				type : "POST",
				data : formData,
				contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
				url : url,
				dataType : "json"
			}).done(function(data){
				if( data.error ){
					var alert = $("#alert-area").empty();
					$.each(data.error, function(key, value){
						alert.append( '<div class="alert alert-danger" role="alert">'+value+'</div>');
					});
				}
				if( data.data == 'success' ){
					$("#modal-content").modal('hide');
					thisObj.lists();
				}
			});
		},
		deletePOST : function( po_idx ){
			var thisObj = this;
			$.ajax({
				type : "POST",
				data : { "po_idx" : po_idx },
				url : thisObj.url+"/delete",
				dataType : "json"
			}).done(function(data){
				if( data.result ){
					alert("삭제되었습니다.");
					$("#modal-content").modal('hide');
					thisObj.lists();
				}
			});
		}
	}
	function boardSearch(f){
		var action = f.action;
		f.action = action;
		return true;
	}
</script>

<script type="text/javascript" src="<?php echo ADM_JS_URL; ?>/ux.popup.js?v=180816"></script>
