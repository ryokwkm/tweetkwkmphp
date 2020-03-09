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
		var_dump($_SESSION);
		vr(array("ok"));
//		$this->load->view('auth/index.html');

//		$this->load->library('session');

		echo "ng?";
		vr(array("ok"));
	}

}
