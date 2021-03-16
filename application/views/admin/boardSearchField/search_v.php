<form id="frmSearch">
<div class="modal-dialog ">
	<div class="modal-content">
		<div class="modal-header">
			<h3>Analysis 설정</h3>
		</div>
		<div class="input-group">
			<div class="input-group-addon">Analysis Type 추가</div>
			<input type="text" class="form-control" name="bs_name" id="bs_name"  />
			<input type="hidden" class="form-control" name="bs_idx" id="bs_idx"value="1" />
			<input type="hidden" class="form-control" name="op_table" id="op_table" value="1"/>
			<input type="hidden" class="form-control" name="bs_option" id="bs_option" value="1"/>
			<input type="hidden" class="form-control" name="bs_order" id="bs_order" value="1"/>
			<span class="input-group-btn">
    			<button class="btn btn-default" type="button" id="btn-add-search">추가</button>
  			</span>
		</div>
		<div class="modal-body">
            <ul class="list-group">
            	<?php foreach ( $search as $le ){ ?>
                <li class="list-group-item">
				<div class="input-group">
                	<input type="hidden" name="bs_idx[]" value="<?php echo $le->bs_idx; ?>" />
						<div class="input-group-addon"><?php echo $le->bs_idx; ?></div>
                		<input type="text" class="form-control" name="bs_name[]" value="<?php echo $le->bs_name; ?>" />
						<div class="input-group-addon">
							<label><input type="checkbox" name="bc_delete[]" value="<?php echo $le->bs_idx; ?>" /><span>삭제</span></label>
						</div>						
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
					<a id="btn-search_submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil"></span>Analysis 수정</a>
				</div>
			</div>
			<!-- //board_write_bottom -->
		</div>
	</div>
</div>
</form>

