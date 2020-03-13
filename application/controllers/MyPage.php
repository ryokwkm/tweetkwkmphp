<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class MyPage extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		if($this->session_model->IsLogin() == false ) {
			header( 'location: /auth/index' );
		}
	}

	/**
	 * index
	 */
	public function index()
	{
		$appID = 14;	//どうやって渡すか
		$this->getBaseTemplate();
//		$this->debugMode();
		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$this->vd += $this->session_model->GetFlash();

		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function userupdate() {
		$appID = 14;	//どうやって渡すか
		$posts = $this->input->post();
		$this->appuser_model->UpdateByID($posts, $appID);
		$this->session_model->SetFlash("message", "更新しました");
		header( 'location: /mypage/index' );
	}

	public function user()
	{

		$data = $this->getBaseTemplate();
		$data["contents"] = $this->load->view('admin/user', '', TRUE);
		$this->load->view('admin/base', $data);
	}



}
