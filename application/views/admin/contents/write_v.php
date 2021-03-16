<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">컨텐츠관리</a></li>
<li class="active">컨텐츠관리</li>
</ol>
<!-- //breadcrumb -->
<?php 
	$attr = array('class' => 'form-horizontal', 'id'=>'frm');
	echo form_open('', $attr); 
?>
<?php if($view != null){ ?>
<input type="hidden" name="cn_idx" value="<?php echo $view->cn_idx ?>" />
<?php } ?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>컨텐츠관리<small> 컨텐츠에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
    	<div id="alert-area">
    		<?php foreach ($errors as $err){ ?>
    		<div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
    		<?php } ?>
    	</div>
		<!-- board_write_area -->
		<div class="board_write_area">
			<div class="board_write">
				<div class="form-group">
					<label for="cn_subject" class="col-sm-2 control-label">제목</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="cn_subject" value="<?php echo $view == null ? "": $view->cn_subject; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="cn_is_view" class="col-sm-2 control-label">사용여부</label>
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
							<input type="checkbox" value="1" name="cn_is_view"  <?php echo $view == null ? "": ( $view->cn_is_view == 1 ? "checked" : "" ); ?> /> 사용
							</label>
						</div>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<label for="cn_content" class="col-sm-2 control-label">내용</label>
					<div class="col-sm-10">
						<?php create_editor("cn_content", $view == null ? "" : $view->cn_content); ?>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<label for="cn_sitelink" class="col-sm-2 control-label">사이트링크</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="cn_sitelink" value="<?php echo $view == null ? "": $view->cn_sitelink; ?>" />
					</div>
				</div>
			</div>
		</div>
		<!-- //board_write_area -->
	</div>
	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-12 text-right">
			<!-- board_write_bottom -->
			<div class="board_write_bottom clearfix">
				<div class="text-right">
					<a href="<?php echo $this->list_href ?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-th-list"></span> 목록</a>
					<button id="btn-delete" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제</button>
					<button id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 입력</button>
				</div>
			</div>
			<!-- //board_write_bottom -->
		</div>
	</div>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function(){
		//에디터
		<?php update_editor("cn_content"); ?>

		//수정, 등록
		$("#btn-submit").on("click", function(e){
			e.preventDefault();
			$("#frm").submit();
		});
		
		<?php if( $view != null ){ ?>
		//삭제
		$("#btn-delete").on("click", function(e){
			e.preventDefault();
			if( confirm("삭제하시겠습니까?") ){
				$("#frm").attr("action", "/backS1te/contents/delete");
				$("#frm").submit();
			}
		});
		<?php } ?>
	});
</script>