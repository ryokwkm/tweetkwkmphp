<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Auth extends CI_Controller {

	public function index()
	{
		$this->load->library('session');
		vr($_SESSION);
		$this->load->view('auth/index.html');
	}





}
