<?php

class User {

	var $data = array();
	var $is_login = false;
	var $level = 0;

	public function __construct() {
		if ($_SESSION['FE']) {
			if ($_SESSION['FE']['login'] == true) {
				$this -> is_login = true;
			}
		} else {
			$_SESSIOn['FE'] = array('level' => 0);
		}
	}

	public function login() {
		$_SESSION['FE']['login'] = true;
		$this -> login = true;
		$this -> level = 1;
	}

	public function logout() {
		unset($_SESSION['FE']);
		$this -> login = false;
		$this -> level = 0;
		session_destroy();
	}

	public function getUser() {
		return $_SESSION['FE'];
	}

	public function getAttrib($attribute) {
		return $_SESSION['FE'][$attribute];
	}

	public function getLevel() {
		return $_SESSION['FE']['level'];
	}

	public function isAuth() {
		return $this -> isLogin();
	}

	public function isLogin() {
		if ($_SESSION['FE']['login'] === true)
			return true;

		return false;
	}

}
?>