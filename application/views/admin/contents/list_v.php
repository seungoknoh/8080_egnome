<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">컨텐츠관리</a></li>
<li class="active">컨텐츠관리</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>컨텐츠관리<small> 컨텐츠에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<!-- board-top -->
		<div class="text-center board-top">
			<div class="well mt20">
				<form class="form-inline" action="<?=ADM_DIR?>/popup" method="get" onsubmit="return boardSearch(this)">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								<span class="skip">검색</span>
							</div>
							<select class="form-control" name="sfl" id="sfl" >
								<option value="cn_subject" <?php if($this -> sfl == 'cn_subject') echo "selected"; ?> >제목</option>
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
        			<col style="width:80px" />
        			<col style="width:120px" />
        		</colgroup>
        		<thead>
        			<tr>
        			<th scope="col">번호</th>
        			<th scope="col">제목</th>
        			<th scope="col">사용</th>
        			<th scope="col">등록일</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php foreach($list as $lt){ ?>
        			<tr>
        			<td><?php echo $lt -> num; ?></td>
        			<td class="text-left"><a class="link" href="/backS1te/contents/update/<?php echo $lt -> cn_idx; ?>" ><?php echo $lt -> cn_subject; ?></a></td>
        			<td><?php echo $lt -> cn_is_view == 0 ? "NO":"YES" ; ?></td>
        			<td><?php echo date("Y-m-d", strtotime($lt -> cn_regdate)); ?></td>
        			</tr>
        			<?php } ?>
        			<?php if( count($list) == 0 ) echo "<tr><td colspan='4'>no data</td></tr>"; ?>
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
			<a href="<?php echo $this->write_href ?>" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Write</a>
		</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function boardSearch(f){
		var action = f.action;
		f.action = action;
		return true;
	}
	
</script>

