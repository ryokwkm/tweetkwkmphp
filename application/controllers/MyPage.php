<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		$this->debugMode();
		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);

		$this->vd += $this->session_model->GetFlash();

		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function userupdate() {
		$appID = $this->appID;
		$posts = $this->input->post();

		//screen_nameからuser_idを取得
		if(!empty($posts["target_screen_name"])) {
			$twitter = $this->twitter_model->NewObject($this->session_model->UserID());
			$res = $twitter->get("users/show", array("screen_name" => $posts["target_screen_name"]));
			$posts["target_user_id"] = $res->id_str;
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
