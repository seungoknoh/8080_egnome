<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h3>메뉴 정보 입력 </h3>
		</div>
		<div class="modal-body">
			<?php 
				$attr = array('id'=>'frm_write');
				echo form_open('', $attr); 
			?>
				<div id="alert-area"></div>
				<input type="hidden" name="mn_type" value="<?php echo $mn_type ?>" />
				<input type="hidden" name="mn_code" value="<?php echo $mn_next_code ?>" />
				<div class="form-group">
					<label for="mn_name">메뉴명(국문)</label>
					<input type="text" class="form-control" id="mn_name" name="mn_name" value="" placeholder="메뉴명(국문)을 입력하세요">
				</div>
				<div class="form-group">
					<label for="mn_name_en">메뉴명(영문)</label>
					<input type="text" class="form-control" id="mn_name_en" name="mn_name_en" value="" placeholder="메뉴명(영문)을 입력하세요.">
				</div>
				<div class="form-group">
					<label for="mn_link">링크</label>
					<input type="text" class="form-control" id="mn_link" name="mn_link" value="" placeholder="링크를 입력하세요.">
				</div>
				<div class="form-group">
					<label for="mn_level">권한</label>
					<select id="mn_level" name="mn_level" class="form-control">
						<option value="10">10</option>
						<option value="9">9</option>
						<option value="8">8</option>
					</select>
				</div>
        		<div class="form-group">
        			<label for="mn_icon">아이콘</label>
        			<div class="checkbox">
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-star"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-file"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-camera"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-blackboard"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-comment"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-cog"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-user"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-folder-close"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-paperclip"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-bullhorn"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-list"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-picture"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-envelope"); ?>
        				<?php echo edit_radio("mn_icon","glyphicon glyphicon-th-large"); ?>
        			</div>
        		</div>				
				<div class="checkbox">
					<label><input type="checkbox" name="mn_is_view" value="1"> 사용여부</label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="mn_is_alert" value="1"> 준비중여부</label>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<!-- board_write_bottom -->
			<div class="board_write_bottom clearfix">
				<div class="text-right">
					<button type="button" class="btn btn-default btn-lg" data-dismiss="modal"><span class="glyphicon glyphicon-th-list"></span> 목록</button>
					<button id="btn-write-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 입력</button>
				</div>
			</div>
			<!-- //board_write_bottom -->
		</div>
		
	</div>
</div>

