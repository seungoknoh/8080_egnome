<div class="pt30"></div>
<section class="SubContent">
    <h2 class="SubContent__title"><span>OMICS분석</span></h2>
</section>
<section class="SubContent bg_content11">
    <div class="Layout__section">
        <div class="pt60"></div>
        <h3 class="SubContent__title3 center isBar"><span>OMICS Analysis</span></h3>
        <div class="pt40"></div>
        <div class="SubContent__text center c_white">
            <p>이지놈에서는 유전체, 전사체, 후성유전체 등 다양한 분자 수준에서 생성된<br>
                여러 데이터들을 통합적이고, 유기적으로 분석합니다.</p>
        </div>
        <div class="pt60"></div>
    </div>
</section>
<div class="pt60"></div>
<section class="SubContent">
    <div class="Layout__section">
        <h3 class="SubContent__title2"><span>Omics Analysis</span></h3>
        <div class="pt20"></div>
        <div class="SubContent__text">
            <p>최근까지, 급격한 sequencing 기술의 발달로 유전체, 전사체 등의 부분적인 분석에서는 비약적인 발전을 이루었습니다. 하지만 특정 질병의 원인은
                한 가지가 아닌 경우가 많으며, 여러 차원의 데이터를 다면적으로 분석해야 그 원인을 찾는 경우가 많습니다. 저희 이지놈에서는 다양한 분석 경험을
                기반으로 한 심도 깊은 연구 결과를 제시합니다.</p>
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
                            <span class="small">유전체 분석</span>
                            <span class="base">Genome analysis</span>
                        </h3>
                        <div class="text">
                            <p>유전 정보의 구축, 비교 등을 통해 특이적 유전 정보 및 <br>구조 변이 등을 발굴하는 분석</p>
                        </div>
                    </div>
                    <div class="ServiceVisaul__img">
                        <img src="<?php echo IMG_DIR ?>/content/omics_img_1.jpg" alt="">
                    </div>
                    <div class="ServiceVisual__link">
                        <a href="/pages/business/GENOME"><span>서비스 바로가기</span></a>
                    </div>
                </div>
                <div class="ServiceVisaul__slide swiper-slide">
                    <div class="ServiceVisaul__info">
                        <h3 class="title">
                            <span class="small">유전체 분석</span>
                            <span class="base">Transcriptome analysis</span>
                        </h3>
                        <div class="text">
                            <p>전사체의 발현량 비교, 발현 패턴 파악 등을 통해 <br>특이적 유전자 발현 및 질병 연관성 등을 파악하는 분석</p>
                        </div>
                    </div>
                    <div class="ServiceVisaul__img">
                        <img src="<?php echo IMG_DIR ?>/content/omics_img_2.jpg" alt="">
                    </div>
                    <div class="ServiceVisual__link">
                        <a href="/pages/business/TRANSCIPT"><span>서비스 바로가기</span></a>
                    </div>
                </div>
                <div class="ServiceVisaul__slide swiper-slide">
                    <div class="ServiceVisaul__info">
                        <h3 class="title">
                            <span class="small">유전체 분석</span>
                            <span class="base">Epigenome analysis</span>
                        </h3>
                        <div class="text">
                            <p>DNA 메틸화, 히스톤 변형 등 DNA 서열의 변화 없이<br>유전자가 상이하게 발현하는 현상을 분석</p>
                        </div>
                    </div>
                    <div class="ServiceVisaul__img">
                        <img src="<?php echo IMG_DIR ?>/content/omics_img_3.jpg" alt="">
                    </div>
                    <div class="ServiceVisual__link">
                        <a href="/pages/business/EPIGENOME"><span>서비스 바로가기</span></a>
                    </div>
                </div>
                <div class="ServiceVisaul__slide swiper-slide">
                    <div class="ServiceVisaul__info">
                        <h3 class="title">
                            <span class="small">유전체 분석</span>
                            <span class="base">Metagenome analysis</span>
                        </h3>
                        <div class="text">
                            <p>미생물의 배양없이 유전체 정보만을 이용하여 환경 내 <br>모든 종을 동정하고, 상호작용 및 대사작용을 규명하는 분석</p>
                        </div>
                    </div>
                    <div class="ServiceVisaul__img">
                        <img src="<?php echo IMG_DIR ?>/content/omics_img_4.jpg" alt="">
                    </div>
                    <div class="ServiceVisual__link">
                        <a href="/pages/business/METAGENOME"><span>서비스 바로가기</span></a>
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
</section>