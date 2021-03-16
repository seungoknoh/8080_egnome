<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">게시판관리</a></li>
<li class="active"><?php echo $this->option->op_name; ?></li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2><?php echo $this->option->op_name; ?></h2>
	</div>
	<div class="panel-body">
		<!-- board-top -->
		<div class="text-center board-top">
			<div class="well mt20">
				<form class="form-inline" action="<?php echo $this->list_href; ?>" method="get" onsubmit="return boardSearch(this)">
					<input type="hidden" name="sca" value="<?php echo $this->sca ?>" />
					<div class="form-group">
						<label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								<span class="skip">검색</span>
							</div>
							<select class="form-control" name="sfl" id="sfl" >
								<option value="bo_subject" <?php if($this -> sfl == 'bo_subject') echo "selected"; ?>  >제목</option>
								<option value="bo_content" <?php if($this -> sfl == 'bo_content') echo "selected"; ?>  >내용</option>
								<option value="bo_writer" <?php if($this -> sfl == 'bo_writer') echo "selected"; ?> >작성자</option>
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
					<?php if( $this->stx != "" ){ ?>
					<div class="form-group">
						<a href="<?php echo $this->list_href ?>" class="col-xs-12 btn btn-default">목록</a>
					</div>
					<?php } ?>
				</form>
			</div>
		</div>
		<!-- //board-top -->
		<!-- button_area -->
		<div class="button_area text-right mt30">
			<a class="btn <?php echo $this->sst=='bo_regdate' ? "btn-info" : "btn-default"; ?>"  href="<?=ADM_DIR?>/board/index/<?php echo $this->op_table; ?><?=$q?>&sst=bo_regdate&sod=desc"><span class="glyphicon glyphicon-sort-by-attributes-alt"></span> 최신순</a>
			<a class="btn <?php echo $this->sst=='bo_subject' ? "btn-info" : "btn-default"; ?>" href="<?=ADM_DIR?>/board/index/<?php echo $this->op_table; ?><?=$q?>&sst=bo_subject&sod=desc"><span class="glyphicon glyphicon-sort-by-attributes-alt"></span> 이름순</a>
		</div>
		<!-- //button_area -->
	</div>
	<div class="panel-body" id="board-content">
        <p class="text-left">총 게시물 수 <strong><?php echo $total ?></strong> </p>
        <!-- board_cate_area -->
        <?php if($this->option->op_is_category == 1){ ?>
        <div class="board_cate_area clearfix">
        	<a href="?sca=" class="col-xs-3 col-md-2 btn <?php echo $this->sca == "" ? "btn-primary": "btn-default" ?>"   >전체</a>
        	<?php foreach ($category_list as $category){ 
        	   $is_selected = "btn-default";
        	   if($category->bc_idx == $this->sca ){
        	       $is_selected = "btn-primary";
        	   }
        	?>
        	<a href="?sca=<?php echo $category -> bc_idx ?>" class="col-xs-3 col-md-2 btn <?php echo $is_selected ?>" ><?php echo $category -> bc_name ?></a>
        	<?php } ?>
        </div>
        <?php } ?>
        <!-- //board_cate_area -->
        <!-- board-list -->
        <div class="gallery-list mt10">
            <div class="row">
              <?php foreach($list as $lt){ ?>
				<div class="col-xs-6 col-sm-4 col-lg-3 ">
					<div class="thumbnail">
						<div class="checkbox"><input type="checkbox" name="bo_idx[]" value="<?php echo $lt->bo_idx; ?>" /></div>
						<?php if( $lt->thumbnail['is_thumbnail']){ ?>
						<span class="img"> <img src="<?php echo $lt->thumbnail['src']; ?>" style="height:<?php echo $lt->thumbnail['height']; ?>px" alt="<?php echo $lt->thumbnail['alt']; ?>" /></span>
						<?php }else{ ?>
						<span class="img"><span style="line-height:300px;width:auto;display:block;text-align:center;background:#eee" >NO IMAGE</span></span>
						<?php } ?>
						<div class="caption">
							<h3 class="ellipsis">
                            <?php if( isset($lt->bc_name) ) echo "[{$lt -> bc_name}]"; ?>
                    		<?php echo $lt -> bo_subject; ?> 
                            </h3>
							<p>Writer : <?php echo $lt -> bo_writer; ?> <br /> Date : <?php echo date("Y-m-d", strtotime($lt -> bo_regdate)); ?></p>
							<p>Hit : <?php echo $lt -> bo_hit; ?></p>
							<p><a href="<?php echo $lt -> link; ?>" class="link btn btn-primary">수정</a></p>
						</div>
					</div>
				</div>
				<?php } ?>
              <?php if(count($list) == 0){?>
              <div class="col-sm-12"><p class="text-center">등록된 데이터가 없습니다.</p></div>
              <?php }?>
            </div>
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
		<div class="col-sm-6 text-left">
			<button class="btn-lg btn btn-primary" onclick="category.modal();"><span class="glyphicon glyphicon-pencil"></span> 카테고리수정</button>
		</div>
		<div class="col-sm-6 text-right">
			<a href="<?php echo $this->write_href ?>" id="btn-write" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Write</a>
		</div>
		</div>
	</div>
</div>

<!-- 카테고리모달 -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title"><?php echo $this->option->op_name; ?> 카테고리수정</h4>
			</div>
			<div class="modal-body">
				<form action="/backS1te/board/categoryUpdate/<?php echo $this->op_table; ?>" method="post" id="cateFrm">
					<input type="hidden" name="chk_bo_idxs" />
					<input type="hidden" name="op_table" value="<?php echo $this->op_table; ?>" />
					<div class="form-group">
						<label for="bc_idx">카테고리</label>
						<select name="bc_idx" id="bc_idx" class="form-control">
							<?php foreach($category_list as $cl){ ?>
							<option value="<?php echo $cl -> bc_idx ?>"><?php echo $cl -> bc_name ?></option>
							<?php } ?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
				<button type="button" class="btn btn-primary" onclick="category.update();">저장</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- //카테고리모달 -->

<script type="text/javascript">
	var category = {
		input : [],
		modal : function(){
			var objThis = this;
			objThis.input = [];
			var chk = $("input[name='bo_idx[]']:checked");
			if( chk.size() > 0 ){
				$('#categoryModal').modal('show');
				chk.each(function(i){
					objThis.input[i] = $(this).val();
				});
			}else{
				alert("카테고리를 수정 할 리스트를 하나이상 선택해주세요.");
			}
		},
		update : function(){
			var objThis = this;
			$("input[name='chk_bo_idxs']").val( objThis.input.join(",") );
			$("#cateFrm").submit();
		}
	}

	//카테고리수정
	$("#btn-category-modify").on("click", function(){
		if( $("input[name='bo_idx[]']:checked").size() > 0 ){
			$('#categoryModal').modal('show');
		}else{
			alert("카테고리를 수정 할 리스트를 하나이상 선택해주세요.");
		}
	});

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