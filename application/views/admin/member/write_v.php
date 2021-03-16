<?php header('X-XSS-Protection:0'); ?>
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
		<?php 
			$attr = array('class' => 'form-horizontal', 'id'=>'frm');
			echo form_open('', $attr); 
		?>
    	<div id="alert-area">
    		<?php foreach ($errors as $err){ ?>
    		<div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
    		<?php } ?>
    	</div>
		<!-- board_write_area -->
		<div class="board_write_area">
			<div class="board_write">
			<?php if($view == null){ ?>
			<input type="hidden" name="mb_id_chk" id="mb_id_chk" value="<?php echo set_value('mb_id_chk'); ?>" />
			<?php }else{ ?>
			<input type="hidden" name="mb_idx" value="<?php echo $view->mb_idx; ?>" />
			<?php } ?>
			<div class="form-group">
				<label for="mb_id" class="col-sm-2 control-label">아이디</label>
				<div class="col-sm-10">
					<?php if($view == null){?>
					<div class="input-group">
					<input type="text" class="form-control" name="mb_id"  value="<?php echo set_value('mb_id'); ?>" />
					<a href="javascript:;" class="input-group-addon" onclick="return chkMemberId();" >중복확인</a>
					</div>					
					<?php }else{?>
					<input type="text" class="form-control" name="mb_id" value="<?php echo $view->mb_id; ?>" readonly  />
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label for="mb_name" class="col-sm-2 control-label">이름</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="mb_name" value="<?php echo $view != null ? $view->mb_name : set_value('mb_name'); ?>" <?php echo $view == null ? "" : "readonly"; ?> />
				</div>
			</div>
			<?php if($view == null ){?>
			<div class="form-group">
				<label for="mb_name" class="col-sm-2 control-label">비밀번호</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" name="mb_passwd" value="<?php echo set_value('mb_passwd'); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label for="mb_name" class="col-sm-2 control-label">비밀번호확인</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" name="mb_re_passwd" value="<?php echo set_value('mb_re_passwd'); ?>" />
				</div>
			</div>
			<?php }else{ ?>
			<div class="form-group">
				<label for="mb_name" class="col-sm-2 control-label">비밀번호재설정</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="mb_passwd" />
				</div>
				<div class="col-sm-4">
					<div class="checkbox">변경일 : <?php echo $view->mb_passwddate; ?></div>
				</div>					
			</div>
			<?php } ?>
			<div class="form-group">
				<label for="mb_level" class="col-sm-2 control-label">권한</label>
				<div class="col-sm-10">
					<select name="mb_level" id="mb_level" class="form-control">
						<option value="">회원레벨</option>
						<?php foreach( $level as $ls ){ 
						    $is_selected = "";
						    if( $view != null ){
							    $is_selected = $view -> mb_level == $ls->ml_idx ? "selected" : "";
							}
							if( set_value('mb_level') ){
								$is_selected = set_value('mb_level') == $ls->ml_idx ? "selected" : "";
							}
						?>
						<option value="<?php echo $ls -> ml_idx; ?>" <?php echo $is_selected; ?> ><?php echo $ls -> ml_name; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="mb_phone" class="col-sm-2 control-label">전화번호</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="mb_phone" value="<?php echo $view != null ? $view->mb_phone : set_value('mb_phone'); ?>" />
				</div>
				<div class="col-sm-4">
					<div class="checkbox">
						<label>
						<input type="checkbox" value="1" name="mb_is_phone" <?php echo $view != null ? ( $view->mb_is_phone == 1 ?  "checked" : "") : ""; ?> > 수신여부
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="mb_email" class="col-sm-2 control-label">이메일</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="mb_email" value="<?php echo $view != null ? $view->mb_email : set_value('mb_email'); ?>" />
				</div>
				<div class="col-sm-4">
					<div class="checkbox">
						<label>
						<input type="checkbox" value="1" name="mb_is_email" <?php echo $view != null ? ($view->mb_is_email == 1 ?  "checked" : "") : ""; ?> > 수신여부
						</label>
					</div>
				</div>
			</div>
			<?php if($view != null){ ?>
			<div class="form-group">
				<div class="col-sm-2 control-label">등록일</div>
				<div class="col-sm-10">
					<div class="checkbox">
						<?php echo $view-> mb_regdate; ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">최종접속일</div>
				<div class="col-sm-10">
					<div class="checkbox">
						<?php echo $view-> mb_latestdate ; ?>
					</div>
				</div>
			</div>
			<?php if( $view -> mb_state == 0 ){ ?>
			<div class="form-group">
				<div class="col-sm-2 control-label">탈퇴일</div>
				<div class="col-sm-10">
					<div class="checkbox">
						<?php echo $view -> mb_leavedate ?>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="form-group">
				<div class="col-sm-2 control-label">회원 설정</div>
				<div class="col-sm-10">
					<input type="hidden" name="mb_state" value="<?php echo $view != null ? $view->mb_state : ""; ?>" />
					<button class="btn btn-warning" id="btn-state" data-value="<?php echo $view->mb_state; ?>"><?php echo $view->mb_state == 0 ? "재가입처리": "탈퇴처리"; ?></button>
					<button class="btn btn-danger" id="btn-delete-member" data-value="<?php echo $view->mb_idx; ?>"><span class="glyphicon glyphicon-remove"></span> 영구삭제</button>
				</div>
			</div>
			<?php } ?>
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
					<a href="<?php echo $this->list_href; ?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-th-list"></span> 목록</a>
					<button id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> <?php echo $view == null ? "입력" : "수정"; ?></button>
				</div>
			</div>
			<!-- //board_write_bottom -->
		</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	<?php if($view == null){ ?>
	$(document).ready(function(){
		$('input[name="mb_id"]').change(function(){
			$('input[name="mb_id_chk"]').val('');
		});
	});
	function chkMemberId(){
		var mb_id = $('input[name="mb_id"]');
		var mb_id_chk = $('input[name="mb_id_chk"]');
		if( mb_id.val() != '' ){
			$.ajax({
				method : 'get',
				url : "/backS1te/memberAjax/id_chk",
				data : {
					'mb_id' : mb_id.val()
				}
			}).done(function(data){
				if( data ) alert('사용가능합니다.');
				mb_id_chk.val(data);
				$('input[name="mb_name"]').focus();
			});
		}else{
			alert("아이디를 입력해주세요");
			mb_id.focus();
		}
		return false;
	}
	<?php }else{ ?>
	//삭제
	$("#btn-delete-member").on("click", function(e){
		e.preventDefault();
		if( confirm("해당 회원을 영구삭제하시겠습니까?") ){
			$("#frm").attr("action", "/backS1te/member/delete");
			$("#frm").submit();
		}
	});
	$("#btn-state").on("click", function(e){
		e.preventDefault();
		if( $("input[name='mb_state']").val() == 1 ){
			$("input[name='mb_state']").val(0);
    		if( confirm("해당 회원을 탈퇴처리하시겠습니까?") ){
    			$("#frm").attr("action", "/backS1te/member/state");
    			$("#frm").submit();
    		}
		}else{
			if( confirm("해당 회원을 재가입 처리하시겠습니까?") ){
				$("input[name='mb_state']").val(1);
    			$("#frm").attr("action", "/backS1te/member/state");
    			$("#frm").submit();
    		}
		}
	});
	<?php } ?>
	//수정, 등록
	$("#btn-submit").on("click", function(e){
		e.preventDefault();
		<?php if($view == null){ ?>
		if( $('input[name="mb_id_chk"]').val() == "" ){
			alert("중복확인이 필요합니다.");
			return false;
		}
		<?php } ?>
		$("#frm").submit();
	});	
</script>

