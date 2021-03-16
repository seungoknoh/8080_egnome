<section class="boxArea-01">
	<!-- 카테고리 CategoryArea -->
	<?php if($this->option->op_is_category == 1){?>
	<div class="CategoryArea clearfix">
		<div class="CategoryArea__inner">
		<a href="?sca=" class="CategoryArea__item all <?php echo $this->sca == "" ? "on": "" ?>"><span>ALL</span></a>
		<?php
		foreach ($category_list as $category){
			$is_selected = "";
			if($category->bc_idx == $this->sca ){
				$is_selected = "on";
			}
		?>
		<a href="?sca=<?php echo $category -> bc_idx ?>" class="CategoryArea__item <?php echo $is_selected ?>"><span><?php echo $category -> bc_name ?></span></a>
		<?php } ?>
		</div>
	</div>	
	<?php } ?>
	<!-- //카테고리 CategoryArea -->
	
	<!-- 검색 ContentsSearch -->
	<div class="ContentsSearch pb10">
		<form action="/<?php echo $this -> uri -> segment(1) ?>/lists/<?php echo $this->op_table; ?>" method="get" onsubmit="return boardSearch(this)">
			<legend class="skip">게시물 검색</legend>
			<input type="hidden" name="sca" value="<?php echo $this->sca ?>" />
			<div class="ContentsSearch__inner">
				<select name="sfl" id="sfl" width="5">
					<option value="bo_subject" <?php if($this -> sfl == 'bo_subject') echo "selected"; ?>  >제목</option>
					<option value="bo_content" <?php if($this -> sfl == 'bo_content') echo "selected"; ?>  >내용</option>
					<option value="bo_writer" <?php if($this -> sfl == 'bo_writer') echo "selected"; ?> >작성자</option>
				</select>
				<input type="text" size="15" placeholder="검색어입력" name="stx" id="stx" value="<?php echo $this -> stx;?>">
				<button class="button__search"><span>검색</span></button>
				<?php if($this->stx){ ?>
				<a href="<?php echo $link?>" class="button__search list"><span>목록</span></a>
				<?php } ?>
			</div>
		</form>
	</div>
	<!-- //검색 ContentsSearch -->
	<?php 
		$colLength = 5;
		if( $this -> option -> op_is_file ){
			$colLength++;
		}
	?>
	<!-- NoticeTable -->
	<div class="ContentsTable-02 <?php echo $this->option->op_table."Table"; ?>">
		<table class="">
			<colgroup>
				<col class="col1" />
				<col class="col2" />
				<?php if( $this -> option -> op_is_file ){ ?>
				<col class="col6" />
				<?php } ?>
				<col class="col3 m_hidden" />
				<col class="col4 m_hidden" />
				<col class="col5 m_hidden" />
			</colgroup>
			<thead>
				<tr>
					<th scope="col">번호</th>
					<th scope="col">제목</th>
					<?php if( $this -> option -> op_is_file ){ ?>
					<th scope="col">자료</th>
					<?php } ?>
					<th scope="col" class="m_hidden">작성자</th>
					<th scope="col" class="m_hidden">등록일</th>
					<th scope="col" class="m_hidden">조회수</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $lt){ ?>
				<tr>
				<td><?php echo $lt -> num; ?></td>
				<td class="left">
					<a class="link" href="<?php echo $lt -> link; ?>">
					<?php if( $lt -> bo_level ) echo "<span class='ico'><img src='".IMG_DIR."/common/ico_reply.png' alt='답글' /></span>"; ?>
					<span><?php if( isset($lt->bc_name) ) echo "[{$lt -> bc_name}]"; ?><?php echo $lt -> bo_subject; ?></span>
					<?php if( $lt -> is_new ) echo "<span class='ico'><img src='".IMG_DIR."/common/icon_new.png' alt='새글' /></span>"; ?>
					<?php if( $lt -> is_file ) echo "<span class='ico'><img src='".IMG_DIR."/common/ico_file.png' alt='파일' /></span>"; ?>
					<?php if( $lt -> is_secret ) echo "<span class='ico'><img src='".IMG_DIR."/common/ico_secret.png' alt='비밀글' /></span>"; ?>
					</a>
 					<div class="m_data">
						<div>DATE : <?php echo date("Y-m-d", strtotime($lt -> bo_regdate)); ?></div>
						<div>WRITER : <?php echo$lt -> bo_writer; ?></div>
					</div>
				</td><?php echo $link_file; ?>
				<?php if( $this -> option -> op_is_file ){ ?>
				<td><?php if( $lt -> is_file ) echo '<a href="{$link_file}" class="Button__file icon"><span class="skip">파일</span></a>'; ?></td>
				<?php } ?>
				<td class="m_hidden" ><?php echo $lt -> bo_writer; ?></td>
				<td class="m_hidden" ><?php echo date("Y-m-d", strtotime($lt -> bo_regdate)); ?></td>
				<td class="m_hidden" ><?php echo $lt-> bo_hit ?></td>
				</tr>
				<?php } ?>
				<?php if( count($list) == 0 ) echo "<tr><td colspan='{$colLength}' class='empty'>내용이 없습니다.</td></tr>"; ?>
			</tbody>
		</table>
	</div>
	<!-- //NoticeTable -->

	<!-- ButtonArea -->
	<div class="ButtonArea">
		<div class="ButtonArea__right">
			<?php if( !empty($write_href) ){ ?>
			<a href="<?php echo $write_href.$this->query; ?>" class="button__common write"><span>글쓰기</span></a>
			<?php } ?>
		</div>
	</div>
	<!-- //ButtonArea -->	

	<!-- pagination -->
	<nav class="pt30">
		<ul class="pagination">
		<?php echo $pagination; ?>
		</ul>
	</nav>
	<!-- pagination -->

</section>
<script type="text/javascript">
	function boardSearch(f){
		var action = f.action;
		if( f.stx.value == '' ){
			alert("검색어를 입력해주세요");
			f.stx.focus();
			return false;
		}
		f.action = action;
		return true;
	}
</script>