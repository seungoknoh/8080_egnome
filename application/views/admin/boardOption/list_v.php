<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">게시판관리</a></li>
<li class="active">게시판관리</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>게시판관리<small> 게시판에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<!-- board-top -->
		<div class="text-center board-top">
			<div class="well mt20">
				<form class="form-inline" action="<?php echo $this->list_href; ?>" method="get" onsubmit="return boardSearch(this)">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								<span class="skip">검색</span>
							</div>
							<select class="form-control" name="sfl" id="sfl" >
								<option value="op_table" <?php if($this -> sfl == 'op_table') echo "selected"; ?> >테이블명</option>
								<option value="op_name" <?php if($this -> sfl == 'op_name') echo "selected"; ?> >게시판이름</option>
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
        			<col />
        			<col style="width:80px" />
        			<col style="width:80px" />
        			<col style="width:120px" />
        		</colgroup>
        		<thead>
        			<tr>
        			<th scope="col">번호</th>
        			<th scope="col">테이블명</th>
        			<th scope="col">게시판이름</th>
        			<th scope="col">게시물수</th>
        			<th scope="col">이동</th>
        			<th scope="col">등록일</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php foreach($list as $lt){ ?>
        			<tr>
        			<td><?php echo $lt -> num; ?></td>
        			<td><a class="link" href="/backS1te/boardOption/update/<?php echo $lt -> op_idx; ?>/<?php echo $q ;?>"><?php echo $lt -> op_table; ?></a></td>
        			<td><?php echo $lt -> op_name; ?></td>
        			<td><?php echo $lt -> op_count; ?></td>
        			<td><a href="/backS1te/board/index/<?php echo $lt -> op_table; ?>">링크</a></td>
        			<td><?php echo date("Y-m-d", strtotime($lt -> op_regdate)); ?></td>
        			</tr>
        			<?php } ?>
        			<?php if( count($list) == 0 ) echo "<tr><td colspan='6'>no data</td></tr>"; ?>
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
    			<a href="./boardOption/write<?php echo $this->query; ?>" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Write</a>
    		</div>
		</div>
	</div>
</div>


<script type="text/javascript">
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