                <!-- //ContentTop -->
                <div class="pt30"></div>
                <h2 class="SubContent__title"><span>공지사항</span></h2>
                <section class="SubContent">
                    <div class="Layout__section">
                        <div class="ContentsSearch">
	<!-- //검색 ContentsSearch -->						
						<form action="/<?php echo $this -> uri -> segment(1) ?>/lists/<?php echo $this->op_table; ?>" method="get" onsubmit="return boardSearch(this)">
                                <legend class="skip">게시물 검색</legend>
                                <input type="hidden" name="sca" value="<?php echo $this->sca ?>">
                                <div class="ContentsSearch__inner">
                                    <select width="8" name="sfl" id="sfl">
                                        <option value="">전체</option>
										<option value="bo_subject" <?php if($this -> sfl == 'bo_subject') echo "selected"; ?>  >제목</option>
										<option value="bo_content" <?php if($this -> sfl == 'bo_content') echo "selected"; ?>  >내용</option>
                                    </select>
                                    <input type="text" placeholder="검색어를 입력하세요." name="stx" id="stx"
                                        value="<?php echo $this->stx;?>">
                                    <button class="btn_search"><span>검색</span></button>									
                                </div>
                            </form>
	<!-- 검색 ContentsSearch -->
	<?php 
		$colLength = 4;
		if( $this -> option -> op_is_file ){
			$colLength++;
		}
	?>
	<!-- NoticeTable -->
		</div>
		<div class="pt20"></div>
		<div class="BoardTable__basic center">
			<table>
				<colgroup>
					<col class="col_num">
					<col class="col_subject">
					<col class="col_date m_hidden">
					<col class="col_hit m_hidden">
				</colgroup>
				<thead>
					<tr>
						<th scope="col"><span>번호</span></th>
						<th scope="col"><span>제목</span></th>
						<th scope="col" class="m_hidden"><span>날짜</span></th>
						<th scope="col" class="m_hidden"><span>조회</span></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($list as $lt){ ?>
					<tr>
						<td><?php echo $lt -> num; ?></td>
						<td class="left"><a class="link" href="<?php echo $lt -> link; ?>"><?php echo $lt -> bo_subject; ?></a></td>
						<td class="m_hidden"><?php echo date("Y.m.d", strtotime($lt -> bo_regdate)); ?></td>
						<td class="m_hidden"><?php echo $lt-> bo_hit ?></td>
					</tr>
					<?php } ?>
					<?php if( count($list) == 0 ) echo "<tr><td colspan='{$colLength}' class='empty'>내용이 없습니다.</td></tr>"; ?>									
				</tbody>
			</table>
		</div>
		<!-- pagination -->
		<nav class="pt30">
			<ul class="pagination">
			<?php echo $pagination; ?>
			</ul>
		</nav>
		<!-- pagination -->
		<div class="pt50"></div>
	</div>
</section>
	
	<!-- //NoticeTable -->


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