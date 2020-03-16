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

	public $appID = 6;

	/**
	 * index
	 */
	public function index()
	{
		$appID = $this->appID;

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
		if(!empty($posts["screen_name"])) {

		}
		$this->appuser_model->UpdateByID($posts, $appID);

		//action済みにする
		$appuser = $this->appuser_model->FindByID($appID);
		$this->usertlog_model->UpdateActioned($appuser["user_id"]);

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
