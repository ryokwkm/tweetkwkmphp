<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session_model->IsLogin() == false ) {
			header( 'location: /auth/index' );
		}
	}

	public function index()
	{
		$this->load->helper("twitter_user");
		$this->getBaseTemplate();
		$this->vd += $this->session_model->GetFlash();


		$this->vd["twitterUsers"] = $this->appuser_model->GetUsersByAdmin();

		$this->vd["contents"] = $this->load->view('admin/list', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function list2()
	{
		$this->load->helper("twitter_user");
		$data["twitterUsers"] = $this->getTwitterUsers();
		$data["jsBase"] = $this->load->view('general/parts/js_base', '', TRUE);
		$data["navBar"] = $this->load->view('admin/parts/nav_bar', '', TRUE);
		$this->load->view('admin_index', $data);

	}


	//情報取得
	protected function getTwitterUsers() {
		$this->load->database();
		$query = $this->db->query("SELECT * from twitter_users where is_deleted <> 1 OR (is_deleted = 1 AND is_debug = 1)");
		return $query->result_array();
	}
}
