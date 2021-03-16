<div class="pt30"></div>
<section class="SubContent">
    <h2 class="SubContent__title"><span>마이크로바이옴 케어</span></h2>
</section>
<section class="SubContent bg_content8">
    <div class="Layout__section">
        <div class="pt60"></div>
        <h3 class="SubContent__title3 center isBar"><span class="t_uppercase">Microbiome Care</span>
        </h3>
        <div class="pt40"></div>
        <div class="SubContent__text center c_white">
            <p>이지놈에서는 앞선 분석 기술과 노하우로 미생물 모니터링 서비스를 제공하고 있습니다.<br>
                우리 몸(반려동물)에 서식하는 미생물 상태를 바탕으로 건강상태를 확인하고 맞춤형 처방을 경험해보세요.</p>
        </div>
        <div class="pt60"></div>
    </div>
</section>
<div class="pt60"></div>
<section class="SubContent">
    <div class="Layout__section">
        <h3 class="SubContent__title2"><span>eGome 에서 제공하는 장내미생물(Gut microbiome) 분석 서비스</span></h3>
        <div class="pt20"></div>
        <div class="SubContent__text">
            <p>예전에는 장내미생물이 단순히 소화를 도와주고 영양분을 제공해 주는 것으로 그 중요성이 부각되지 않았습니다. 하지만 지금은 여러 연구를 통해
                장내미생물이 소화기능 뿐만 아니라 각종 면역 관련질환, 비만, 더 나아가 뇌 기능에까지 영향이 준다는 사실이 밝혀지고 있습니다.
                eGnome에서는 그동안 쌓아온 첨단 Bioinformatics의 기술과 노하우를 이용하여 여러분들의 건강 관리에 도움을 주는 장내미생물 서비스를 개발하여
                제공하고 있습니다.</p>
        </div>
        <div class="pt30"></div>
        <div class="SubContent__img center">
            <img src="<?php echo IMG_DIR ?>/content/bus_img_1.png" alt="Microbiota + Biome = Microbiome">
        </div>
    </div>
</section>
<div class="pt60"></div>
<section class="SubContent bg_dark_blue">
    <div class="Layout__section">
        <!-- ServiceVisaul -->
        <div class="ServiceVisaul" id="ServiceVisaul">
            <div class="swiper-wrapper">
                <div class="ServiceVisaul__slide swiper-slide">
                    <div class="ServiceVisaul__info">
                        <h3 class="title">
                            <span class="small">인체 장내미생물 분석 서비스</span>
                            <span class="base">Human gut <br>microbiome analysis service</span>
                        </h3>
                        <div class="text">
                            <p>사람의 장내미생물을 qPCR, 3세대 NGS등 다양한 분석방법으로 <br>모니터링하여 현재 건강상태를 알기 쉽게 전달합니다.</p>
                        </div>
                    </div>
                    <div class="ServiceVisaul__img">
                        <img src="<?php echo IMG_DIR ?>/content/service_img_1.jpg" alt="">
                    </div>
                    <div class="ServiceVisual__link">
                        <a href="/pages/business/HUMANCARE"><span>서비스 바로가기</span></a>
                    </div>
                </div>
                <div class="ServiceVisaul__slide swiper-slide">
                    <div class="ServiceVisaul__info">
                        <h3 class="title">
                            <span class="small">인체 장내미생물 분석 서비스</span>
                            <span class="base">Animal Gut <br>Microbiome analysis service</span>
                        </h3>
                        <div class="text">
                            <p>반려 동물의 장내미생물을 qPCR 분석방법으로 확인후 결과를 <br>바탕으로 반려동물의 건강상태를 알기 쉽게 알려드립니다.</p>
                        </div>
                    </div>
                    <div class="ServiceVisaul__img">
                        <img src="<?php echo IMG_DIR ?>/content/service_img_2.jpg" alt="">
                    </div>
                    <div class="ServiceVisual__link">
                        <a href="/pages/business/ANIMALCARE"><span>서비스 바로가기</span></a>
                    </div>
                </div>
            </div>
            <div class="ServiceVisual__control">
                <div class="ServiceVisaul__count">
                    <span class="current"></span>
                    <span>/</span>
                    <span class="total">2</span>
                </div>
                <div class="ServiceVisual__arrow">
                    <div class="ServiceVisual__arrow_next"></div>
                    <div class="ServiceVisual__arrow_prev"></div>
                </div>
            </div>
        </div>
        <!-- //ServiceVisaul -->
        <div class="pt60"></div>
    </div>
</section>
<script>
$(document).ready(function() {
    var swiperCount = $(".ServiceVisaul__count");
    var serviceSwiper = new Swiper('#ServiceVisaul', {
        spaceBetween: 0,
        centeredSlides: true,
        autoplay: {
            delay: 5000
        },
        navigation: {
            nextEl: '.ServiceVisual__arrow_next',
            prevEl: '.ServiceVisual__arrow_prev',
        },
        on: {
            init: function(e) {
                swiperCount.find(".current").text(this.activeIndex + 1);
                var total = $(".ServiceVisaul .swiper-slide").size();
                swiperCount.find(".total").text(total);
            },
            slideChangeTransitionEnd: function() {
                swiperCount.find(".current").text(this.activeIndex + 1);
            },
        },
    });
});
</script>