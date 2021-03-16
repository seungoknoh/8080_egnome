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
    <div class="panel-body" id="board-content">
        <div id="alert-area">
            <?php foreach ($errors as $err){ ?>
            <div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
            <?php } ?>
        </div>
        <!-- board_write_area -->
        <div class="board_write_area form-horizontal">
            <?php 
    			$attr = array('id'=>'frm', "enctype"=>"multipart/form-data" );
    			echo form_open('', $attr); 
    		?>
            <input type="hidden" name="bo_idx" id="bo_idx" value="<?php echo $view == null ? "" : $view->bo_idx ?>" />
            <input type="hidden" name="bo_ref" id="bo_ref" value="<?php echo $view == null ? "" : $view->bo_ref ?>" />
            <input type="hidden" name="op_table" id="op_table" value="<?php echo $this->op_table ?>" />

            <?php if( $is_reply ){ ?>
            <input type="hidden" name="bc_idx" value="<?php echo $reply->bc_idx ?>" />
            <input type="hidden" name="bo_parent" id="bo_parent" value="<?php echo $bo_parent ?>" />
            <input type="hidden" name="bo_ref" id="bo_ref" value="<?php echo $reply->bo_ref ?>" />
            <input type="hidden" name="bo_child" id="bo_child" value="<?php echo $reply->bo_child ?>" />
            <input type="hidden" name="bo_level" id="bo_level"
                value="<?php echo $reply->bo_level ?><?php echo (($reply->bo_child)+1)*10 ?>" />
            <?php } ?>

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
                    <label for="bo_is_view" class="col-sm-2 control-label">사용여부</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label><input type="checkbox" name="bo_is_view" id="bo_is_view" value="1"
                                    <?php echo $view == null ? "checked" : ""; ?><?php if( $view != null ) echo $view->bo_is_view == 1 ? "checked" : ""; ?> />
                                사용 </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_subject" class="col-sm-2 control-label">제목</label>
                    <div class="col-sm-10">
                        <?php if($is_reply){ ?>
                        <input type="text" class="form-control" name="bo_subject"
                            value="<?php echo $reply == null ? "" : "RE:".$reply->bo_subject ?>" />
                        <?php }else{ ?>
                        <input type="text" class="form-control" name="bo_subject"
                            value="<?php echo $view == null ? "" : $view->bo_subject ?>" />
                        <?php } ?>
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
                    <label for="op_name" class="col-sm-2 control-label">내용</label>
                    <div class="col-sm-10">
                        <?php create_editor("bo_content", $view == null ? "" : $view->bo_content); ?>
                    </div>
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
            <div class="board_file">
                <div class="form-group">
                    <label for="bo_file" class="col-sm-2 control-label">파일</label>
                    <div class="col-sm-10">
                        <div><label class="checkbox"><input type="file" name="bf_file[]" class="custom-file-input"
                                    style="display:inline-block"></label></div>
                        <ul id="upload-list" class="upload_list clear pt20">
                            <?php foreach ($fileList as $list){ ?>
                            <li>
                                <label><input type='checkbox' name='bf_delete[]' value='<?php echo $list->bf_idx ?>' />
                                    (삭제 시 선택해주세요.)
                                    <span> <?php echo $list->bf_source ?> </span>
                                    <span><?php echo ($list->bf_filesize/1000000)."MB" ?></span>
                                </label>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- //파일업로드 -->
            <?php echo form_close() ?>
        </div>
        <!-- //board_write_area -->
    </div>
    <div class="panel-footer">
        <!-- board_write_bottom -->
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
        <!-- //board_write_bottom -->
    </div>
</div>

<script>
$(document).ready(function() {
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
</script>