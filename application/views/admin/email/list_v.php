<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">환경설정</a></li>
<li class="active">회원메일발송</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>회원메일발송<small> 회원메일발송 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<!-- board-top -->
		<div class="text-center board-top">
			<div class="well mt20">
				<form class="form-inline" action="<?php echo $this->list_href; ?>" method="get">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								<span class="skip">검색</span>
							</div>
							<select class="form-control" name="sfl" id="sfl" >
								<option value="me_subject" <?php if($this -> sfl == 'me_subject') echo "selected"; ?> >제목</option>
								<option value="me_content" <?php if($this -> sfl == 'me_content') echo "selected"; ?> >내용</option>
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
        			<col style="width:100px"/>
        		</colgroup>
        		<thead>
        			<tr>
        			<th scope="col">번호</th>
        			<th scope="col">제목</th>
        			<th scope="col">등록일</th>
        			<th scope="col">메일발송</th>
        			<th scope="col">테스트</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php foreach($list as $lt){ ?>
        			<tr>
        			<td><?php echo $lt -> num; ?></td>
        			<td><a href="/backS1te/email/update/<?php echo $lt -> me_idx; ?>"><?php echo $lt -> me_subject; ?></a></td>
        			<td><?php echo date("Y-m-d", strtotime($lt -> me_regdate)); ?></td>
        			<td><a href="/backS1te/email/send/<?php echo $lt -> me_idx; ?>" class="btn btn-default">메일발송</a></td>
        			<td><a href="javascript:sendEmail(<?php echo $lt->me_idx; ?>);" class="btn btn-default">테스트발송</a></td>
        			</tr>
        			<?php } ?>
        			<?php if( count($list) == 0 ) echo "<tr><td colspan='5'>no data</td></tr>"; ?>
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
    			<a href="<?php echo $this->write_href; ?>" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Write</a>
    		</div>
		</div>
	</div>
</div>

<script>
	function sendEmail(_me_idx){
		$.ajax({
			url : "/backS1te/email/json_send_email",
			data : {
				"me_idx" : _me_idx
			},
			dataType : "json"
		}).done(function(data){
			alert(data.msg);
		});
	}
</script>

