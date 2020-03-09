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
		$this->load->library('session');
		vr($_SESSION);
		$this->load->view('auth/index.html');
	}

}
