<form id="frmLevel">
<div class="modal-dialog ">
	<div class="modal-content">
		<div class="modal-header">
			<h3>회원 권한 설정</h3>
		</div>
		<div class="modal-body">
            <ul class="list-group">
            	<?php foreach ( $level as $le ){ ?>
                <li class="list-group-item">
                	<input type="hidden" name="ml_idx[]" value="<?php echo $le->ml_idx; ?>" />
					<div class="input-group">
						<div class="input-group-addon"><?php echo $le->ml_idx; ?></div>
                		<input type="text" class="form-control" name="ml_name[]" value="<?php echo $le->ml_name; ?>" />
                	</div>
                </li>
                <?php } ?>
            </ul>
		</div>
		<div class="modal-footer">
			<!-- board_write_bottom -->
			<div class="board_write_bottom clearfix">
				<div class="text-right">
					<a href="javascript:;" class="btn btn-default btn-lg" data-dismiss="modal"><span class="glyphicon glyphicon-th-list"></span> 목록</a>
					<a id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil"></span>회원 권한 수정</a>
				</div>
			</div>
			<!-- //board_write_bottom -->
		</div>
	</div>
</div>
</form>

