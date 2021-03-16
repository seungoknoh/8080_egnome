<!-- HeaderArea 상단 -->

<div class="Header" id="Header">
    <div class="Header__content">
        <!-- logo -->
        <header class="clearfix Layout__relative">
            <h1 class="Header__logo"><a href="/" class="MainLogo"><span class="skip">egnome</span></a></h1>
            <!-- //logo -->
            <!-- MainMenu -->
            <div class="Header__nav">
                <div class="MainMenu" id="MainMenu">
                    <ul class="clear clearfix MainMenu__list">
                        <?php echo $menu_list;?>
                    </ul>
                </div>
            </div>
            <!-- //MainMenu -->
        </header>
        <div class="Header__bg"></div>
    </div>
</div>
<div class="HeaderLink">
    <a class="HeaderLink__total" id="TotalMenuBtn" href="javascript:;">
        <span class="skip">메뉴열기</span>
        <span class="line line_top"></span>
        <span class="line line_bottom"></span>
    </a>
    <a class="HeaderLink__contact" href="/company/CONTACT">
        <span class="skip">Contact Us.</span>
    </a>
</div>

<!-- //HeaderArea 상단 -->