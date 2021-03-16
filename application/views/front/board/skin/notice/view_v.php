                <!-- //ContentTop -->
                <div class="pt30"></div>
                <h2 class="SubContent__title"><span>공지사항</span></h2>
                <section class="SubContent">
                    <div class="Layout__section">
                        <div class="BoardView">
                            <div class="BoardView__top">
                                <h2 class="BoardView__title"><span><?php echo $view -> bo_subject; ?></span></h2>
                                <div class="BoardView__info">
                                    <span><?php echo date("Y.m.d", strtotime($view -> bo_regdate)); ?></span>
                                </div>
                            </div>
                            <div class="BoardView__href">
                                <ul class="clear clearfix">
                                    <li><a href="<?php echo $view -> bo_url ?>"
                                            class="link"><?php echo $view -> bo_url; ?></a>
                                    </li>
                                    <li><a href="<?php echo $view -> bo_url2 ?>"
                                            class="link"><?php echo $view -> bo_url2; ?></a>
                                    </li>
                                    <?php if( $view -> bo_url == null ) echo "<li>사이트링크가 존재하지 않습니다.</li>"; ?>
                                </ul>
                            </div>
                            <div class="pt10"></div>
                            <div class="BoardView__filelist">
                                <h3 class="title"><span>첨부파일</span></h3>
                                <ul class="clear clearfix">
                                    <?php foreach($fileList as $list){ ?>
                                    <li><a href="<?php echo $link_file ?>?bf_idx=<?php echo $list->bf_idx ?>&token=<?php echo $token ?>"
                                            class="link"><?php echo $list->bf_source ?></a></li>
                                    <?php } ?>
                                    <?php if( is_countable($fileList) && count($fileList) == 0 ) echo "<li>첨부파일이 존재하지 않습니다.</li>"; ?>
                                </ul>
                            </div>

                            <div class="BoardView__body">
                                <div class="editor">
                                    <p style="font-size:16px;"><?php echo $view->bo_content; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="pt30"></div>
                            <div class="BoardView__button center">
                                <a href="<?php echo $list_href.$this->query; ?>" class="btn_basic"><span>목록</span></a>
                            </div>
                            <div class="pt50"></div>
                        </div>
                    </div>
                </section>