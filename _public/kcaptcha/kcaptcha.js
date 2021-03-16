$(function(){
    $(document).on( "click", "#captcha_reload", function(){
        $.ajax({
            type: 'POST',
            url: captcha_url+'/kcaptcha_session.php',
            cache: false,
            async: false,
            success: function(text) {
                $('#captcha_img').attr('src', captcha_url+'/kcaptcha_image.php?t=' + (new Date).getTime());
            }
        });
    });
    $("#captcha_reload").trigger("click");
});

// 출력된 캡챠이미지의 키값과 입력한 키값이 같은지 비교한다.
function chk_captcha()
{
    var captcha_result = false;
    var captcha_key = document.getElementById('captcha_key');
    $.ajax({
        type: 'POST',
        url: captcha_url+'/kcaptcha_result.php',
        data: {
            'captcha_key': captcha_key.value
        },
        cache: false,
        async: false,
        success: function(text) {
            captcha_result = text;
        }
    });

    if (!captcha_result) {
        alert('자동등록방지 입력 글자가 틀렸거나 입력 횟수가 넘었습니다.\n\n새로고침을 클릭하여 다시 입력해 주십시오.');
        captcha_key.select();
        captcha_key.focus();
        return false;
    }

    return true;
}