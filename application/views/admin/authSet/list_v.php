<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">환경설정</a></li>
<li class="active">권한관리추가</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>권한관리추가<small> 권한에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<!-- board-top -->
		<div class="text-center board-top">
			<div class="well mt20">
				<form class="form-inline" action="/backS1te/authSet" method="get" onsubmit="return boardSearch(this)">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								<span class="skip">검색</span>
							</div>
							<select class="form-control" name="sfl" id="sfl" >
								<option value="mb_id" <?php if($this -> sfl == 'mb_id') echo "selected"; ?>>회원아이디</option>
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
        <!-- board-list -->
        <div class="table-responsive board-list mt10">
        	<table class="table table-bordered table-striped table-hover table-center">
        		<colgroup>
        			<col style="width:120px" />
        			<col />
        			<col style="width:120px" />
        		</colgroup>
        		<thead>
        			<tr>
        			<th scope="col">회원아이디</th>
        			<th scope="col">메뉴</th>
        			<th scope="col">권한부여아이디</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php 
        			if( is_countable($list) && count($list) > 0 ){
        			     foreach($list as $lt){ 
        			         //자기자신은 제외한다.
        			         if( $lt -> mb_id == $this -> session -> userdata('mb_name') ) continue;
        			?>
        			<tr>
        			<td><a class="link" href="/backS1te/authSet/update/<?php echo $lt -> mb_id; ?>"><?php echo $lt -> mb_id; ?></a></td>
        			<td class="text-left"><?php echo $lt -> mn_name_list ?></td>
        			<td><?php echo $lt -> au_id; ?></td>
        			</tr>
        			<?php 
        			     }
        			} 
        			?>
        			<?php if( is_countable($list) && count($list) == 0 ) echo "<tr><td colspan='3'>no data</td></tr>"; ?>
        		</tbody>
        	</table>
        </div>
        <!-- //board-list -->
        
        <!-- pagination -->
        <nav class="text-center">
        	<ul class="pagination">
        	<?php //echo $pagination; ?>
        	</ul>
        </nav>
        <!-- pagination -->
        
        <script type="text/javascript">	
        	$("#chk_all").on("change", function(){
        		Common.fncCheckAll($(this), "chk_idx[]");
        	});
        </script>

	
	</div>
	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-12 text-right">
			<a href="/backS1te/authSet/write" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Write</a>
		</div>
		</div>
	</div>
</div>
