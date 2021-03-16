<div class="pt30"></div>
<h2 class="SubContent__title"><span>연구성과</span></h2>
<section class="SubContent">
    <div class="Layout__section">
        <!-- //검색 ContentsSearch -->
        <div class="ContentsSearch">
            <form action="/<?php echo $this -> uri -> segment(1) ?>/lists/<?php echo $this->op_table; ?>" method="get"
                onsubmit="return boardSearch(this)">
                <legend class="skip">게시물 검색</legend>
                <input type="hidden" name="sca" value="<?php echo $this->sca ?>" />
                <div class="ContentsSearch__inner">
                    <select name="bo_year" id="bo_year" width="8">
                        <option value="" selected="selected">Year</option>
                        <?php 
                        $is_selected = "";
                        foreach( $year as $ls ){ 
                            $is_selected = $this -> bo_year == $ls -> bs_name ? "selected" : "";
                        ?>
                        <option value="<?php echo $ls -> bs_name; ?>" <?php echo $is_selected;  ?>>
                            <?php echo $ls -> bs_name; ?></option>
                        <?php } ?>
                    </select>
                    <select name="bo_scf1" id="bo_scf1" width="10">
                        <option value="" selected="selected">Analysis Type</option>
                        <?php 
                        $is_selected = "";
                        foreach( $analtype as $ls ){ 
                            $is_selected = $this -> bo_scf1 == $ls -> bs_name ? "selected" : "";
                        ?>
                        <option value="<?php echo $ls -> bs_name; ?>" <?php echo $is_selected;  ?>>
                            <?php echo $ls -> bs_name; ?></option>
                        <?php } ?>
                    </select>
                    <select name="bo_scf2" id="bo_scf2" width="10">
                        <option value="" selected="selected">Species</option>
                        <?php 
                        $is_selected = "";
                        foreach( $species as $ls ){ 
                            $is_selected = $this -> bo_scf2 == $ls -> bs_name ? "selected" : "";
                        ?>
                        <option value="<?php echo $ls -> bs_name; ?>" <?php echo $is_selected;  ?>>
                            <?php echo $ls -> bs_name; ?></option>
                        <?php } ?>
                    </select>
                    <button class="btn_search"><span>검색</span></button>
                </div>
            </form>
        </div>
        <!-- //검색 ContentsSearch -->
        <?php 
			$colLength = 5;
			if( $this -> option -> op_is_file ){
				$colLength++;
			}
		?>
        <div class="pt20"></div>
        <div class="ContentBoardList">
            <?php foreach($list as $lt){ ?>
            <div class="ContentBoardList__item">
                <div class="inner">
                    <a href="<?php echo $lt -> link; ?>" class="link"><span class="skip">링크</span></a>

                    <?php if( isset($lt->thumbnail['is_thumbnail']) ){ ?>
                    <div class="img"> <img src="<?php echo $lt->thumbnail['src']; ?>"
                            alt="<?php echo $lt->thumbnail['alt']; ?>" /></div>
                    <?php }else{ ?>
                    <div class="img"><span
                            style="margin:0;padding:0;line-height:200px;width:auto;display:block;text-align:center;background:#eee">NO
                            IMAGE</span></div>
                    <?php } ?>
                    <div class="info">
                        <div class="title"><span><?php echo $lt -> bo_subject; ?></span></div>
                        <div class="pt10"></div>
                        <div class="etc"><span><?php echo $lt -> bo_scf2; ?></span>
                            <span><?php echo  date("d M Y", strtotime($lt -> bo_yearmd)); ?></span>
                        </div>
                        <div class="pt20"></div>
                        <div class="text">
                            <span><?php echo mb_strimwidth($lt -> bo_content, 0, 200,"...","utf-8"); ?></span>
                        </div>
                    </div>
                    <div class="pt20"></div>
                </div>

            </div>
            <?php } ?>
            <?php if( count($list) == 0 ) { ?>
            <div>
                <div>
				<div class="pt20"></div>
                    <div style="line-height: 1.4;text-align: center;font-size: 1.15em;">
                        <p style="line-height: 1.4;text-align: center;font-size: 1.15em;">검색된 내용이 없습니다.</p>
                    </div>
                </div>
            </div>
            <?php  } ?>
        </div>
        <div class="pt40"></div>
        <!-- pagination -->
        <ul class="pagination">
            <?php echo $pagination; ?>
        </ul>
        <div class="pt50"></div>
    </div>
    <!-- pagination -->
</section>
<script type="text/javascript">
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