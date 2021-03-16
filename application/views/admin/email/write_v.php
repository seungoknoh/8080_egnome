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
		<?php 
			$attr = array('class' => 'form-horizontal', 'id'=>'frm');
			echo form_open('', $attr); 
		?>
		<?php if($view != null){?>
		<input type="hidden" name="me_idx" value="<?php echo $view->me_idx; ?>" />
		<?php } ?>
        	<div id="alert-area">
        		<?php foreach ($errors as $err){ ?>
        		<div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
        		<?php } ?>
        	</div>
			<!-- board_write_area -->
			<div class="board_write_area">
				<div class="board_write">
					<div class="form-group">
						<label for="ma_subject" class="col-sm-2 control-label">제목</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="me_subject" value="<?php echo $view != null ? $view->me_subject : ""; ?>" />
						</div>
					</div>
					<hr />
					<div class="form-group">
						<div class="col-sm-12">
							<?php create_editor("me_content", $view == null ? "" : $view->me_content); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- //board_write_area -->
		<?php echo form_close(); ?>
	</div>
	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-12 text-right">	
			<!-- board_write_bottom -->
			<div class="board_write_bottom clearfix">
				<div class="text-right">
					<a href="<?php echo $this->list_href; ?>" class="btn btn-default btn-lg" ><span class="glyphicon glyphicon-th-list"></span> 목록</a>
					<button id="btn-delete" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제</button>
					<button id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 입력</button>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		//에디터
		<?php update_editor("me_content"); ?>

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
				$("#frm").attr("action", "/backS1te/email/delete");
				$("#frm").submit();
			}
		});
		<?php } ?>
	});
</script>