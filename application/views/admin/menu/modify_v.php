<?php 
$attr = array('id'=>'frm');
echo form_open('', $attr); 
?>
<div class="panel panel-info">
	<div class="panel-heading">상세정보</div>
	<div class="panel-body">
		<input type="hidden" name="mn_code" value="<?php echo $view -> mn_code ?>" />
		<div class="form-group">
			<label for="mn_name">메뉴명(국문)</label>
			<input type="text" class="form-control" id="mn_name" name="mn_name" value="<?php echo $view -> mn_name ?>" placeholder="메뉴명(국문)을 입력하세요">
		</div>
		<div class="form-group">
			<label for="mn_name_en">메뉴명(영문)</label>
			<input type="text" class="form-control" id="mn_name_en" name="mn_name_en" value="<?php echo $view -> mn_name_en ?>" placeholder="메뉴명(영문)을 입력하세요.">
		</div>
		<div class="form-group">
			<label for="mn_link">링크</label>
			<input type="text" class="form-control" id="mn_link" name="mn_link" value="<?php echo $view -> mn_link ?>" placeholder="링크를 입력하세요.">
		</div>
		<div class="form-group">
			<label for="mn_level">권한</label>
			<select id="mn_level" name="mn_level" class="form-control">
				<option value="10" <?php echo $view->mn_level == "10" ? "selected" : "" ?> >10</option>
				<option value="9" <?php echo $view->mn_level == "9" ? "selected" : "" ?>>9</option>
				<option value="8" <?php echo $view->mn_level == "8" ? "selected" : "" ?>>8</option>
			</select>
		</div>
		<div class="form-group">
			<label for="mn_icon">아이콘</label>
			<div class="checkbox">
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-star",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-file",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-camera",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-blackboard",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-comment",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-cog",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-user",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-folder-close",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-paperclip",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-bullhorn",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-list",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-picture",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-envelope",$view->mn_icon); ?>
				<?php echo edit_radio("mn_icon","glyphicon glyphicon-th-large",$view->mn_icon); ?>
			</div>
		</div>
		<div class="checkbox">
			<label>
			<input type="checkbox" name="mn_is_view" value="1" <?php echo $view->mn_is_view == "1" ? "checked" : "" ?> > 사용여부
			</label>
		</div>
		<div class="checkbox">
			<label>
			<input type="checkbox" name="mn_is_alert" value="1" <?php echo $view->mn_is_alert == "1" ? "checked" : "" ?> > 준비중여부
			</label>
		</div>
	</div>
	<div class="panel-footer text-right">
		<button class="btn btn-danger" id="btn-add-menu"><span class="glyphicon glyphicon-list-alt"></span> 하위메뉴추가</button>
		<button class="btn btn-default" id="btn-delete"><span class="glyphicon glyphicon-trash"></span> 삭제</button>
		<button class="btn btn-default" id="btn-submit"><span class="glyphicon glyphicon-pencil"></span> 저장</button>
	</div>
</div>
</form>