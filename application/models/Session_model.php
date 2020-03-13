<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_model extends CI_Model {
	//session_start() はコントローラーでやる
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * Flashセッション
	 */
	public function GetFlash() {
		$ret = array();
		foreach($_SESSION as $key => $value) {
			if(strpos($key, "flash_") === 0 ) {
				$ret[str_replace("flash_", "", $key)] = $value;
				//削除
				unset($_SESSION[$key]);
			}
		}
		return $ret;
	}

	public function SetFlash($name, $value) {
		$_SESSION["flash_". $name] = $value;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	public function UserID() {
		if(isset($_SESSION["auth"]["user_id"])) {
			return $_SESSION["auth"]["user_id"];
		}
		return 0;
	}


	public function MakeLogin($appID, $userID) {
		$_SESSION["auth"]["app_id"] = $appID;
		$_SESSION["auth"]["user_id"] = $userID;
		$_SESSION["is_login"] = true;
	}

	public function IsLogin() {
		if(isset($_SESSION["is_login"]) && $_SESSION["is_login"] == true) {
			return true;
		}
		return false;
	}
}
