<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Test extends CI_Controller {

	/**
	 * index
	 */
	public function index()
	{
		echo "ok";

		vr(array("ok"));
//		$this->load->view('auth/index.html');
			session_start();
//		$this->load->library('session');
		var_dump($_SESSION["test"]);

		$_SESSION["test"] = "gogogo";
		echo "ng?";
		vr(array("ok"));
	}

}
