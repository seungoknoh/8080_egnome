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
                    <label for="bo_subject" class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_subject"
                            value="<?php echo $view != null ? $view->bo_subject : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_client" class="col-sm-2 control-label">Client</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_client"
                            value="<?php echo $view != null ? $view->bo_client : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_company" class="col-sm-2 control-label">Company</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_company"
                            value="<?php echo $view != null ? $view->bo_company : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_yearmd" class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">Start Date</div>
                            <input type="text" class="form-control datePicker" name="bo_yearmd" id="datepicker1"
                                style="z-index:700" value="<?php echo $view != null ? $view -> bo_yearmd: ""; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">End Date</div>
                            <input type="text" class="form-control datePicker" name="bo_yearmd2" id="datepicker2"
                                style="z-index:700" value="<?php echo $view != null ? $view -> bo_yearmd2: ""; ?>" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_url" class="col-sm-2 control-label">Project URL</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_url"
                            value="<?php echo $view != null ? $view->bo_url : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_subtitle" class="col-sm-2 control-label">project title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bo_subtitle"
                            value="<?php echo $view != null ? $view->bo_subtitle : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bo_content" class="col-sm-2 control-label">project descriptions</label>
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
$(document).ready(function() {
    $(function() {
        $("#datepicker1").datepicker("option", "dateFormat", "yy-m-d");
    });
    $(function() {
        $("#datepicker2").datepicker("option", "dateFormat", "yy-m-d");
    });
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