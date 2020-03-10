<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class MyPage extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		session_start();
	}

	/**
	 * index
	 */
	public function index()
	{

		$this->load->model("Session_model");
		$data = $this->getBaseTemplate();
		$data["contents"] = $this->load->view('admin/user', '', TRUE);
		$this->load->view('admin/base', $data);
	}

	public function user()
	{

		$data = $this->getBaseTemplate();
		$data["contents"] = $this->load->view('admin/user', '', TRUE);
		$this->load->view('admin/base', $data);
	}


}
