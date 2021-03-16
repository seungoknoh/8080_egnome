<!-- header -->
<div class="header-wrap">
	<header id="header">
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-sidemenu" id="btn-admin-menu">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/backS1te">Administrator</a>
					<button type="button" class="navbar-toggle navbar-setting btn-toggle" id="btn-setting" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/">Go to Main</a></li>
						<li><a href="/backS1te/config">Settings</a></li>
						<?php if ( @$this -> session -> userdata('logged_in') == TRUE) { ?>
						<li><a href="/backS1te/auth/logout">Logout</a></li>
						<?php } ?>
					</ul>
					<div class="navbar-login navbar-right">
						<?php 
						if ( @$this -> session -> userdata('logged_in') == TRUE) {
						echo "<span class='user'>".$this -> session -> userdata('mb_name')."님 환영합니다. </span>";
						} ?>
					</div>
					<div class="navbar-form navbar-right">
						<div class="adminSearch">
							<input type="text" class="form-control" placeholder="Menu Search..." name="menu_stx" />
							<div class="adminSearch-list">
								<ul class="list-group" id="adminSearch-list"></ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</header>
</div>
<!-- //header -->

<script type="text/javascript">
	$(document).ready(function(){
		menuAjax.init();
	});
	var menuAjax = (function(){
		return {
			input : $("input[name='menu_stx']"),
			el : $("#adminSearch-list"),
			init : function(){
				var thisObj = this;
				thisObj.input.on("keyup", function(e){
					thisObj.search();
				});
			},		
			search : function(){
				var thisObj = this;
				var stx = thisObj.input.val();
				$.ajax({
					url : "/backS1te/menu/json_list",
					dataType : "json",
					type : "GET",
					data : {
						'stx' : stx
					}
				}).done(function(data){
					thisObj.el.empty();
					if( !data.list ) return;
					for( var i =0;i<data.list.length;i++ ){
						thisObj.el.append( thisObj.setItem(data.list[i]) );
					}
				});
			},
			setItem : function(_data){
				return "<li class='list-group-item'><a href='"+_data.mn_link+"'><span class='"+_data.mn_icon+"'></span> "+_data.mn_name+"</a></li>";
			}
		}
	})();
</script>
