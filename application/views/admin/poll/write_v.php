<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/poll">환경설정</a></li>
<li class="active">설문관리</li>
</ol>
<!-- //breadcrumb  -->
<?php if(!empty($question_result)){ ?>
<!-- 설문 결과 -->
<div class="panel panel-danger">
	<div class="panel-heading">설문 결과</div>
	<div class="panel-body">
		<div class="form-group">
			<div class="col-sm-2 text-right"><strong>설문결과</strong></div>
			<div class="col-sm-10">
				<?php foreach($question_result as $result){ ?>
				<p><?php echo ($result->num+1)."."; ?> <?php echo $result->question; ?>  (<?php echo $result->total."/".$poll_total; ?>)</p>
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $result->percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $result->percent; ?>%;"><?php echo $result->percent; ?>%</span>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!-- //설문 결과 -->
<?php } ?>
<?php 
	$attr = array('class' => 'form-horizontal', 'id'=>'frm');
	echo form_open('', $attr); 
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>설문관리<small> 설문에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<?php if($view != null){?>
		<input type="hidden" name="pl_idx" value="<?php echo $view->pl_idx ?>" />
		<?php } ?>
    	<div id="alert-area">
    		<?php foreach ($errors as $err){ ?>
    		<div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
    		<?php } ?>
    	</div>
		<!-- board_write_area -->
		<div class="board_write_area">
			<div class="board_write">
				<?php if($view != null){?>
				<div class="form-group">
					<label class="col-sm-2 control-label">등록일</label>
					<div class="col-sm-10">
						<div class="checkbox"><?php echo $view -> pl_regdate; ?></div>
					</div>
				</div>
				<?php } ?>
				<div class="form-group">
					<label for="pl_subject" class="col-sm-2 control-label">설문제목</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="pl_subject"  value="<?php echo $view != null ? $view -> pl_subject: ""; ?>"  />
					</div>
				</div>
				<div class="form-group">
					<label for="pl_content" class="col-sm-2 control-label">설문내용</label>
					<div class="col-sm-10">
						<textarea name="pl_content" class="form-control" id="pl_content" cols="30" rows="5"><?php echo $view != null ? $view -> pl_content: ""; ?></textarea>
					</div>
				</div>
				
				<div class="form-group">
					<label for="op_cate" class="col-sm-2 control-label">설문작성</label>
					<div class="col-sm-10">
						<?php $i=0; while($i < 8){ ?>
						<div class="input-group" style="margin-bottom:2px">
							<div class="input-group-addon"><?php echo $i+1;?></div>
							<input type="text" class="form-control" name="pl_question[]" value="<?php if($view != null){ echo $question_list[$i] != null ? trim($question_list[$i]) : ''; } ?> " />
						</div>
						<?php $i++; } ?>
					</div>
				</div>
				<div class="form-group">
					<label for="pl_is_view" class="col-sm-2 control-label">사용여부</label>
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
							<input type="checkbox" value="1" name="pl_is_view" <?php echo $view != null ? ( $view -> pl_is_view == 1 ? "checked" : "") : ""; ?> /> 사용
							</label>
						</div>
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
    					<a href="<?php echo $this->list_href; ?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-th-list"></span> 목록</a>
    					<a id="btn-delete" href="#" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제</a>
    					<button id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> <?php echo $view == null ? "작성" : "수정"; ?></button>
    				</div>
    			</div>
    			<!-- //board_write_bottom -->
    		</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function(){

	$(".btn-ql-delete").each(function(_index){
		var index = _index;
		$(this).on("click", function(e){
			e.preventDefault();
			$(".list-ql-item").eq(index).remove();
		});
	});

	//수정, 등록
	$("#btn-submit").on("click", function(e){
		e.preventDefault();
		$("#frm").submit();
	});
	
	<?php if($view != null){ ?>
	//삭제
	$("#btn-delete").on("click", function(e){
		e.preventDefault();
		if( confirm("삭제하시겠습니까?") ){
			$("#frm").attr("action", "/backS1te/poll/delete");
			$("#frm").submit();
		}
	});
	<?php } ?>
});
</script>
