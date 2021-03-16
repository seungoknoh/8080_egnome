<div class="pt30"></div>
<h2 class="SubContent__title"><span>Big Data Management</span></h2>
<section class="SubContent">
    <div class="Layout__section">
        <div class="BoardView">
            <div class="BoardView__top center">
                <h2 class="BoardView__title"><span><?php echo $view -> bo_subject; ?></span></h2>
                <div class="BoardView__info">
                    <span><?php echo $view -> bc_name; ?></span>
                </div>
            </div>
            <div class="BoardView__body">
                <div class="DataArea__img">
                    <img src="<?php echo $big_file; ?>" alt="">
                </div>
                <div class="DataArea__text">
                    <div class="title"><span>project descriptions</span></div>
                    <div class="subtitle"><span><?php echo $view -> bo_subtitle; ?></span></div>
                    <div class="txt">
                        <p><?php echo $view -> bo_content; ?></p>
                    </div>
                </div>
                <div class="pt30"></div>
                <div class="DataArea__text">
                    <div class="title"><span>project details</span></div>
                    <div class="list">
                        <ul class="clear clearfix">
                            <li>
                                <div class="tit">Client</div>
                                <div class="data"><?php echo $view -> bo_client; ?></div>
                            </li>
                            <li>
                                <div class="tit">Company</div>
                                <div class="data"><?php echo $view -> bo_company; ?></div>
                            </li>
                            <li>
                                <div class="tit">Catagory</div>
                                <div class="data"><?php echo $view -> bc_name; ?></div>
                            </li>
                            <li>
                                <div class="tit">Date</div>
                                <div class="data"><?php echo date("Y년 m월 d일", strtotime($view -> bo_yearmd)); ?> - none
                                </div>
                            </li>
                            <li>
                                <div class="tit">Project URL</div>
                                <div class="data"><a href=""><?php echo $view -> bo_url; ?></a></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="pt30"></div>
            </div>
            <div class="BoardView__move">
                <div class="item empty">
                    <div class="tit prev"><span>이전글</span></div>
                    <?php if($view_list_prev != null) { ?>
                    <a href="/board/view/bigdata/<?php echo $view_list_prev -> bo_idx; ?>" class="link">
                        <span><?php echo $view_list_prev -> bo_subject; ?></span>
                    </a>
                    <?php } else {?>
                    <div class="link"><span>이전 게시물이 없습니다.</span></div>
                    <?php } ?>
                </div>
                <div class="item">
                    <div class="tit next"><span>다음글</span></div>
                    <?php if($view_list_next != null) { ?>
                    <a href="/board/view/bigdata/<?php echo $view_list_next -> bo_idx; ?>"
                        class="link"><span><?php echo $view_list_next -> bo_subject; ?></span>
                    </a>
                    <?php } else {?>
                    <div class="link"><span>다음 게시물이 없습니다.</span></div>
                    <?php } ?>
                </div>
            </div>
            <div class="pt30"></div>
            <div class="BoardView__button">
                <div class="right">
                    <a href="/board/category/bigdata" class="btn_basic"><span>목록</span></a>
                </div>
            </div>
            <div class="pt60"></div>
        </div>
    </div>
</section>