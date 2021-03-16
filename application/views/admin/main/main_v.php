<h1 class="page-header">Dashboard</h1>
<div class="panel panel-primary">
	<div class="panel-heading"><h4>최신글</h4></div>
	<div class="panel-body">
		<div class="table-responsive table-bordered">
			<table class="table table-striped table-center">
				<colgroup>
					<col style="width:15%" />
					<col  />
					<col style="width:10%"/>
					<col style="width:15%" />
					<col style="width:8%" />
					<col style="width:8%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col">TABLE</th>
						<th scope="col">TITLE</th>
						<th scope="col">WRITE</th>
						<th scope="col">DATE</th>
						<th scope="col">VIEW</th>
						<th scope="col">LINK</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($lastestList as $ls){ ?>
					<tr>
						<td><?php echo $ls -> op_name ?></td>
						<td class="text-left"><a href="<?php echo $ls->adminlink ?>"><?php echo $ls -> bo_subject ?></a></td>
						<td><?php echo $ls -> bo_writer ?></td>
						<td><?php echo $ls -> bo_regdate ?></td>
						<td>
						<?php echo $ls -> bo_is_view == 1 ? '<span style="color:#337ab7" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>' : '<span style="color:#ff3e3e" class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>'; ?>	
						</td>
						<td><a href="<?php echo $ls->link?>" class="col-xs-12 btn btn-primary">보기</a></td>
					</tr>
					<?php } ?>
					<?php if(count($lastestList) == 0){ echo "<tr><td colspan='6'>등록된 게시물이 없습니다.</td></tr>"; }?>
				</tbody>
			</table>
		</div>
	</div>
</div>