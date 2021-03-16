<!-- 카테고리 board_cate_area -->
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
<!-- //카테고리 board_cate_area -->	
<div class="boxArea-01">
	<div class="boxRelative">
		<!-- ContentsGallery -->
		<div class="ContentsGallery">
			<ul class="clear clearfix ContentsGallery__list">
				<?php foreach($list as $lt){ ?>
				<li class="ContentsGallery__item">
					<div class="inner">
						<a href="<?php echo $lt -> link; ?>" class="link"><span class="skip">과기정통부, 청년 미취업자 1,400명 혁신성장 실무인재로 양성한다 바로가기</span></a>
						<?php if( isset($lt->thumbnail) ){ ?>
						<div class="img"><img src="<?php echo $lt->thumbnail['src']; ?>" alt="<?php echo $lt->thumbnail['alt']; ?>" /></div>
						<?php } ?>
						<div class="info">
							<?php if( isset($lt->bc_name) && $options->op_is_view_category == 1 ){ ?>
							<div class="cate"><span><?php echo $lt -> bc_name; ?></span></div>
							<?php } ?>
							<div class="title"><span><?php echo $lt -> bo_subject; ?></span></div>
							<div class="text"><span> <?php echo $lt -> bo_content; ?></span></div><br><br>
							<!--<div class="date"><?php echo "<span class='day'>".date("M d", strtotime($lt -> bo_regdate))."</span> <span class='year'>".date("/ Y", strtotime($lt -> bo_regdate))."</span>" ?></div>-->
						</div>
					</div>
				</li>
				<?php } ?>
			</ul>
			<?php if( count($list) == 0 ) echo "<div class='ContentsVeiw__empty'><p>등록된 게시물이 없습니다.</p></div>"; ?>
		</div>
		<!-- //ContentsGallery -->
		<!-- ContentsPost -->
		<div class="ContentsPost">
			<h2 class="title"><span>최근포스트</span></h2>
			<ul class="clear ContentsPost__list" id="latestList"></ul>
		</div>
		<!-- //ContentsPost -->
		<!-- pagination -->
		<nav class="pt30">
			<ul class="pagination">
			<?php echo $pagination; ?>
			</ul>
		</nav>
		<!-- pagination -->
	</div>
</div>
<script type="text/javascript" src="<?php echo JS_DIR ?>/jquery.sortVertical.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".ContentsGallery").sortVertical({itemTop : 0, itemLeft : 0});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		window.lastest.list();
	});
	var lastest = (function(){
		return {
			el : $("#latestList"),
			list : function(){
				var thisObj = this;
				$.ajax({
					url : "/latest/list/<?php echo $this->op_table?>",
					dataType : "json",
					type : "GET"			
				}).done(function(data){
					thisObj.el.empty();
					for( var i=0; i<data.list.length;i++){
						var item = data.list[i];
						thisObj.el.append(thisObj.setItem(item));
					}
				});
			},
			setItem : function(_data){
				var output = '<li class="ContentsPost__item"><div class="inner">';
					output +='<a href="'+_data.link+'" class="link"><span class="skip">바로가기</span></a>';
					if( _data.bc_name ){
					output +='<div class="cate"><span>'+_data.bc_name+'</span></div>';
					}
					output +='<div class="text"><span>'+_data.subject+'</span></div>';
					//output +='<div class="date">'+_data.regdate+'</div>';
					output +='</li></div>';	
				return output;
			}
		}
	})();
</script>