<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">회원관리</a></li>
<li class="active">회원관리</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>회원관리<small> 회원에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<!-- board-top -->
		<div class="text-center board-top">
			<div class="well mt20">
				<form class="form-inline" action="<?php echo $this->list_href; ?>" method="get" onsubmit="return boardSearch(this)">
					<div class="form-group">
						<label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								<span class="skip">검색</span>
							</div>
							<select class="form-control" name="sfl" id="sfl" >
								<option value="mb_id" <?php if($this -> sfl == 'mb_id') echo "selected"; ?>  >아이디</option>
								<option value="mb_name" <?php if($this -> sfl == 'mb_name') echo "selected"; ?> >이름</option>
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
        <p class="text-left">
        	총 회원수 <strong><?php echo $count_total_member ?>명</strong>, 
        	탈퇴 <strong><?php echo $count_out_member ?>명</strong>
        </p>
        <!-- board_cate_area -->
        <div class="board_cate_area clearfix">
        	<a class='col-xs-6 col-md-2 <?php echo ($mb_level == "") && ($this->sfl != 'mb_state') ? "btn btn-primary" : "btn btn-default"; ?> ' href="/backS1te/member"  >전체 (<?php echo $count_total_member ?>명)</a>
        	<a class='col-xs-6 col-md-2 <?php echo ($this->stx == 0) && ($this->sfl == 'mb_state') ? "btn btn-primary" : "btn btn-default"; ?>' href="/backS1te/member/lists?stx=0&sfl=mb_state">탈퇴회원 (<?php echo $count_out_member ?>명)</a>
        	<?php foreach( $level as $le ){ ?>
        	<a class='col-xs-6 col-md-2 <?php echo $mb_level == $le->ml_idx ? "btn btn-primary" : "btn btn-default"; ?>' href="/backS1te/member/lists?mb_level=<?php echo $le->ml_idx ?>" ><?php echo $le->ml_name ?>(<?php echo $le->cnt ?>명)</a>
        	<?php } ?>
        </div>
        <!-- //board_cate_area -->
        <!-- board-list -->
        <div class="table-responsive board-list mt10">
        	<table class="table table-bordered table-striped table-hover table-center">
        		<colgroup>
        			<col style="width:50px" />
        			<col style="width:150px" />
        			<col style="width:210px" />
        			<col />
        			<col style="width:120px" />
        			<col style="width:100px" />
        		</colgroup>
        		<thead>
        			<tr>
        			<th scope="col">번호</th>
        			<th scope="col">아이디</th>
        			<th scope="col">이름</th>
        			<th scope="col">이메일</th>
        			<th scope="col">접속일</th>
        			<th scope="col">권한</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php foreach($list as $lt){ ?>
        			<tr <?php echo $lt -> mb_state == 0 ? "class='warning'" : "" ?> >
        			<td><?php echo $lt -> num; ?></td>
        			<td class="text-left"><a class="link" href="/backS1te/member/update/<?php echo $lt -> mb_idx; ?>/<?php echo $q ;?>"><?php echo $lt -> mb_id; ?></a></td>
        			<td><?php echo $lt -> mb_name; ?></td>
        			<td class="text-left"><?php echo $lt -> mb_email; ?></td>
        			<td><?php echo date("Y-m-d", strtotime($lt -> mb_regdate)); ?></td>
        			<td><?php echo $this-> level_m -> get_level_name ($lt -> mb_level) ?></td>
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
        
        <script type="text/javascript">	
        	$("#chk_all").on("change", function(){
        		Common.fncCheckAll($(this), "mb_idx[]");
        	});
        </script>
	</div>
	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-6">
			<button type="button" id="btn-level" class="btn-lg btn btn-info"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 회원권한설정</button>
		</div>
		<div class="col-sm-6 text-right">
			<a href="<?php echo $this->write_href; ?>" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Write</a>
		</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>

<script type="text/javascript">
    $(document).ready(function(){
    	Member.init();
    });
	var Member = {
		url : "/backS1te/member",
		init : function(){
			var thisObj = this;
			//회원권한
			$("#btn-level").on("click", function(e){
				e.preventDefault();
				thisObj.levelGET();
			});			
		},
		//회원권한
		levelGET : function(){
			var thisObj = this;
			$.ajax ({
				method : "GET",
				dataType : "html",
				url : thisObj.url+"/json_level",
				success : function(data){
					$("#modal-content").empty().append(data);
					$("#modal-content").modal('show');

					//권한이름 수정
					$("#btn-submit").on("click", function(e){
						e.preventDefault();
						thisObj.levelPOST();
					});
				}
			});
		},
		levelPOST : function(){
			var thisObj = this;
			$.ajax({
				type : "POST",
				data : $("#frmLevel").serialize(),
				url : thisObj.url+"/json_level",
				dataType : "json"
			}).done(function(data){
				$("#modal-content").modal('hide');
				alert(data.data);
				
				//권한이름 변경때문에 다시 리로드 필요.
				window.location.reload();
			});
		}
	}
	
	//검색
	function boardSearch(f){
		var action = f.action;
		if( f.stx.value == '' ){
			alert("검색어를 입력해주세요");
			f.stx.focus();
			return false;
		}
		f.action = action;
		return true;
	}
	
</script>