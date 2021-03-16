<!-- breadcrumb -->
<ol class="breadcrumb text-right">
<li><a href="/baskS1te/main">Home</a></li>
<li><a href="/baskS1te/config">환경설정</a></li>
<li class="active">기본환경설정</li>
</ol>
<!-- //breadcrumb -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>기본환경설정<small> 웹사이트에 대한 설정을 적용할 수 있습니다.</small></h2>
	</div>
	<form action="<?php echo current_url(); ?>/update" method="post" id="frm" class="form-horizontal">
	<div class="panel-body">
		<div class="panel panel-default">
			<input type="hidden" name="cf_url_prev" value="<?php echo $view -> cf_url ?>" />
			<div class="panel-heading"><span class="glyphicon glyphicon-cog"></span>기본환경설정</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="cf_title" class="col-sm-3 control-label">웹 URL</label>
					<div class="col-sm-9">
						<div class="input-group">
							<input type="text" class="form-control" name="cf_url"  value="<?php echo $view -> cf_url ?>" />
							<a href="javascript:;" id="btn-modify-url" class="input-group-addon">컨텐츠 웹사이트 URL 수정</a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="cf_title" class="col-sm-3 control-label">웹사이트명</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="cf_title"  value="<?php echo $view -> cf_title ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="cf_admin" class="col-sm-3 control-label">관리자</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="cf_admin"  value="<?php echo $view -> cf_admin ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="cf_admin_email" class="col-sm-3 control-label">관리자메일</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="cf_admin_email"  value="<?php echo $view -> cf_admin_email ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="cf_admin_name" class="col-sm-3 control-label">관리자명</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="cf_admin_name"  value="<?php echo $view -> cf_admin_name ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="cf_addr1" class="col-sm-3 control-label">우편번호</label>
					<div class="col-sm-5">
						<div class="input-group">
							<div class="input-group-addon">우편번호</div>
							<input type="text" class="form-control" name="cf_zip"  value="<?php echo $view -> cf_zip ?>" id="postcode" />
						</div>
					</div>
					<div class="col-sm-4 col-md-3 col-lg-2">
						<button class="btn btn-primary btn-block" id="btn-zip">우편번호검색</button>
					</div>
				</div>
				<div class="form-group">
					<label for="cf_addr1" class="col-sm-3 control-label">주소</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="cf_addr1"  value="<?php echo $view -> cf_addr1 ?>" id="address" placeholder="주소" />
					</div>
					<div class="col-sm-3">
						<input type="text" class="form-control" name="cf_addr2" value="<?php echo $view -> cf_addr2 ?>" id="address2" placeholder="상세주소" />
					</div>
				</div>
				<span id="guide" style="color:#999"></span>
				<div class="form-group">
					<label for="cf_tel" class="col-sm-3 control-label">전화번호</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="cf_tel"  value="<?php echo $view -> cf_tel ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="cf_fax" class="col-sm-3 control-label">팩스번호</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="cf_fax"  value="<?php echo $view -> cf_fax ?>" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="panel panel-warning">
			<div class="panel-heading"><span class="glyphicon glyphicon-cog"></span> 가입환경설정</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="cf_privacy" class="col-sm-3 control-label">개인정보취급방침</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" name="cf_privacy"><?php echo $view -> cf_privacy ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="cf_service" class="col-sm-3 control-label">서비스이용약관</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" name="cf_service"><?php echo $view -> cf_service ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="cf_is_phone" class="col-sm-3 control-label">회원가입</label>
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
							<input type="checkbox" value="1" name="cf_is_phone" <?php echo $view -> cf_is_phone == "1" ? "checked":""; ?>> 전화번호 사용
							</label>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
							<input type="checkbox" value="1" name="cf_email_auth" <?php echo $view -> cf_email_auth == "1" ? "checked":""; ?>> 메일인증 사용
							</label>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
		<div class="col-sm-12 text-right">
			<button type="submit" id="btn-write" class="btn-lg btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Write</button>
		</div>
		</div>
	</div>
	</form>
</div>
<script type="text/javascript">
	var config = {
		url : "/backS1te/config",
		contentUrlModify : function(_prev, _next){
			var objThis = this;
			$.ajax({
				method : "GET",
				dataType : "json",
				url : objThis.url+"/json_urlUpdate",
				data : {
					"cf_url_prev" : _prev,
					"cf_url_next" : _next
				}
			}).done(function(data){
				alert(data.msg);
			});
		}
	};
	
	$("#btn-modify-url").on("click", function(){
		var url_prev = $("input[name='cf_url_prev']").val();
		var url_next = $("input[name='cf_url']").val();
		if( confirm("게시물 컨텐츠에 포함된 주소 "+url_prev+"를 "+url_next+"로 변경합니다.")){
			config.contentUrlModify(url_prev, url_next);
		}
	});
	
</script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#btn-zip").on("click", function(e){
			e.preventDefault();
			execDaumPostcode();
		});
	});
	//본 예제에서는 도로명 주소 표기 방식에 대한 법령에 따라, 내려오는 데이터를 조합하여 올바른 주소를 구성하는 방법을 설명합니다.
	function execDaumPostcode() {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

				// 각 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var fullAddr = ''; // 최종 주소 변수
				var extraAddr = ''; // 조합형 주소 변수

				// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					fullAddr = data.roadAddress;

				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					fullAddr = data.jibunAddress;
				}

				// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
				if(data.userSelectedType === 'R'){
					//법정동명이 있을 경우 추가한다.
					if(data.bname !== ''){
						extraAddr += data.bname;
					}
					// 건물명이 있을 경우 추가한다.
					if(data.buildingName !== ''){
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
					fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				document.getElementById('postcode').value = data.zonecode; //5자리 새우편번호 사용
				document.getElementById('address').value = fullAddr;

				// 커서를 상세주소 필드로 이동한다.
				document.getElementById('address2').focus();
			}
		}).open();
	}
</script>