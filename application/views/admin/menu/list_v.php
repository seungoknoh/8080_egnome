<!-- board-list -->
<ul class="list-group" id="sortable">
	<?php for($i = 0;$i<count($list); $i++ ){ ?>
	<li class="list-group-item menu-item" data-idx="<?php echo $list[$i]->mn_idx ?>" data-step="<?php echo $list[$i]->mn_step ?>" >
		<a href="javascript:;" class="btn badge"><span class='glyphicon glyphicon-cog'></span></a>
		<span class="step" data-step="<?php echo $list[$i] -> mn_step ?>"></span>
		<span class="<?php echo $list[$i] -> mn_icon; ?>"></span> <?php echo $list[$i] -> mn_name; ?>
		( <?php echo $list[$i] -> mn_code; ?>  )
	</li>
	<?php } ?>
	<?php if( count($list) == 0 ) echo "<li class='list-group-item'>No data</li>"; ?>
</ul>
<!-- //board-list -->
<script type="text/javascript">
	//ui-state-disabled
	$(document).ready(function(){
		$( "#sortable" ).sortable({});
	});
</script>