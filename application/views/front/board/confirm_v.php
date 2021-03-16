<section class="BoardConfirm">
    <div class="BoardConfirm__inner">
        <?php echo error_output($errors); ?>
        <h1 class="title"><span>게시물 비밀번호 확인</span></h1>
        <?php 
            $attr = array('id'=>'frm', "enctype"=>"multipart/form-data" );
            echo form_open('', $attr); 
        ?>
        <input type="hidden" name="token" value="<?php echo get_token()?>" />
        <input type="hidden" name="action" value="<?php echo $action == null ?  set_value('action') : $action; ?>" />
            <div class="BoardConfirm__form">
                <div class="input"><input type="password" name="bo_passwd" title="비밀번호" placeholder="비밀번호작성" /></div>
                <a href="javascript:;" class="button__confirm" id="btn-confirm"><span>확인</span></a>
            </div>
        <?php echo form_close() ?>
        <div class="ButtonArea">
            <div class="text-center">
                <a href="javascript:;" class="button__common" id="btn-back"><span>뒤로가기</span></a>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
        //폼 전송
        $("#btn-confirm").on("click", function(e){
            e.preventDefault();
            $("#frm").submit();
        });
        $("#btn-back").on("click", function(e){
            e.preventDefault();
            window.history.back(-1);
        })
    });
</script>