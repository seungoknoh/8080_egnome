<!DOCTYPE html>
<html lang="ko">
<?php $this->load->view('/front/_include/head_v.php', $option); ?>
<body>
<?php $this->load->view($page, $option); ?>
<?php $this->load->view('/front/_include/tail_v.php', $option); ?>
</body>
</html>