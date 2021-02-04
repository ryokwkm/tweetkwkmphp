<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
//		$this->debugMode();
		$this->vd = $this->getBaseTemplate(false);
		$this->vd["general"] = true;
	}

	public function index()
	{
		$this->list();
	}

	public function list()
	{
		$this->vd += $this->session_model->GetFlash();
		$this->vd["twitterUsers"] = $this->appuser_model->GetPublicAndNotSisters();
		$this->vd["memo"] = "情報をつぶやくBot達。";
		$this->vd["contents"] = $this->load->view('general/list', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function sisters()
	{
		$this->vd += $this->session_model->GetFlash();
		$this->vd["twitterUsers"] = $this->appuser_model->GetSisters();
		$this->vd["memo"] = "Sistersは一般人のように振る舞うことを目的としたBot。実験中";
		$this->vd["contents"] = $this->load->view('general/list', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}


	public function view($appID)
	{
		if(empty($appID)) {
			echo "エラー";
			exit;
		}

		$appuser = $this->appuser_model->FindByID($appID);
		if(empty($appuser["is_public"])) {
			echo "不正アクセス";
			exit;
		}
		$this->vd["appuser"] = $appuser;
		$this->vd["characters"] = $this->acharacter_model->FindByStoryID(1);
		$this->vd["trend_genre"] = $this->config->item("trend_genre");
		$this->vd += $this->session_model->GetFlash();
		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

}
