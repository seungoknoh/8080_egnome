
<?php 
	$attr = array('class' => 'form-signin', 'id'=>'frm');
	echo form_open('', $attr); 
?>
	<input type="hidden" name="token" value="<?php echo $token ?>" />
	<h2 class="form-signin-heading text-center">(주)이지놈 <br>관리자페이지 </h2>
	
	<?php foreach($error as $value){ ?>
	<div id="alert-area">
		<p class="bg-warning"><?php echo $value ?></p>
	</div>
	<?php }?>
	<div class="item" style="padding-top:10px">
		<label for="mb_id" class="sr-only">ID</label>
		<input type="text" id="mb_id" name="mb_id" class="form-control" placeholder="ID" required autofocus>
	</div>
	<div class="item" style="padding-top:5px">
		<label for="mb_passwd" class="sr-only">Password</label>
		<input type="password" id="mb_passwd" name="mb_passwd" class="form-control" placeholder="Password" required>
	</div>
	<!-- <div class="checkbox">
		<label><input type="checkbox" value="1" name="is_save_id" /> Remember me</label>
	</div> -->
	<button class="btn btn-lg btn-primary btn-block" type="submit">로그인</button>
</form>
<!-- //login-form -->
