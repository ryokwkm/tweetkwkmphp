<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mypage extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session_model->IsLogin() == false ) {
			header( 'location: /auth/index' );
		}
	}

	public $defaultAppID = 14;
	public $myApps = array(14, 15);

	/**
	 * index
	 */
	public function index($appID=0)
	{
		$appID = $this->checkAppID($appID);

		$this->getBaseTemplate();
		$this->debugMode();
		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$this->vd["characters"] = $this->acharacter_model->FindByStoryID(1);

		$this->vd += $this->session_model->GetFlash();

		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function userupdate() {

		$posts = $this->input->post();
		$appID = $this->checkAppID($posts["id"]);


		//更新対象Paramを格納
		$columns = array(
			"exe_rate",
			"character_mode",
			"target_screen_name",
			"target_character_id",
			"search_rate",
			"fire_lv",
			"search_keyword",
			"search_option",
			"is_search",
			"is_news",
			"is_reply",
			"is_replyreply",
			"reply_retweet",
			"followback",
		);
		$updateParams = array();
		foreach($columns as $col) {
			if(isset($posts[$col])) {
				$updateParams[$col] = $posts[$col];
			}
		}

		//screen_nameからuser_idを取得
		if(!empty($posts["target_screen_name"])) {
			$twitter = $this->twitter_model->NewObject($this->session_model->UserID());
			$res = $twitter->get("users/show", array("screen_name" => $posts["target_screen_name"]));
			$updateParams["target_user_id"] = $res->id_str;
		}
		if(isset($posts["main_status"]) && $posts["main_status"] == 1){
			$statusReady = 3;
			$updateParams["is_deleted"] = $statusReady;	//go側の処理で0にする。check後、action済みにする
		} else {
			$updateParams["is_deleted"] = 1;
		}

		$this->appuser_model->UpdateByID($updateParams, $appID);

		//action済みにする
		$appuser = $this->appuser_model->FindByID($appID);
		$this->usertlog_model->UpdateActioned($appuser["user_id"]);

		$this->session_model->SetFlash("message", "更新しました");
		header( 'location: /mypage/index/'. $appID );
	}

	public function user()
	{

		$data = $this->getBaseTemplate();
		$data["contents"] = $this->load->view('admin/user', '', TRUE);
		$this->load->view('admin/base', $data);
	}


	public function checkAppID($appID) {
		if(empty($appID)) {
			$appID = $this->defaultAppID;
		}

		if(!in_array($appID, $this->myApps)) {
			echo "アプリの編集が許可されていません";
			exit;
		}
		return $appID;
	}
}
