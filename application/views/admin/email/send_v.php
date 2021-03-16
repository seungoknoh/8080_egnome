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
			<?php if($email_result != null){?>
			<div class="alert alert-warning alert-dismissible" role="alert">
				<?php foreach ($email_result as $el){ ?>
				<p><?php echo $el;?></p>
				<?php } ?>
			</div>
			<?php } ?>
			<input type="hidden" name="me_idx" value="<?php echo $me_idx; ?>" />
        	<div id="alert-area">
        		<?php foreach ($errors as $err){ ?>
        		<div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
        		<?php } ?>
        	</div>
			<!-- board_write_area -->
			<div class="board_write_area">
				<div class="board_write">
					<div class="form-group">
						<div class="control-label col-sm-2">회원선택</div>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="me_select" value="all" checked >전체회원 (<?php echo $member_count; ?>)</label> 
							<label class="radio-inline"><input type="radio" name="me_select" value="part">추가 이메일만 보내기</label>
						</div>
					</div>					
					<div class="form-group">
						<div class="control-label col-sm-2">메일링</div>
						<div class="col-sm-10 checkbox">
							<label><input type="checkbox" name="mb_is_email" value="1">수신 동의 한 회원만</label>
						</div>
					</div>
					<div class="form-group">
						<div class="control-label col-sm-2">추가 이메일</div>
						<div class="col-sm-10">
							<input type="text" name="me_add_email" class="form-control" placeholder="추가 email주소" />
						</div>
					</div>
					<hr />
					<div class="form-group">
						<label for="mb_level" class="col-sm-2 control-label">회원권한</label>
						<div class="col-sm-3">
							<select name="mb_level_start" id="mb_level_start" class="form-control">
								<option value="">선택하세요.</option>
								<?php for($i=1; $i< count($level_list);$i++){ 
								$is_selected = $i == 1 ? "selected" : "";
								?>
								<option <?php echo $is_selected; ?> value="<?php echo $level_list[$i]->ml_idx; ?>"><?php echo $level_list[$i]->ml_name; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-sm-1"><div class="checkbox">에서</div></div>
						<div class="col-sm-3">
							<select name="mb_level_last" id="mb_level_last" class="form-control">
								<option value="">선택하세요.</option>
								<?php for($i=1; $i< count($level_list);$i++){ 
								    $is_selected = $i == 9 ? "selected" : "";
								?>
								<option <?php echo $is_selected; ?> value="<?php echo $level_list[$i]->ml_idx; ?>"><?php echo $level_list[$i]->ml_name; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-sm-1"><div class="checkbox">까지</div></div>
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
					<button id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil"></span> 전송</button>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		//수정, 등록
		$("#btn-submit").on("click", function(e){
			e.preventDefault();
			$("#frm").submit();
		});
	});
</script>