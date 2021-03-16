<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/member">환경설정</a></li>
<li class="active">팝업관리</li>
</ol>
<!-- //breadcrumb -->
<?php 
	$attr = array('class' => 'form-horizontal', 'id'=>'frm');
	echo form_open('', $attr); 
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>팝업관리<small> 팝업에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<div class="panel-body">
		<?php if($view != null){?>
		<input type="hidden" name="po_idx" value="<?php echo $view->po_idx ?>" />
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
						<div class="checkbox"><?php echo $view -> po_regdate; ?></div>
					</div>
				</div>
				<?php } ?>
				<div class="form-group">
					<label for="po_subject" class="col-sm-2 control-label">제목</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="po_subject"  value="<?php echo $view != null ? $view -> po_subject: ""; ?>"  />
					</div>
				</div>
				<div class="form-group">
					<label for="po_begin_time" class="col-sm-2 control-label">설정시간</label>
					<div class="col-sm-5">
						<div class="input-group">
							<div class="input-group-addon">시작시간</div>
							<input type="text" class="form-control datePicker" name="po_begin_time" id="po_begin_time" style="z-index:1050" value="<?php echo $view != null ? $view -> po_begin_time: ""; ?>" />
						</div>
					</div>
					<div class="col-sm-5">
						<div class="input-group">
							<div class="input-group-addon">종료시간</div>
							<input type="text" class="form-control datePicker" name="po_end_time" id="po_end_time" style="z-index:1050" value="<?php echo $view != null ? $view -> po_end_time : ""; ?>" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="po_hour" class="col-sm-2 control-label">시간</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" class="form-control" name="po_hour" value="<?php echo $view != null ? $view -> po_hour: "24"; ?>" />
							<div class="input-group-addon">시</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<label for="po_left" class="col-sm-2 control-label">팝업위치</label>
					<div class="col-sm-4">
						<div class="input-group">
							<div class="input-group-addon">왼쪽</div>
							<input type="text" class="form-control" name="po_left" value="<?php echo $view != null ? $view -> po_left : "0"; ?>" />
							<div class="input-group-addon">px</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="input-group">
							<div class="input-group-addon">위쪽</div>
							<input type="text" class="form-control" name="po_top" value="<?php echo $view != null ? $view -> po_top : "0" ?>" />
							<div class="input-group-addon">px</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="po_width" class="col-sm-2 control-label">크기</label>
					<div class="col-sm-4">
						<div class="input-group">
							<div class="input-group-addon">넓이</div>
							<input type="text" class="form-control" name="po_width" value="<?php echo $view != null ? $view -> po_width : "350"; ?>" />
							<div class="input-group-addon">px</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="input-group">
							<div class="input-group-addon">높이</div>
							<input type="text" class="form-control" name="po_height" value="<?php echo $view != null ? $view -> po_height : "400"; ?>" />
							<div class="input-group-addon">px</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<label for="po_color" class="col-sm-2 control-label">라인색상</label>
					<div class="col-sm-5">
						<div id="colorpicker" class="input-group colorpicker-component" title="Using format option">
							<input type="text" id="po_color" name="po_color" class="form-control" value="<?php echo $view != null ? $view -> po_color : "#000000" ?>"/>
							<span class="input-group-addon"><i></i></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="po_is_view" class="col-sm-2 control-label">사용여부</label>
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
							<input type="checkbox" value="1" name="po_is_view" <?php echo $view != null ? ( $view -> po_is_view == 1 ? "checked" : "") : ""; ?> /> 사용
							</label>
						</div>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<div class="col-sm-12">
						<?php create_editor("po_content", $view != null ? $view -> po_content : "" ) ?>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<label for="po_is_target" class="col-sm-2 control-label">링크새창</label>
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
							<input type="checkbox" value="1" id="po_is_target" name="po_is_target" <?php echo $view != null ? ( $view -> po_is_target == 1 ? "checked" : "") : ""; ?> /> 사용
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="po_link" class="col-sm-2 control-label">링크경로</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="po_link" id="po_link" value="<?php echo $view != null ? $view -> po_link : ""; ?>" />
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
    					<button id="btn-delete" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제</button>
    					<button id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 수정</button>
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
	//에디터
	<?php update_editor("po_content"); ?>

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
			$("#frm").attr("action", "/backS1te/popup/delete");
			$("#frm").submit();
		}
	});
	<?php } ?>
	
	//색상, 시간선택
	var startDateTextBox = $('#po_begin_time');
	var endDateTextBox = $('#po_end_time');

	$.timepicker.datetimeRange(
		startDateTextBox,
		endDateTextBox,
		{
			minInterval: (1000*60*60), // 1hr
			dateFormat: 'yy-mm-dd', 
			nextText: '다음 달', // next 아이콘의 툴팁.
			prevText: '이전 달', // prev 아이콘의 툴팁.			
			timeFormat: 'HH:mm',
			changeMonth: true,
			changeYear: true,			
			controlType: 'select',
			oneLine: true,
			dayNamesShort: [ "일", "월", "화", "수", "목", "금", "토" ],
			dayNamesMin: [ "일", "월", "화", "수", "목", "금", "토" ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNames: [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ],			
			start: {}, // start picker options
			end: {} // end picker options
		}
	);
	$('#colorpicker').colorpicker();
});
</script>
<script src="<?php echo ADM_JS_URL ?>/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="<?php echo ADM_JS_URL ?>/ux.popup.js?v=180816"></script>
