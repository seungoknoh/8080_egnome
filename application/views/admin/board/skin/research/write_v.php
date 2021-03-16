<!-- breadcrumb -->
<ol class="breadcrumb text-right">
    <li><a href="/baskS1te/main">Home</a></li>
    <li><a href="/baskS1te/member">게시판관리</a></li>
    <li class="active"><?php echo $this->option->op_name; ?></li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2><?php echo $this->option->op_name; ?></h2>
    </div>
    <div class="panel-body">
        <div id="alert-area">
            <?php foreach ($errors as $err){ ?>
            <div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
            <?php } ?>
        </div>
        <?php 
		    $attr = array('class' => 'form-horizontal', 'id'=>'frm', "enctype"=>"multipart/form-data");
			echo form_open('', $attr); 
		?>
        <?php if($view != null){ ?>
        <input type="hidden" name="bo_idx" id="bo_idx" value="<?php echo $view->bo_idx ?>" />
        <input type="hidden" name="bo_ref" id="bo_ref" value="<?php echo $view->bo_ref ?>" />
        <?php } ?>
        <input type="hidden" name="op_table" id="op_table" value="<?php echo $this->op_table ?>" />

        <!-- board_write_area -->
        <div class="board_write_area">
            <div class="board_write">
                <?php if( $this->option->op_is_category == 1 ){ ?>
                <div class="form-group">
                    <label for="bc_idx" class="col-sm-2 control-label">카테고리</label>
                    <div class="col-sm-10">
                        <select name="bc_idx" id="bc_idx" class="form-control">
                            <option value="">카테고리선택</option>
                            <?php foreach ( $this->category_list as $category ){?>
                            <?php 
    							$is_selected = "";
    							if( $view->bc_idx == $category->bc_idx ){
    							     $is_selected = "selected";
    							}
							?>
                            <option value="<?php echo $category->bc_idx ?>" <?php echo $is_selected ?>>
                                <?php echo $category->bc_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php } ?>

                <div class="form-group">

                    <!-- <label for="bo_lang" class="col-sm-2 control-label">언어선택</label> -->
                    <!-- <div class="col-sm-10">
						<select name="bo_lang" id="bo_lang" class="form-control" required>
							<option value="">선택</option>		
							<option value="ko" <?php //echo ft_set_value($view, 'bo_lang') == "ko" ? "selected" : "" ?>>국문</option>
							<option value="en" <?php //echo ft_set_value($view, 'bo_lang') == "en" ? "selected" : "" ?>>영문</option>
						</select>
    				</div> -->
                </div>
                <div class="form-group">
                    <label for="bo_is_view" class="col-sm-2 control-label">사용여부</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label><input type="checkbox" name="bo_is_view" id="bo_is_view" value="1"
                                    <?php echo $view != null ? ($view->bo_is_view == 1 ? "checked" : "") : ""; ?> /> 사용
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_subject" class="col-sm-2 control-label">제목</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_subject"
                            value="<?php echo $view != null ? $view->bo_subject : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_level_write" class="col-sm-2 control-label">선택</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon">날짜</div>
                            <input type="text" class="form-control datePicker" name="bo_yearmd" id="datepicker1"
                                style="z-index:700" value="<?php echo $view != null ? $view -> bo_yearmd: ""; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">Analysis Type</div>
                            <select name="bo_scf1" id="bo_scf1" class="form-control" required>
                                <option value="" selected="selected">선택</option>
                                <?php 
								$is_selected = "";
								foreach( $analtype as $ls ){ 
									$is_selected = $view -> bo_scf1 == $ls -> bs_name ? "selected" : "";
                                ?>
                                <option value="<?php echo $ls -> bs_name; ?>" <?php echo $is_selected  ?>>
                                    <?php echo $ls -> bs_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon">Species</div>
                            <select name="bo_scf2" id="bo_scf2" class="form-control" required>
                                <option value="" selected="selected">선택</option>
                                <?php 
								$is_selected = "";
								foreach( $species as $ls ){ 
									$is_selected = $view -> bo_scf2 == $ls -> bs_name ? "selected" : "";
                                ?>
                                <option value="<?php echo $ls -> bs_name; ?>" <?php echo $is_selected  ?>>
                                    <?php echo $ls -> bs_name; ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_url" class="col-sm-2 control-label">사이트링크1</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_url"
                            value="<?php echo $view != null ? $view->bo_url : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_url2" class="col-sm-2 control-label">사이트링크2</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_url2"
                            value="<?php echo $view != null ? $view->bo_url2 : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_content" class="col-sm-2 control-label">내용</label>
                    <div class="col-sm-10">
                        <?php create_editor("bo_content", $view == null ? "" : $view->bo_content); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_caption" class="col-sm-2 control-label">캡션</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_caption"
                            value="<?php echo $view != null ? $view->bo_caption : ""; ?>" />
                    </div>
                </div>
                <!-- 썸네일 -->
                <div class="form-group">
                    <label for="bo_file" class="col-sm-2 control-label">썸네일</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label><input type="checkbox" name="bo_is_file_thumb" value="1"
                                    <?php echo $view == null ? "checked" : ""; ?><?php if( $view != null ) echo $view->bo_is_file_thumb == 1 ? "checked" : ""; ?> />
                                등록된 파일을 썸네일로 정할시 체크해주세요. (이미지파일만 해당)</label>
                        </div>
                    </div>
                </div>
                <!-- //썸네일 -->

                <!-- 파일업로드 -->
                <div class="form-group">
                    <label for="bo_file" class="col-sm-2 control-label">파일</label>
                    <div class="col-sm-10">
                        <div>
                            <label class="checkbox">
                                <input type="file" name="bf_file[]" class="custom-file-input"
                                    style="display:inline-block">
                            </label>
                        </div>
                        <ul id="upload-list" class="upload_list clear">
                            <?php foreach ($fileList as $list){ ?>
                            <li>
                                <label><input type='checkbox' name='bf_delete[]' value='<?php echo $list->bf_idx ?>' />
                                    <span> <?php echo $list->bf_source ?> </span> (삭제 시 선택)
                                    <span><?php echo ($list->bf_filesize/1000000)."MB" ?></span>
                                </label>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <!-- //파일업로드 -->
            </div>
        </div>
        <!-- //board_write_area -->
        </form>
    </div>
    <!-- //board_footer -->
    <div class="panel-footer">
        <div class="board_write_bottom clearfix">
            <div class="text-right">
                <a href="<?php echo $this -> list_href ?>" class="btn btn-default btn-lg"><span
                        class="glyphicon glyphicon-th-list"></span> 목록</a>
                <?php if($this -> action == "update"){ ?>
                <a id="btn-delete" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-trash"></span> 삭제</a>
                <a id="btn-submit" type="submit" class="btn btn-primary btn-lg"><span
                        class="glyphicon glyphicon-pencil"></span> 수정</a>
                <?php } ?>
                <?php if($this -> action == "write"){ ?>
                <a id="btn-submit" type="submit" class="btn btn-primary btn-lg"><span
                        class="glyphicon glyphicon-pencil"></span> 등록</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- board_footer -->
</div>

<script>
$(function() {
    $("#datepicker1").datepicker("option", "dateFormat", "yy-m-d");
});

$(document).ready(function() {
    search.init();
    //에디터
    <?php update_editor("bo_content"); ?>

    //수정, 등록
    $("#btn-submit").on("click", function(e) {
        e.preventDefault();
        $("#frm").submit();
    });

    <?php if($this->action == "update"){ ?>
    //삭제
    $("#btn-delete").on("click", function(e) {
        e.preventDefault();
        if (confirm("삭제하시겠습니까?")) {
            $("#frm").attr("action", "/backS1te/board/delete/<?php echo $this->op_table; ?>");
            $("#frm").submit();
        }
    });
    <?php } ?>
});
var search = {
    url: "/backS1te/boardAjax",
    init: function() {
        var thisObj = this;
        //검색옵션설정
        $("#btn-search").on("click", function(e) {
            e.preventDefault();
            thisObj.searchGET();
        });
    },
    //검색옵션설정
    searchGET: function() {
        var thisObj = this;
        $.ajax({
            method: "GET",
            dataType: "html",
            url: thisObj.url + "/json_search",
            success: function(data) {
                $("#modal-content").empty().append(data);
                $("#modal-content").modal('show');

                //검색옵션 수정
                $("#btn-search_submit").on("click", function(e) {
                    e.preventDefault();
                    thisObj.searchPOST();
                });
                //검색옵션 추가
                // $("#btn-add-search").on("click", function(e){
                // 	alert("aaaaaaaaaa");
                // 	e.preventDefault();
                // 	thisObj.searchAddPOST();
                // });

                //form 전달
                // $("bc_delete").on("click", function(e){
                // var frm = $("#frmSearch");
                // e.preventDefault();
                // if(confirm(" 삭제하시겠습니까? 전체 게시물 데이터가 삭제됩니다. ")){
                // 	frm.attr("action", "/backS1te/boardAjax/delete");
                // 	frm.submit();
                // 					//검색필드 변경때문에 다시 리로드 필요.
                // //window.location.reload();
                // }

            }
        });
    },
    searchPOST: function() {
        var thisObj = this;
        $.ajax({
            type: "POST",
            data: $("#frmSearch").serialize(),
            url: thisObj.url + "/search_update",
            dataType: "json"
        }).done(function(data) {
            $("#modal-content").modal('hide');
            alert(data.data);
            thisObj.init();
            //검색필드 변경때문에 다시 리로드 필요.
            //window.location.reload();
        });
    },
    searchAddPOST: function() {
        var thisObj = this;
        $.ajax({
            type: "POST",
            data: $("#frmSearch").serialize(),
            contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
            url: thisObj.url + "/search_insert",
            dataType: "json"
        }).done(function(data) {
            $("#modal-content").modal('hide');
            alert(data.data);
            thisObj.init();
            //검색필드 변경때문에 다시 리로드 필요.
            //window.location.reload();
        });
    }
}
</script>