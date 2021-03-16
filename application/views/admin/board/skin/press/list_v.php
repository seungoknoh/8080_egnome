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
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stx" class="skip">검색어입력</label>
                        <input type="text" class="form-control" placeholder="검색어입력" name="stx" id="stx"
                            value="<?php echo $this -> stx;?>">
                    </div>
                    <div class="form-group clearfix">
                        <button type="submit" class="col-xs-12 btn btn-primary">검색</button>
                    </div>
                    <?php if($this->stx){ ?>
                    <div class="form-group">
                        <a href="<?php echo $list_href ?>" class="col-xs-12 btn btn-default">목록</a>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
        <!-- //board-top -->
    </div>
    <div class="panel-body" id="board-content">
        <p class="text-left">총 게시물 수 <strong><?php echo $total ?></strong> </p>
        <!-- board_cate_area -->
        <?php if($this->option->op_is_category == 1){ ?>
        <div class="board_cate_area clearfix">
            <a href="?sca="
                class="col-xs-6 col-md-2 btn <?php echo $this->sca == "" ? "btn-primary": "btn-default" ?>">전체</a>
            <?php foreach ($category_list as $category){ 
        	   $is_selected = "btn-default";
        	   if($category->bc_idx == $this->sca ){
        	       $is_selected = "btn-primary";
        	   }
        	?>
            <a href="?sca=<?php echo $category -> bc_idx ?>"
                class="col-xs-6 col-md-2 btn <?php echo $is_selected ?>"><?php echo $category -> bc_name ?></a>
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