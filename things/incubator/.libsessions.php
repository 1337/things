<?php
// brian's php sessions library
// used by brian's php functions library

	function session($name,$val) {
		if(!isset($_SESSION)) {
			session_start();
		}
		session_register($name);
		$_SESSION[$name]=$val;
	}
	
	function gession($name) {
		return $_SESSION[$name];
	}
	function dession($name) {
		unset($_SESSION[$name]);
	}
	
	//session_start(); // you might blow everything up if some other thing wants to start a session
	
?>
