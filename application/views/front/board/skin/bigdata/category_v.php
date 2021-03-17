<div class="pt30"></div>
<section class="SubContent">
    <h2 class="SubContent__title"><span>Big Data Management</span></h2>
</section>
<section class="SubContent bg_content16">
    <div class="Layout__section">
        <div class="pt60"></div>
        <h3 class="SubContent__title3 center isBar"><span>BIG DATA MANAGEMENT 서비스</span></h3>
        <div class="pt40"></div>
        <div class="SubContent__text center c_white">
            <p>대용량 시퀀싱 데이터를 기반으로 유전정보의 가치를 발굴하여 <br>사용자에게 효과적으로 전달하는 체계화된 통합 서비스입니다.</p>
        </div>
        <div class="pt60"></div>
    </div>
</section>
<div class="pt60"></div>
<section class="SubContent">
    <div class="Layout__section">
        <div class="TabSubArea">
            <a href="?sca=" class="TabSubArea__item <?php echo $this->sca == "" ? "active": "" ?>"><span>all</span></a>
            <?php foreach ($category_list as $category){ 
        	   $is_selected = "";
        	   if($category->bc_idx == $this->sca ){
        	       $is_selected = "active";
        	   }
        	?>
            <a href="?sca=<?php echo $category -> bc_idx ?>"
                class="TabSubArea__item <?php echo $is_selected ?>"><span><?php echo $category -> bc_name ?></span></a>
            <?php } ?>
        </div>

    </div>
    <div class="pt40"></div>
    <div class="Layout__section">
        <div class="DataArea">
            <div class="DataArea__list">
                <?php foreach($list as $lt){ ?>

                <div class="DataArea__item">
                    <div class="inner">
                        <a href="<?php echo $lt -> link; ?>" class="link"><span class="skip">바로가기</span></a>
                        <?php if( $lt->thumbnail['is_thumbnail']){ ?>
                        <div class="img"><img src="<?php echo $lt->thumbnail['src']; ?>"
                                style="height:358px;width;300px" alt="<?php echo $lt->thumbnail['alt']; ?>" /></div>
                        <?php }else{ ?>
                        <div class="img"><span
                                style="line-height:358px;width:300;display:block;text-align:center;background:#eee">NO
                                IMAGE</span></div>
                        <?php } ?>
                        <div class="info">
                            <div class="title"><?php echo $lt -> subject; ?></div>
                            <div class="txt"><?php echo $lt->bc_name; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="pt30"></div>
        <?php if(count($list) == 0){?>
        <div style="text-align: center;">
            <p style="line-height: 1.4;text-align: center;font-size: 1.15em;">등록된 데이터가 없습니다.</p>
        </div>
        <?php } ?>
        <ul class="pagination">
            <?php echo $pagination; ?>
        </ul>
        <div class="pt50"></div>
    </div>
    <div class="pt60"></div>
</section>