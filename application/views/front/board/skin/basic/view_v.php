<section class="boxArea-01">
	<div class="ContentsVeiw NoticeView">
		<!-- ContentsVeiw__title -->
		<div class="ContentsVeiw__title">
			<h1 class="title"><span><?php echo $view -> bo_subject; ?></span></h1>
			<div class="data">
				<span class="writer"><?php echo $view -> bo_writer; ?></span>
				<span class="date"><?php echo $view -> bo_regdate; ?></span>
			</div>
		</div>
		<!-- //ContentsVeiw__title -->

		<!-- ContentsVeiw__file -->
		<?php if( $fileList != null ){ ?>
		<div class="ContentsVeiw__file">
			<div class="title"><span>첨부파일</span></div>
			<div class="data">
			<?php foreach($fileList as $list){ ?>
				<div class="item"><a class="file" href="<?php echo $link_file ?>?bf_idx=<?php echo $list->bf_idx ?>&token=<?php echo $token ?>" class="link"><?php echo $list->bf_source ?></a></div>
			<?php } ?>
			</div>
		</div>
		<?php } ?>
		<!-- //ContentsVeiw__file -->

		<!-- ContentsVeiw__content -->
		<div class="ContentsVeiw__content">
			<div class="inner">
			<?php echo $view->bo_content; ?>
			</div>
		</div>
		<!-- //ContentsVeiw__content -->

		<!-- ContentsVeiw__move -->
		<div class="ContentsVeiw__move">
			<div class="item">
				<div class="tit">이전</div>
				<?php if($view_list_prev != null){ ?>
					<a href="<?php echo $view_href.$view_list_prev -> bo_idx.$this->query ?>" class="link"><span><?php echo $view_list_prev -> bo_subject; ?></span></a>
					<div class="data">
						<span class="writer"><?php echo $view_list_prev -> bo_writer; ?></span>
						<span class="date"><?php echo date("Y-m-d", strtotime($view_list_prev -> bo_regdate)); ?></span>
					</div>
				<?php }else{ ?>
					<span class="link"><span>다음 글이 없습니다.</span></span>
				<?php } ?>
			</div>
			<div class="item">
				<div class="tit">다음</div>
				<?php if($view_list_next != null){ ?>
					<a href="<?php echo $view_href.$view_list_next -> bo_idx.$this->query ?>" class="link"><span><?php echo $view_list_next -> bo_subject; ?></span></a>
					<div class="data">
						<span class="writer"><?php echo $view_list_next -> bo_writer; ?></span>
						<span class="date"><?php echo date("Y-m-d", strtotime($view_list_next -> bo_regdate)); ?></span>
					</div>
				<?php }else{ ?>
					<span class="link"><span>다음 글이 없습니다.</span></span>
				<?php } ?>
			</div>			
		</div>
		<!-- //ContentsVeiw__move -->
	</div>
	<!-- ButtonArea -->

	<div class="ButtonArea">
		<div class="ButtonArea__left">
			<?php if( !empty($reply_href) ){ ?>		
			<a href="<?php echo $reply_href.$this->query; ?>" class="button__common etc"><span>답글</span></a>
			<?php } ?>
			<?php if( !empty($update_href) ){ ?>		
			<a href="<?php echo $update_href.$this->query; ?>" class="button__common etc"><span>수정</span></a>
			<?php } ?>
			<?php if( !empty($delete_href) ){ ?>		
			<a href="<?php echo $delete_href; ?>" id="btn-delete" class="button__common etc"><span>삭제</span></a>
			<?php } ?>
		</div>
		<div class="ButtonArea__right">
			<?php if( !empty($write_href) ){ ?>
			<a href="<?php echo $write_href.$this->query; ?>" class="button__common write"><span>글쓰기</span></a>
			<?php } ?>
			<a href="<?php echo $list_href.$this->query; ?>" class="button__common"><span>목록</span></a>
		</div>
	</div>
	<!-- //ButtonArea -->	
</section>

