/*
    author : wixon
    date : 2021-01-30
    file : ui.js
*/
$(document).ready(function(){
   UI.init();
});
var UI = (function(){
    return{
        init : function(){
            //메뉴
            this.totalMenu().init();
            this.global();
            this.mainMenu();

            //로딩중
            this.loading();
            this.datepicker();
            this.fixedHeader();
        },
        main : function(){
            var that = this;
            var visual_swiper = new Swiper('#VisualArea', {
                spaceBetween: 0,
                centeredSlides: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    bulletClass : "swiper-pagination-custom",
                    clickableClass : "swiper-pagination-clickable",
                    currentClass : "swiper-pagination-current",
                },
                effect : "fade"
            });
            
            var content1_swiper = new Swiper('#MainContent1', {
                spaceBetween: 0,
                centeredSlides: true,
                autoplay: {
                    delay: 10000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    bulletClass : "swiper-pagination-custom",
                    clickableClass : "swiper-pagination-clickable",
                    currentClass : "swiper-pagination-current",
                },
            });

            $('#CompanySlider').slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                variableWidth: true
            });

            that.searviceArea();
        }, 
        mainMenu : function(){
            var menulist = $(".MainMenu__list");
            var onMenu = null;
            var allMenu = menulist.children("li").children("ul");
            var bg = $(".Header__bg");

            menulist.children("li").each(function(index){
                $(this).addClass("menu_"+(index+1));
            });

            menulist.children("li").on("mouseenter", function(e){
                var list = $(this).children("ul");
                if( list.size() ==  0 ) return;
                allMenu.removeClass("active");
                list.addClass('active')
                onMenu = list;
                bg.addClass("active");
                onMenu.off("mouseleave", onLeave).on("mouseleave", onLeave);
            });
            
            function onLeave(){
                onMenu.removeClass('active');
                bg.removeClass("active");
            }
        },
        global : function(){
            var content = $("#HeaderGlobal");
            if( content.size() == 0 ) return;
            var btn = content.find(".HeaderGlobal__current");
            var list = content.find(".HeaderGlobal__list");
            var isActive = -1;
            btn.on("click", function(){
                if(isActive == -1){
                    list.addClass("active");
                    isActive = 1;
                }else{
                    list.removeClass("active");
                    isActive = -1;
                }
            })
        },
        searviceArea : function(){
            var content = $("#ServiceArea");
            if( content.size() == 0 ) return;
            var img = content.find(".ServiceArea__img img");
            var items = content.find(".ServiceArea__item");
            items.each(function(){
                $(this).off("mouseenter").on("mouseenter", function(){
                    var over = $(this).data('over');
                    img.attr('src', over);
                });
            })

        },
        datepicker : function(){
            $(".datepicker").datepicker();
        },
        loading : function(){
            var loading = $("#loading");
            $(window).on("load", function(){
                loading.addClass("inactive");
            });           
        },
        totalMenu : function(){
            var totalMenu =  $("#TotalGnb");
            var totalMenuBtn = $("#TotalMenuBtn");
            return {
                currentActive : -1,
                init : function(){
                    var that = this;
                    totalMenuBtn.off("click").on("click", function(e){
                        e.preventDefault();
                        if( that.currentActive == -1 ){
                            that.open();
                        }else{
                            that.close();
                        }
                    });
                },
                close: function(){
                    var that = this;
                    totalMenu.removeClass("active");
                    totalMenuBtn.removeClass("active");
                    that.currentActive = -1;
                },
                open : function(){
                    var that = this;
                    totalMenu.addClass("active"); 
                    totalMenuBtn.addClass("active");
                    that.currentActive = 1;
                }
            }
        },
        loadMotion : function(){
            var $motion = $('.n-motion');
            var windowT;
            if($motion.length){
                $motion.each(function(i){
                    var $this = $(this);
                    var thisT = $this.offset().top;
                    var thisH = $this.height() / 2;
                    var thisP = thisT + thisH;
                    var event = 'load.'+i+' scroll.'+i;
                    $(window).on(event, function(){
                        windowT = $(window).scrollTop() + $(window).outerHeight();
                        if(windowT > thisP){
                            $this.addClass('n-active');
                            $(window).off(event);
                        }
                    });
                });
            }
        },
        windowPopup : function(_url, _width, _height, _left, _top){
            var popupX = _left != null ? _left : (window.screen.width / 2) - (_width / 2);
            var popupY = _top != null ? _top : (window.screen.height / 2) - (_height / 2);	
            var option="resizable=no, scrollbas=yes,status=no,width="+_width+",height="+_height+",left="+popupX+",top="+popupY;
            window.open(_url, 'portData', option);          
        },
        checkBroswer : function(){
            var agent = navigator.userAgent.toLowerCase(),
                name = navigator.appName,
                browser = '';
         
            // MS 계열 브라우저를 구분
            if(name === 'Microsoft Internet Explorer' || agent.indexOf('trident') > -1 || agent.indexOf('edge/') > -1) {
                browser = 'ie';
                if(name === 'Microsoft Internet Explorer') { // IE old version (IE 10 or Lower)
                    agent = /msie ([0-9]{1,}[\.0-9]{0,})/.exec(agent);
                    browser += parseInt(agent[1]);
                } else { // IE 11+
                    if(agent.indexOf('trident') > -1) { // IE 11
                        browser += 11;
                    } else if(agent.indexOf('edge/') > -1) { // Edge
                        browser = 'edge';
                    }
                }
            } else if(agent.indexOf('safari') > -1) { // Chrome or Safari
                if(agent.indexOf('opr') > -1) { // Opera
                    browser = 'opera';
                } else if(agent.indexOf('chrome') > -1) { // Chrome
                    browser = 'chrome';
                } else { // Safari
                    browser = 'safari';
                }
            } else if(agent.indexOf('firefox') > -1) { // Firefox
                browser = 'firefox';
            }
            return browser;
        },
		fixedHeader : function(){
			var header = $(".Layout__header");
			$(window).on("scroll", function(){
				if( $(window).scrollTop() > 50) {
					header.addClass("isScroll");
				}else{
					header.removeClass("isScroll");
				}
			});
		},
        tab : function(_el){
            var tab = $("#"+_el);
            var tabLink = tab.find(".TabLink");
            var tabContent = tab.find(".TabContent");
            var hash = location.hash;
            return {
                activeIndex : 0,
                init : function(_index){
                    var that = this;
                    that.activeIndex = _index ;
                    if( hash ){
                        that.activeIndex = hash.replace("#", "");
                    }

                    that.open();
    
                    tabLink.children("a").on("click", function(){
                        that.activeIndex = $(this).attr("tabIndex");
                        console.log(that.activeIndex);
                        that.open();
                    });
                },
                open : function(){
                    var that = this;
                    tabContent.removeClass("active");
                    tabContent.eq(that.activeIndex).addClass("active");
                    location.hash = that.activeIndex;
                    tabLink.children('a').removeClass("active");
                    tabLink.children('a').eq(that.activeIndex).addClass("active");
                }
            }
        },            
    }
})();

//팝업
var uiPopup = (function(){
    return {
        open : function(_el){
            var thisObj = this;
            var popup = _el == null ? null : $("#"+_el);
            if( popup == null ) return;
            
            popup.addClass("active");
        },
        close : function(_el){
            var popup = _el == null ? null : $("#"+_el);
            if( popup == null ) return;
            popup.removeClass("active");
        }
    }
})();
