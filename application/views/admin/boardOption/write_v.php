<!-- breadcrumb -->
<ol class="breadcrumb text-right">
    <li><a href="/baskS1te/main">Home</a></li>
    <li><a href="/baskS1te/member">게시판관리</a></li>
    <li class="active">게시판관리</li>
</ol>
<!-- //breadcrumb -->
<?php 
	$attr = array('class' => 'form-horizontal', 'id'=>'frm');
	echo form_open('', $attr); 
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2>게시판관리<small> 게시판에 대한 설정을 적용할 수 있습니다.</small></h2>
    </div>
    <div class="panel-body" id="board-content">
        <?php if( $view == null ){?>
        <input type="hidden" name="op_table_chk" id="op_table_chk" value="" />
        <?php }else{ ?>
        <input type="hidden" name="op_idx" value="<?php echo $view->op_idx; ?>" />
        <?php } ?>
        <?php if( $errors != null){ ?>
        <div id="alert-area">
            <?php foreach ($errors as $err){ ?>
            <div class="alert alert-warning" role="alert"><?php echo $err ?></div>
            <?php } ?>
        </div>
        <?php }?>
        <!-- board_write_area -->
        <div class="board_write_area">
            <div class="board_write">
                <div class="form-group">
                    <label for="op_table" class="col-sm-2 control-label">테이블명</label>
                    <div class="col-sm-10">
                        <?php if($view == null){ ?>
                        <div class="input-group">
                            <input type="text" class="form-control" name="op_table" />
                            <a href="javascript:;" class="input-group-addon" onclick="return checkDouble();">중복확인</a>
                        </div>
                        <?php }else{ ?>
                        <input type="text" class="form-control" name="op_table" value="<?php echo $view -> op_table; ?>"
                            readonly="readonly" />
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_name" class="col-sm-2 control-label">게시판이름</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="op_name" id="op_name"
                            value="<?php echo $view != null ? $view -> op_name : ""; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_cate" class="col-sm-2 control-label">카테고리</label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <div class="input-group-addon">카테고리 추가</div>
                            <input type="text" class="form-control" name="op_cate" id="op_cate" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="btn-add-category">추가</button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="op_is_category"
                                    <?php echo $view != null ? ( $view -> op_is_category == 1 ? "checked" : "" ) : ""; ?>>카테고리
                                사용
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">카테고리 리스트</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <ul class="list-group" id="list-category">
                                <?php 
							$i = 1;
							foreach ($category_list as $category){ ?>
                                <li class="list-group-item form-inline list-item">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="num"><?php echo $i ?></span>
                                            </div>
                                            <input type="hidden" name="bc_idx[]"
                                                value="<?php echo $category->bc_idx; ?>" />
                                            <input type="text" class="form-control" name="bc_update_name[]"
                                                value="<?php echo $category->bc_name; ?>" />
                                            <div class="input-group-addon">
                                                <label><input type="checkbox" name="bc_delete[]"
                                                        value="<?php echo $category->bc_idx; ?>" /><span>삭제</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php $i++; }?>
                                <?php if(is_countable($adm_skin_list) && count($category_list) == 0 ) echo "<li class='list-group-item form-inline' >등록된 카테고리가 없습니다.</li>";?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_skin" class="col-sm-2 control-label">스킨</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">사용자</div>
                            <select name="op_skin" id="op_skin" class="form-control">
                                <option value="">선택</option>
                                <?php 
								$is_selected = "";
								$cnt = is_null($skin_list) ? 0 : count($skin_list);
								for( $i= 0; $i<$cnt;$i++ ){ 
									$is_selected = $view -> op_skin == $skin_list[$i] ? "selected" : "";
								?>
                                <option value="<?php echo $skin_list[$i]; ?>" <?php echo $is_selected ?>>
                                    <?php echo $skin_list[$i]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">어드민</div>
                            <select name="op_adm_skin" id="op_adm_skin" class="form-control">
                                <option value="">선택</option>
                                <?php 
								$is_selected = "";
								$cnt = is_null($adm_skin_list) ? 0 : count($adm_skin_list);
								for( $i= 0; $i<$cnt;$i++ ){ 
									$is_selected = $view -> op_adm_skin == $adm_skin_list[$i] ? "selected" : "";
								?>
                                <option value="<?php echo $adm_skin_list[$i]; ?>" <?php echo $is_selected ?>>
                                    <?php echo $adm_skin_list[$i]; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">목록 설정</label>
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="op_is_view_category"
                                    <?php echo $view != null ? ($view -> op_is_view_category == 1 ? "checked" : "") : ""; ?>>
                                카테고리 보임
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="op_is_preview"
                                    <?php echo $view != null ? ($view -> op_is_preview == 1 ? "checked" : "") : ""; ?>>
                                목록 내용보이기
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="op_is_file"
                                    <?php echo $view != null ? ($view -> op_is_file == 1 ? "checked" : "") : ""; ?>> 목록
                                파일보이기
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="mb_phone" class="col-sm-2 control-label">보기 설정</label>
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="op_is_secret"
                                    <?php echo $view != null ? ( $view -> op_is_secret == 1 ? "checked" : "" ) : ""; ?>>
                                비밀글사용
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="op_is_ip"
                                    <?php echo $view != null ? ($view -> op_is_ip == 1 ? "checked" : "") : ""; ?>> IP사용
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_new_date" class="col-sm-2 control-label">새글시간</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="op_new_date"
                            value="<?php echo $view != null ? $view -> op_new_date : "24"; ?>" />
                    </div>
                    <label for="op_page_rows" class="col-sm-2 control-label">글 갯수</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="op_page_rows"
                            value="<?php echo $view != null ? $view -> op_page_rows : "10"; ?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="op_level_list" class="col-sm-2 control-label">보기권한</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon">목록</div>
                            <select id="op_level_list" class="form-control" name="op_level_list">
                                <option value="">글 목록 권한</option>
                                <?php 
								$is_selected = "";
								foreach( $level as $ls ){ 
									$is_selected = $view -> op_level_list == $ls -> ml_idx ? "selected" : "";
								?>
                                <option value="<?php echo $ls -> ml_idx; ?>" <?php echo $is_selected  ?>>
                                    <?php echo $ls -> ml_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon">보기</div>
                            <select id="op_level_view" class="form-control" name="op_level_view">
                                <option value="">글 보기 권한</option>
                                <?php 
								$is_selected = "";
								foreach( $level as $ls ){ 
									$is_selected = $view -> op_level_view == $ls -> ml_idx ? "selected" : "";
								?>
                                <option value="<?php echo $ls -> ml_idx; ?>" <?php echo $is_selected  ?>>
                                    <?php echo $ls -> ml_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_level_write" class="col-sm-2 control-label">쓰기권한</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon">쓰기</div>
                            <select id="op_level_write" class="form-control" name="op_level_write">
                                <option value="">글 쓰기 권한</option>
                                <?php 
								$is_selected = "";
								foreach( $level as $ls ){ 
									$is_selected = $view -> op_level_write == $ls -> ml_idx ? "selected" : "";
								?>
                                <option value="<?php echo $ls -> ml_idx; ?>" <?php echo $is_selected  ?>>
                                    <?php echo $ls -> ml_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon">답변</div>
                            <select id="op_level_reply" class="form-control" name="op_level_reply">
                                <option value="">글 답변 권한</option>
                                <?php 
								$is_selected = "";
								foreach( $level as $ls ){ 
									$is_selected = $view -> op_level_reply == $ls -> ml_idx ? "selected" : "";
								?>
                                <option value="<?php echo $ls -> ml_idx; ?>" <?php echo $is_selected  ?>>
                                    <?php echo $ls -> ml_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-addon">댓글</div>
							<select id="op_level_comment" class="form-control" name="op_level_comment">
								<option value="">글 댓글 권한</option>
								<?php 
								$is_selected = "";
								foreach( $level as $ls ){ 
									$is_selected = $view -> op_level_comment == $ls -> ml_idx ? "selected" : "";
								?>
								<option value="<?php echo $ls -> ml_idx; ?>" <?php echo $is_selected  ?> ><?php echo $ls -> ml_name; ?></option>
								<?php } ?>
							</select>
						</div>
					</div> -->
                </div>
                <hr />
                <div class="form-group">
                    <label for="op_is_gallery" class="col-sm-2 control-label">갤러리</label>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="op_is_gallery"
                                    <?php echo $view != null ? ($view -> op_is_gallery == 1 ? "checked" : "") : ""; ?> />
                                사용
                            </label>
                        </div>
                    </div>
                    <label for="op_img_max_width" class="col-sm-2 control-label">이미지크기</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="op_img_max_width"
                            value="<?php echo $view != null ? $view -> op_img_max_width : "600"; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_img_max_width" class="col-sm-2 control-label">썸네일</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">넓이</div>
                            <input type="text" class="form-control" name="op_thumb_width"
                                value="<?php echo $view != null ? $view -> op_thumb_width : "300"; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">높이</div>
                            <input type="text" class="form-control" name="op_thumb_height"
                                value="<?php echo $view != null ? $view -> op_thumb_height : "300"; ?>" />
                        </div>
                    </div>
                </div>
                <?php if( $view != null ){ ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">등록일</label>
                    <div class="col-sm-10">
                        <div class="checkbox"><?php echo $view -> op_regdate; ?></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <!-- //board_write_area -->
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12 text-right">
                <!-- board_write_bottom -->
                <div class="board_write_bottom clearfix">
                    <div class="text-right">
                        <a href="<?php echo $this->list_href.$this->query; ?>" class="btn btn-default btn-lg"><span
                                class="glyphicon glyphicon-th-list"></span> 목록</a>
                        <button type="button" id="btn-delete" class="btn btn-danger btn-lg"><span
                                class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제</button>
                        <button type="button" id="btn-submit" class="btn btn-primary btn-lg"><span
                                class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 입력</button>
                    </div>
                </div>
                <!-- //board_write_bottom -->
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
$(document).ready(function() {
    //카테고리
    var category = new Category();
    category.init("", $("#list-category"));
    $("#btn-add-category").on('click', function(e) {
        e.preventDefault();
        category.add($("input[name='op_cate']"));
    });

    //form 전달
    $("button").on("click", function(e) {
        var frm = $("#frm");
        e.preventDefault();
        if ($(this).attr("id") == "btn-delete") {
            if (confirm(" 삭제하시겠습니까? 전체 게시물 데이터가 삭제됩니다. ")) {
                frm.attr("action", "/backS1te/boardOption/delete");
                frm.submit();
            }
        } else if ($(this).attr("id") == "btn-submit") {
            <?php if($view == null){ ?>
            var op_table_chk = $("input[name='op_table_chk']");
            if (op_table_chk.val() == "") {
                alert("테이블명 중복확인이 필요합니다.");
                return false;
            }
            <?php } ?>
            frm.submit();
        }
    });
});

//테이블값 확인
function checkDouble() {
    var op_table = $('input[name="op_table"]');
    var op_table_chk = $('input[name="op_table_chk"]');
    if (op_table.val() != '') {
        $.ajax({
            method: 'get',
            url: "/backS1te/boardOption/check",
            data: {
                'op_table': op_table.val()
            }
        }).done(function(data) {
            if (data) alert('사용가능합니다.');
            op_table_chk.val(data);
            $('input[name="op_table"]').focus();
        });
    } else {
        alert("테이블명을 입력해주세요");
        op_table.focus();
    }
    return false;
}
</script>