<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
//		$this->debugMode();
		$this->vd = $this->getGeneralTemplate();
		$this->vd["general"] = true;
	}

	public function index()
	{
		$this->list();
	}

	public function list()
	{
		$this->load->helper("twitter_user");

		$this->vd += $this->session_model->GetFlash();


		$this->vd["twitterUsers"] = $this->appuser_model->GetUserByIDs($this->myApps);

		$this->vd["contents"] = $this->load->view('general/list', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function view($appID)
	{
		$appID = $this->checkAppID($appID);
		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$this->vd["characters"] = $this->acharacter_model->FindByStoryID(1);
		$this->vd += $this->session_model->GetFlash();
		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}


}
