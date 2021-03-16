<!-- Layout__footer -->
<div class="Layout__footer">
    <div class="Footer" id="Footer">
        <div class="Layout__section clearfix">
            <div class="Footer__top">
                <div class="FooterMenu">
                    <ul class="clear clearfix FooterMenu__list">
                        <?php echo $menu_list;?>
                    </ul>
                </div>
                <div class="Footer__logo">
                    <div class="FooterLogo"><span class="skip"><?php echo "{$config -> cf_title}"; ?></span></div>
                </div>
            </div>
            <div class="clearfix Footer__bottom">
                <div class="Footer__address">
                    <p>주소 : <?php echo "{$config -> cf_addr1} {$config -> cf_addr2}"; ?></p>
                    <p><span>대표전화 : +82.<?php echo substr($config -> cf_tel, 1, 10);?></span> <span>FAX :
                            +82.<?php echo substr($config -> cf_fax, 1, 10);?></span></p>
                </div>
                <div class="Footer__copyright">
                    <p class="copyright"><span>COPYRIGHT ⓒ 2021 ㈜ 이지놈.ALL RIGHTS RESERVED.</span>
                    </p>
                </div>
            </div>
        </div>s
    </div>
</div>
<!-- //Layout__footer -->
<!-- Footer  -->