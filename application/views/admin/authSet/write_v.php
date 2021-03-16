<!-- breadcrumb -->
<ol class="breadcrumb text-right">
    <li><a href="/baskS1te/main">Home</a></li>
    <li><a href="/baskS1te/member">환경설정</a></li>
    <li class="active">권한관리추가</li>
</ol>
<?php 
	$attr = array('class' => 'form-horizontal', 'id'=>'frm');
	echo form_open('', $attr); 
?>
<!-- //breadcrumb -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2>권한관리추가<small> 권한에 대한 설정을 적용할 수 있습니다.</small></h2>
    </div>
    <div class="panel-body">
        <?php if( $view != null ){?>
        <input type="hidden" name="au_idx" value="<?php echo $view->au_idx; ?>" />
        <input type="hidden" name="mb_id" value="<?php echo $view->mb_id; ?>" />
        <?php } ?>
        <div id="alert-area">
            <?php foreach ($errors as $err){ ?>
            <div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
            <?php } ?>
        </div>
        <!-- board_write_area -->
        <div class="board_write_area">
            <p class="bg-info notice-text">관리자만 접근권한을 설정할 수 있습니다.</p>
            <div class="board_write">
                <div class="form-group">
                    <label for="mb_id" class="col-sm-2 control-label">관리자 아이디</label>
                    <div class="col-sm-10">
                        <?php if($view == null){ ?>
                        <select name="mb_id" id="mb_id" class="form-control">
                            <option value="">선택하세요</option>
                            <?php foreach($admin_list as $lt){?>
                            <option value="<?php echo $lt -> mb_id ?>"><?php echo $lt -> mb_id ?></option>
                            <?php } ?>
                        </select>
                        <?php }else{ ?>
                        <div class="checkbox">
                            <?php echo $view->mb_id; ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="mb_name" class="col-sm-2 control-label">권한지정</label>
                    <div class="col-sm-10">
                        <div class="well auth_list">
                            <div class="row clear">
                                <?php 
							$i=0;
							foreach ( $menu_list as $lt ){
							    if( strlen($lt -> mn_code) == 2 ){
							        if($i != 0) echo "<div class='clearfix'></div>";
							        echo "<h4 class='text_title'>".$lt->mn_name."</h4>";
							        continue;
							    }
							    if( $view != null ){
							        $mn_idx_arr = explode(",", trim($view->mn_idxs));
							        $is_checkbox = in_array($lt -> mn_idx, $mn_idx_arr) == true ? "checked" : "" ;
							    }
							?>
                                <div class="col-md-4">
                                    <label>
                                        <input type="checkbox" name="mn_idx[]" value="<?php echo $lt -> mn_idx ?>"
                                            <?php echo $view != null ? $is_checkbox : ""; ?> />
                                        <span><?php echo $lt -> mn_name ?></span>
                                    </label>
                                </div>
                                <?php
							    $i++;
							} 
							?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- //board_write_area -->
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12 text-right">
                <!-- board_write_bottom -->
                <div class="board_write_bottom clearfix">
                    <div class="text-left col-sm-6">
                        <?php if( $view != null ){ ?>
                        <button id="btn-delete" type="button" class="btn btn-danger btn-lg"><span
                                class="glyphicon glyphicon-trash"></span> 삭제</button>
                        <?php } ?>
                    </div>

                    <div class="text-right col-sm-6">
                        <a href="<?php echo $this->list_href; ?>" class="btn btn-default btn-lg"><span
                                class="glyphicon glyphicon-th-list"></span> 목록</a>
                        <button id="btn-submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil"
                                aria-hidden="true"></span> 입력</button>
                    </div>
                </div>
                <!-- //board_write_bottom -->
            </div>
        </div>
    </div>
</div>
</form>
<script>
$(document).ready(function() {
    //수정, 등록
    $("#btn-submit").on("click", function(e) {
        e.preventDefault();
        $("#frm").submit();
    });

    <?php if($view != null){ ?>
    //삭제
    $("#btn-delete").on("click", function(e) {
        e.preventDefault();
        if (confirm("삭제하시겠습니까?")) {
            $("#frm").attr("action", "/backS1te/authSet/delete");
            $("#frm").submit();
        }
    });
    <?php } ?>
});
</script>