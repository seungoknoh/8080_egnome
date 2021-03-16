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
        <div class="SubContent__img center">
            <img src="<?php echo IMG_DIR ?>/content/big_content_1.png" alt="">
        </div>
    </div>
    <div class="pt50"></div>
    <div class="Layout__section">
        <div class="DataArea">
            <div class="DataArea__top">
                <h2 class="title"><span>사업예시</span></h2>
                <a href="/board/category/bigdata" class="link"><span>전체보기</span></a>
            </div>
            <div class="pt20"></div>
            <div class="DataArea__list">
                <?php foreach($list as $lt){ ?>
                <div class="DataArea__item">
                    <div class="inner">
                        <a href="/board/view/bigdata/<?php echo $lt -> bo_idx; ?>" class="link"><span
                                class="skip">바로가기</span></a>
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
                <?php if(count($list) == 0){?>
                <div class="col-sm-12">
                    <p class="text-center">등록된 데이터가 없습니다.</p>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</section>
<div class="pt60"></div>
<div class="SubContent bg_blue2">
    <div class="pt60"></div>
    <div class="Layout__section">
        <h2 class="SubContent__title4 isBar center"><span>유전정보의 올바른 해석을 통해 <br>세상을 좀더 살기 좋은 곳으로 만드는
                생물정보분석 전문기업</span></h2>
        <div class="pt20"></div>
        <div class="SubContent__img center">
            <img src="<?php echo IMG_DIR ?>/content/big_logo.png" alt="">
        </div>
        <div class="pt20"></div>
        <div class="SubContent__text c_white center">
            <p>“고객의 관점에서 출발하여 문제를 정의하고 통계적 사고로 문제를 해결하여, <br>
                고객의 니즈에 최적화된 혁신적이고 체계적인 서비스를 제공하고 있습니다.”</p>
        </div>
    </div>
    <div class="pt60"></div>
</div>