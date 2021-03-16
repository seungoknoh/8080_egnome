<?php
	// 세션변수 생성
	function set_session($session_name, $value)
	{
		if (PHP_VERSION < '5.3.0')
			session_register($session_name);
		// PHP 버전별 차이를 없애기 위한 방법
		$$session_name = $_SESSION[$session_name] = $value;
	}


	// 세션변수값 얻음
	function get_session($session_name)
	{
		return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';
	}
?>