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
        <!-- board-top -->
        <div class="text-center board-top">
            <div class="well mt20">
                <form class="form-inline" action="<?php echo $this->list_href; ?>" method="get"
                    onsubmit="return boardSearch(this)">
                    <input type="hidden" name="sca" value="<?php echo $this->sca ?>" />
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                <span class="skip">검색</span>
                            </div>
                            <select class="form-control" name="sfl" id="sfl">
                                <option value="bo_subject" <?php if($this -> sfl == 'bo_subject') echo "selected"; ?>>제목
                                </option>
                                <option value="bo_content" <?php if($this -> sfl == 'bo_content') echo "selected"; ?>>내용
                                </option>
                                <option value="bo_writer" <?php if($this -> sfl == 'bo_writer') echo "selected"; ?>>작성자
                                </option>
                            </select>
                            <!-- <select class="form-control" name="bo_year" id="bo_year" width="5">
                                <option value="" selected="selected">Year</option>
                                <option value="2021" <?php //if($this -> bo_year == '2021') echo "selected"; ?>>2021
                                </option>
                                <option value="2020" <?php //if($this -> bo_year == '2020') echo "selected"; ?>>2020
                                </option>
                                <option value="2019" <?php //if($this -> bo_year == '2019') echo "selected"; ?>>2019
                                </option>
                                <option value="2018" <?php //if($this -> bo_year == '2018') echo "selected"; ?>>2018
                                </option>
                                <option value="2017" <?php //if($this -> bo_year == '2017') echo "selected"; ?>>2017
                                </option>
                                <option value="2016" <?php //if($this -> bo_year == '2016') echo "selected"; ?>>2016
                                </option>
                                <option value="2015" <?php //if($this -> bo_year == '2015') echo "selected"; ?>>2015
                                </option>
                                <option value="2014" <?php //if($this -> bo_year == '2014') echo "selected"; ?>>2014
                                </option>
                                <option value="2013" <?php //if($this -> bo_year == '2013') echo "selected"; ?>>2013
                                </option>
                                <option value="2012" <?php //if($this -> bo_year == '2012') echo "selected"; ?>>2012
                                </option>
                                <option value="2011" <?php //if($this -> bo_year == '2011') echo "selected"; ?>>2011
                                </option>
                                <option value="2010" <?php //if($this -> bo_year == '2010') echo "selected"; ?>>2010
                                </option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="bo_scf1" id="bo_scf1" width="8">
                            <option value="" selected="selected">Analysis Type</option>
                            <option value="Anal_type1"
                                <?php //if($this -> bo_scf1 == 'Anal_type1') echo "selected"; ?>>
                                Genomics
                            </option>
                            <option value="Anal_type2"
                                <?php //if($this -> bo_scf1 == 'Anal_type2') echo "selected"; ?>>
                                Transcriptomics
                            </option>
                            <option value="Anal_type3"
                                <?php //if($this -> bo_scf1 == 'Anal_type3') echo "selected"; ?>>
                                Microbiomics
                            </option>
                            <option value="Anal_type4"
                                <?php //if($this -> bo_scf1 == 'Anal_type4') echo "selected"; ?>>
                                EpiGenomics
                            </option>
                            <option value="Anal_type5"
                                <?php //if($this -> bo_scf1 == 'Anal_type5') echo "selected"; ?>>
                                Software
                            </option>
                            <option value="Anal_type6"
                                <?php //if($this -> bo_scf1 == 'Anal_type6') echo "selected"; ?>>
                                etc.
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="bo_scf2" id="bo_scf2" width="5">
                            <option value="" selected="selected">Species</option>
                            <option value="Species1" <?php //if($this -> bo_scf2 == 'Species1') echo "selected"; ?>>
                                Animal
                            </option>
                            <option value="Species2" <?php //if($this -> bo_scf2 == 'Species2') echo "selected"; ?>>
                                Plant
                            </option>
                            <option value="Species3" <?php //if($this -> bo_scf2 == 'Species3') echo "selected"; ?>>
                                Insect
                            </option>
                            <option value="Species4" <?php //if($this -> bo_scf2 == 'Species4') echo "selected"; ?>>
                                Microorganism</option>
                            <option value="Species5" <?php //if($this -> bo_scf2 == 'Species5') echo "selected"; ?>>
                                Virus
                            </option>
                            <option value="Species6" <?php //if($this -> bo_scf2 == 'Species6') echo "selected"; ?>>
                                Human
                            </option>
                            <option value="Species7" <?php //if($this -> bo_scf2 == 'Species7') echo "selected"; ?>>Cat
                            </option>
                            <option value="Species8" <?php //if($this -> bo_scf2 == 'Species8') echo "selected"; ?>>
                                Chimpanzee</option>
                            <option value="Species9" <?php //if($this -> bo_scf2 == 'Species9') echo "selected"; ?>>Cow
                            </option>
                            <option value="Species10" <?php //if($this -> bo_scf2 == 'Species10') echo "selected"; ?>>
                                Dog
                            </option>
                            <option value="Species11" <?php //if($this -> bo_scf2 == 'Species11') echo "selected"; ?>>
                                Dolphin</option>
                            <option value="Species12" <?php //if($this -> bo_scf2 == 'Species12') echo "selected"; ?>>
                                Duck
                            </option>
                            <option value="Species13" <?php //if($this -> bo_scf2 == 'Species13') echo "selected"; ?>>
                                Goat
                            </option>
                            <option value="Species14" <?php //if($this -> bo_scf2 == 'Species14') echo "selected"; ?>>
                                Horse
                            </option>
                            <option value="Species15" <?php //if($this -> bo_scf2 == 'Species15') echo "selected"; ?>>
                                Macaque</option>
                            <option value="Species16" <?php //if($this -> bo_scf2 == 'Species16') echo "selected"; ?>>
                                Mouse
                            </option>
                            <option value="Species17" <?php //if($this -> bo_scf2 == 'Species17') echo "selected"; ?>>
                                Opossum</option>
                            <option value="Species18" <?php //if($this -> bo_scf2 == 'Species18') echo "selected"; ?>>
                                Orangutan</option>
                            <option value="Species19" <?php //if($this -> bo_scf2 == 'Species19') echo "selected"; ?>>
                                Panda
                            </option>
                            <option value="Species20" <?php //if($this -> bo_scf2 == 'Species20') echo "selected"; ?>>
                                Pig
                            </option>
                            <option value="Species21" <?php //if($this -> bo_scf2 == 'Species21') echo "selected"; ?>>
                                Platypus</option>
                            <option value="Species22" <?php //if($this -> bo_scf2 == 'Species22') echo "selected"; ?>>
                                Rat
                            </option>
                            <option value="Species23" <?php //if($this -> bo_scf2 == 'Species23') echo "selected"; ?>>
                                Tasmanian Devil</option>
                            <option value="Species24" <?php //if($this -> bo_scf2 == 'Species24') echo "selected"; ?>>
                                Whale
                            </option>
                            <option value="Species25" <?php //if($this -> bo_scf2 == 'Species25') echo "selected"; ?>>
                                Bird
                            </option>
                            <option value="Species26" <?php //if($this -> bo_scf2 == 'Species26') echo "selected"; ?>>
                                Chicken</option>
                            <option value="Species27" <?php //if($this -> bo_scf2 == 'Species27') echo "selected"; ?>>
                                Quail
                            </option>
                        </select> -->
                        </div>
                        <div class="form-group">
                            <label for="stx" class="skip">검색어입력</label>
                            <input type="text" class="form-control" placeholder="검색어입력" name="stx" id="stx"
                                value="<?php echo $this -> stx;?>">
                        </div>
                        <div class="form-group clearfix">
                            <button type="submit" class="col-xs-12 btn btn-primary">검색</button>
                        </div>
                        <?php if( $this->stx != "" ){ ?>
                        <div class="form-group">
                            <a href="<?php echo $this->list_href ?>" class="col-xs-12 btn btn-default">목록</a>
                        </div>
                        <?php } ?>
                </form>
            </div>
        </div>
        <!-- //board-top -->
        <!-- button_area -->
        <div class="button_area text-right mt30">
            <a class="btn <?php echo $this->sst=='bo_regdate' ? "btn-info" : "btn-default"; ?>"
                href="<?=ADM_DIR?>/board/index/<?php echo $this->op_table; ?><?=$q?>&sst=bo_regdate&sod=desc"><span
                    class="glyphicon glyphicon-sort-by-attributes-alt"></span> 최신순</a>
            <a class="btn <?php echo $this->sst=='bo_subject' ? "btn-info" : "btn-default"; ?>"
                href="<?=ADM_DIR?>/board/index/<?php echo $this->op_table; ?><?=$q?>&sst=bo_subject&sod=desc"><span
                    class="glyphicon glyphicon-sort-by-attributes-alt"></span> 이름순</a>
        </div>
        <!-- //button_area -->
    </div>
    <div class="panel-body" id="board-content">
        <p class="text-left">총 게시물 수 <strong><?php echo $total ?></strong> </p>
        <!-- board_cate_area -->
        <?php if($this->option->op_is_category == 1){ ?>
        <div class="board_cate_area clearfix">
            <a href="?sca="
                class="col-xs-3 col-md-2 btn <?php echo $this->sca == "" ? "btn-primary": "btn-default" ?>">전체</a>
            <?php foreach ($category_list as $category){ 
        	   $is_selected = "btn-default";
        	   if($category->bc_idx == $this->sca ){
        	       $is_selected = "btn-primary";
        	   }
        	?>
            <a href="?sca=<?php echo $category -> bc_idx ?>"
                class="col-xs-3 col-md-2 btn <?php echo $is_selected ?>"><?php echo $category -> bc_name ?></a>
            <?php } ?>
        </div>
        <?php } ?>
        <!-- //board_cate_area -->
        <!-- board-list -->
        <div class="table-responsive board-list mt10">
            <table class="table table-bordered table-striped table-hover table-center">
                <colgroup>
                    <col style="width:80px" />
                    <col />
                    <col style="width:120px" />
                    <col style="width:120px" />
                    <col style="width:80px" />
                    <col style="width:80px" />
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">제목</th>
                        <th scope="col">작성자</th>
                        <th scope="col">등록일</th>
                        <th scope="col">조회수</th>
                        <th scope="col">사용</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $lt){ ?>
                    <tr>
                        <td><?php echo $lt -> num; ?></td>
                        <td class="text-left">
                            <a class="link" href="<?php echo $lt -> link; ?>">
                                <?php if( isset($lt->bc_name) ) echo "[{$lt -> bc_name}]"; ?>
                                <?php echo $lt -> bo_subject; ?>
                            </a>
                        </td>
                        <td><?php echo $lt -> bo_writer; ?></td>
                        <td><?php echo date("Y-m-d", strtotime($lt -> bo_regdate)); ?></td>
                        <td><?php echo $lt-> bo_hit ?></td>
                        <td><?php echo $lt-> bo_is_view ? '<span style="color:#337ab7" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span><span class="skip">ON</span>' : '<span style="color:#da2724" class="glyphicon glyphicon-eye-close" aria-hidden="true"></span><span class="skip">OFF</span>' ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if( count($list) == 0 ) echo "<tr><td colspan='6'>no data</td></tr>"; ?>
                </tbody>
            </table>
        </div>
        <!-- //board-list -->
        <!-- pagination -->
        <nav class="text-center">
            <ul class="pagination">
                <?php echo $pagination; ?>
            </ul>
        </nav>
        <!-- pagination -->
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12 text-right">
                <a href="<?php echo $this -> write_href; ?>" id="btn-write" class="btn-lg btn btn-primary"><span
                        class="glyphicon glyphicon-pencil"></span> Write</a>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
var category = {
    input: [],
    modal: function() {
        var objThis = this;
        objThis.input = [];
        var chk = $("input[name='bo_idx[]']:checked");
        if (chk.size() > 0) {
            $('#categoryModal').modal('show');
            chk.each(function(i) {
                objThis.input[i] = $(this).val();
            });
        } else {
            alert("카테고리를 수정 할 리스트를 하나이상 선택해주세요.");
        }
    },
    update: function() {
        var objThis = this;
        $("input[name='chk_bo_idxs']").val(objThis.input.join(","));
        $("#cateFrm").submit();
    }
}

//카테고리수정
$("#btn-category-modify").on("click", function() {
    if ($("input[name='bo_idx[]']:checked").size() > 0) {
        $('#categoryModal').modal('show');
    } else {
        alert("카테고리를 수정 할 리스트를 하나이상 선택해주세요.");
    }
});

function boardSearch(f) {
    var action = f.action;
    if (f.stx.value == '') {
        alert("검색어를 입력해주세요");
        f.stx.focus();
        return false;
    }
    f.action = action;
    return true;
}
</script>