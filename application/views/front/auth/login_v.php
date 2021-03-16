
<!-- AuthLoignArea 로그인 -->
<section class="AuthLoignArea" id="AuthLoignArea">
	<div class="AuthLoignArea__inner">
		<?php echo error_output($errors); ?>
		<?php 
			$attr = array('class' => 'form-signin', 'id'=>'frm');
			echo form_open('', $attr); 
		?>	
			<input type="hidden" name="token" value="<?php echo $token ?>" />
			<h1 class="title"><span>로그인</span></h1>
			<div class="AuthLoignArea__form">
				<div class="input"><input type="text" name="mb_id" title="아이디" placeholder="ID" /></div>
				<div class="input"><input type="password" name="mb_passwd" title="비밀번호" autocomplete="new-password" placeholder="PW" /></div>
				<button type="submit" class="button__login"><span>LOG IN</span></button>
			</div>
		</form>
	</div>
</section>
<!-- //AuthLoignArea 로그인 -->
