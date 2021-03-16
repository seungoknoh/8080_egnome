<!-- META TAG 설정 -->

<title><?php echo $this->title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="마이크로바이옴 분석 전문기업, (주)이지놈">
<meta name="author" content="(주)이지놈">
<!-- //META TAG 설정 -->

<!-- favicon -->
<link rel="shortcut icon" href="<?php echo IMG_DIR ?>/favicon/favicon.ico" type="image/x-icon" />

<link href="<?php echo CSS_DIR ?>/style.css" rel="stylesheet" type="text/css" />

<!--[if lt IE 9]>
	<script src="<?php echo JS_DIR ?>/html5.js"></script>
	<![endif]-->

<!-- javascript -->
<script type="text/javascript" src="<?php echo JS_DIR ?>/jquery-1.12.4.js"></script>
<script type="text/javascript" src="<?php echo JS_DIR ?>/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo JS_DIR ?>/slick.js"></script>
<script type="text/javascript" src="<?php echo JS_DIR ?>/swiper.min.js"></script>
<script type="text/javascript" src="<?php echo JS_DIR ?>/TweenMax.min.js"></script>
<script type="text/javascript" src="<?php echo JS_DIR ?>/ui.js"></script>
<!-- //javascript -->
<script id="spHTMLFormElementPrototypeScript">
(function() {
    try {
        var sp_old_HTMLFormElementPrototype_submit = HTMLFormElement.prototype.submit;
        HTMLFormElement.prototype.submit = function(AEvent) {
            try {
                var spEvent = document.createEvent('Event');
                spEvent.initEvent('sp_submit', true, true);
                this.dispatchEvent(spEvent);
            } catch (ErrorMessage) {
                console.error(
                    'spFormElementPrototype() Error sending "sp_submit" event from HTMLFormElement.prototype.submit: ' +
                    ErrorMessage);
            }
            sp_old_HTMLFormElementPrototype_submit.apply(this);
        };
    } catch (ErrorMessage) {
        console.error('spFormElementPrototype() Error attaching to HTMLFormElement.prototype.submit: ' +
            ErrorMessage);
    }

    try {
        if (typeof __doPostBack == 'function') {
            var sp_old__doPostBack = __doPostBack;
            __doPostBack = function(eventTarget, eventArgument) {
                try {
                    var spEvent = document.createEvent('Event');
                    spEvent.initEvent('sp_submit', true, true);
                    window.dispatchEvent(spEvent);
                } catch (ErrorMessage) {
                    console.error(
                        'spFormElementPrototype() Error sending "sp_submit" event from __doPostBack(): ' +
                        ErrorMessage);
                }
                sp_old__doPostBack(eventTarget, eventArgument);
            };
        }
    } catch (ErrorMessage) {
        console.error('spFormElementPrototype() Error attaching to __doPostBack(): ' + ErrorMessage);
    }
})();
</script>