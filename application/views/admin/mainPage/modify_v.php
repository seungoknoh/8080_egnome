<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/config">메인화면관리</a></li>
<li class="active">메인페이지관리</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>메인페이지관리<small> 웹사이트 메인페이지에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<form action="<?php echo current_url(); ?>/update" method="post" id="frm" class="form-horizontal">
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-heading"><span class="glyphicon glyphicon-cog"></span> 메인화면 설정</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="mp_youtube" class="col-sm-2 control-label">유투브링크</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mp_youtube"  value="<?php echo $view != null ? $view -> mp_youtube : ""; ?>" />
					</div>
				</div>
				<!--<div class="form-group">
					<label for="mp_poll" class="col-sm-2 control-label">설문</label>
					<div class="col-sm-8">
						<select name="mp_poll" id="mp_poll" class="form-control">
							<option value="">선택</option>
							<?php foreach($poll_list as $pl){ 
								$is_selected = $view -> mp_poll == $pl->pl_idx ? "selected" : "";
							?>
							<option value="<?php echo $pl->pl_idx; ?>" <?php echo $is_selected; ?>><?php echo $pl->pl_subject; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-sm-2">
						<?php if( isset($view -> mp_poll) && $view -> mp_poll != 0 ){ ?>
						<a href="/backS1te/poll/update/<?php echo $view -> mp_poll; ?>" class="btn btn-info"></span>결과보기</a>
						<?php } ?>
					</div>
				</div>	-->			
			</div>
		</div>
	</div>

	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-12 text-right">
			<button type="submit" id="btn-write" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Write</button>
		</div>
		</div>
	</div>
	</form>
</div>

