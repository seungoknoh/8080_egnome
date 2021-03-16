<!-- breadcrumb -->
<ol class="breadcrumb text-right">
    <li><a href="/baskS1te/main">Home</a></li>
    <li><a href="/baskS1te/boardSearchField">게시판 검색필드 관리</a></li>
    <li class="active">검색필드 관리</li>
</ol>
<!-- //breadcrumb -->
<?php 
	$attr = array('class' => 'form-horizontal', 'id'=>'frm');
	echo form_open('', $attr); 
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2>게시판관리<small> 게시판 검색필드 관리에 대한 설정을 적용할 수 있습니다.</small></h2>
    </div>
    <div class="panel-body" id="board-content">
        <?php if( $view == null ){?>
        <input type="hidden" name="bs_name_chk" id="bs_name_chk" value="" />
        <?php }else{ ?>
        <input type="hidden" name="bs_idx" value="<?php echo $view->bs_idx; ?>" />
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
                    <label for="bs_name" class="col-sm-2 control-label">검색필드명</label>
                    <div class="col-sm-10">
                        <?php if($view == null){ ?>
                        <div class="input-group">
                            <input type="text" id="bs_name" class="form-control" name="bs_name" />
                            <a href="javascript:;" class="input-group-addon" onclick="return checkDouble();">중복확인</a>
                        </div>
                        <?php }else{ ?>
                        <input type="text" class="form-control" name="bs_name"
                            value="<?php echo $view -> bs_name; ?>" />
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="op_table" class="col-sm-2 control-label">게시판이름</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-addon">선택</div>
                            <select id="op_table" class="form-control" name="op_table">
                                <option value="">게시판선택</option>
                                <?php 
								$is_selected = "";
								foreach( $list as $ls ){ 
									$is_selected = $view -> op_table == $ls -> op_table ? "selected" : "";
								?>
                                <option value="<?php echo $ls -> op_table; ?>" <?php echo $is_selected  ?>>
                                    <?php echo $ls -> op_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label for="bs_type" class="col-sm-2 control-label">검색필드 타입</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-addon">선택</div>

                            <?php if($view != null){ ?>
                            <select id="bs_type" class="form-control" name="bs_type">
                                <option value="">검색필드 타입</option>
                                <option value="year" <?php if($view -> bs_type == "year") echo "selected"; ?>>YEAR
                                </option>
                                <option value="analysis type"
                                    <?php if($view -> bs_type == "analysis type") echo "selected"; ?>>
                                    Analysis
                                    Type
                                </option>
                                <option value="species" <?php if($view -> bs_type == "species") echo "selected"; ?>>
                                    Species
                                </option>
                            </select>
                            <?php } else { ?>
                            <select id="bs_type" class="form-control" name="bs_type">
                                <option value="">검색필드 타입</option>
                                <option value="year">YEAR</option>
                                <option value="analysis type">Analysis Type</option>
                                <option value="species">Species</option>
                            </select>
                            <?php } ?>
                        </div>
                    </div>
                </div <!-- //board_write_area -->
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <!-- board_write_bottom -->
                        <div class="board_write_bottom clearfix">
                            <div class="text-right">
                                <a href="<?php echo $this->list_href.$this->query; ?>"
                                    class="btn btn-default btn-lg"><span class="glyphicon glyphicon-th-list"></span>
                                    목록</a>
                                <button type="button" id="btn-delete" class="btn btn-danger btn-lg"><span
                                        class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제</button>
                                <button type="button" id="btn-submit" class="btn btn-primary btn-lg"><span
                                        class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 수정</button>
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
            //form 전달
            $("button").on("click", function(e) {
                var frm = $("#frm");
                e.preventDefault();
                if ($(this).attr("id") == "btn-delete") {
                    if (confirm(" 삭제하시겠습니까? 전체 게시물 데이터가 삭제됩니다. ")) {
                        frm.attr("action", "/backS1te/boardSearchField/delete");
                        frm.submit();
                    }
                } else if ($(this).attr("id") == "btn-submit") {
                    <?php if($view == null){ ?>
                    var bs_name_chk = $("input[name='bs_name_chk']");
                    if (bs_name_chk.val() == "") {
                        alert("테이블명 중복확인이 필요합니다.");
                        return false;
                    }
                    <?php } ?>
                    //frm.attr("action", "/backS1te/boardSearchField/write");
                    frm.submit();
                }
            });
        });
        //테이블값 확인
        function checkDouble() {
            var bs_name = $('input[name="bs_name"]');
            var bs_name_chk = $('input[name="bs_name_chk"]');
            if (bs_name.val() != '') {
                $.ajax({
                    method: 'get',
                    url: "/backS1te/boardSearchField/check",
                    data: {
                        'bs_name': bs_name.val()
                    }
                }).done(function(data) {
                    if (data) alert('사용가능합니다.');
                    bs_name_chk.val(data);
                    $('input[name="bs_name"]').focus();
                });
            } else {
                alert("테이블명을 입력해주세요");
                bs_name.focus();
            }
            return false;
        }
        </script>