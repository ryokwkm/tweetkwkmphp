<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_model extends CI_Model {
	//session_start() はコントローラーでやる
	public function __construct()
	{
		parent::__construct();
	}

	public function MakeLogin($appID, $userID) {
		$_SESSION["auth"]["app_id"] = $appID;
		$_SESSION["auth"]["user_id"] = $userID;
		$_SESSION["is_login"] = true;
	}

	public function IsLogin($appID, $userID) {
		if(isset($_SESSION["is_login"]) && $_SESSION["is_login"] == true) {
			$_SESSION["auth"]["app_id"] = $appID;
			$_SESSION["auth"]["user_id"] = $userID;
			return true;
		}
		return false;
	}
}
