<!-- //ContentTop -->
<div class="pt30"></div>
<h2 class="SubContent__title"><span>보도자료</span></h2>
<section class="SubContent">
    <div class="Layout__section">
        <div class="ContentsSearch">
            <!-- 검색 ContentsSearch -->
            <form action="/<?php echo $this -> uri -> segment(1) ?>/lists/<?php echo $this->op_table; ?>" method="get"
                onsubmit="return boardSearch(this)">
                <legend class="skip">게시물 검색</legend>
                <input type="hidden" name="sca" value="<?php echo $this->sca ?>">
                <div class="ContentsSearch__inner">
                    <select width="8" name="sfl" id="sfl">
                        <option value="">전체</option>
                        <option value="bo_subject" <?php if($this -> sfl == 'bo_subject') echo "selected"; ?>>제목
                        </option>
                        <option value="bo_content" <?php if($this -> sfl == 'bo_content') echo "selected"; ?>>내용
                        </option>
                    </select>
                    <input type="text" placeholder="검색어를 입력하세요." name="stx" id="stx" value="<?php echo $this->stx;?>">
                    <button class="btn_search"><span>검색</span></button>
                </div>
            </form>
            <!-- 검색 ContentsSearch -->
            <?php 
		$colLength = 3;
		if( $this -> option -> op_is_file ){
			$colLength++;
		}
	?>
        </div>
        <div class="pt20"></div>
        <!-- //Content-->
        <div class="ContentGalleryList clear clearfix">
            <?php foreach($list as $ls){ ?>
            <div class="ContentGalleryList__item">
                <div class="inner">
                    <a href="<?php echo $ls -> link; ?>" class="link"><span class="skip">링크</span></a>
                    <?php if( isset($ls->thumbnail['is_thumbnail']) ){ ?>
                    <div class="img"><img src="<?php echo $ls->thumbnail['src']; ?>" style="height:288px;width:458px"
                            alt="<?php echo $ls->thumbnail['alt']; ?>"></div>
                    <?php }else{ ?>
                    <div class="img"><span
                            style=" margin-left: -20px;;padding:0;line-height:280px;width:404px;display:block;text-align:center;background:#eee">NO
                            IMAGE</span></div>
                    <?php } ?>
                    <div class="info">
                        <div class="pt20"></div>
                        <div class="title"><span><?php echo date("Y년 m월 d일", strtotime($ls -> bo_yearmd)) ?> :
                                <?php echo mb_strimwidth($ls -> bo_subject, '0', '30', '...', 'utf-8'); ?></span></div>
                        <div class="pt10"></div>
                        <div class="text">
                            <span><?php echo mb_strimwidth($ls->bo_content, '0', '50', '...', 'utf-8'); ?></span>
                        </div>
                        <div class="pt10"></div>
                        <div class="etc"><span>날짜 :
                                <?php echo date("Y.m.d", strtotime($ls -> bo_regdate)); ?></span><span>조회수 :
                                <?php echo $ls -> bo_hit; ?></span></div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
        <?php if( count($list) == 0 ) { ?>
        <div style="border-top:1px solid #000000">
            <div>
			<div class="pt20"></div>
                <div style="line-height: 1.4;text-align: center;font-size: 1.15em;">
                    <p style="line-height: 1.4;text-align: center;font-size: 1.15em;">검색된 내용이 없습니다.</p>
                </div>
            </div>
        </div>
        <?php  } ?>
        <!-- //Content-->
        <div class="pt10"></div>
        <ul class="pagination">
            <?php echo $pagination; ?>
        </ul>
        <div class="pt50"></div>
    </div>
</section>