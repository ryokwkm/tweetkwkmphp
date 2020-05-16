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
	public $myApps = array(14, 15);	//編集を許可するBOT


	public function index($appID=0)
	{
		$this->user($appID);
	}

	public function user($appID=0) {
		$appID = $this->checkAppID($appID);

		$this->getBaseTemplate();

		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$this->vd["characters"] = $this->acharacter_model->FindByStoryID(1);

		$this->vd += $this->session_model->GetFlash();

		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function userupdate() {

		$posts = $this->input->post();
		$appID = $this->checkAppID($posts["id"]);

		//バリデーション＆更新
		try {
			$new = $this->appuser_model->ValidationUpdate($posts);
			$old = $this->appuser_model->FindByID($appID);
			$this->deleteOldLogs($old["user_id"], $old, $new);
			$this->appuser_model->UpdateByID($appID, $new);
		} catch(Exception $e) {
			$this->session_model->SetFlash("err", $e->getMessage());
			header( 'location: /mypage/user/'. $appID );
			exit;
		}

		//action済みにする
		$appuser = $this->appuser_model->FindByID($appID);
		$this->usertlog_model->UpdateActioned($appuser["user_id"]);

		$this->session_model->SetFlash("message", "更新しました");
		header( 'location: /mypage/user/'. $appID );
	}


	public function test_user($appID=0) {
		$appID = $this->checkAppID($appID);
		if(!empty($this->input->post())){
			//チェック実行
			shell_exec("ls");
			if (IsProduction()) {
				$output = shell_exec("sh /virtual/vacation/public_html/www.2chx.net/test.sh");
			} else {
				$output = shell_exec("sh ~/source/GAS/.go/src/github.com/ryokwkm/trends/test-mac.sh");
			}
		}

		$this->getBaseTemplate();

		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$this->vd["characters"] = $this->acharacter_model->FindByStoryID(1);

		$this->vd += $this->session_model->GetFlash();
		$this->vd["output"] = $output;
		$this->debugMode();
		$this->vd["contents"] = $this->load->view('admin/test_user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	//権限チェック的なことがしたい
	protected function checkAppID($appID) {
		if(empty($appID)) {
			$appID = $this->defaultAppID;
		}

		if(!in_array($appID, $this->myApps)) {
			echo "アプリの編集が許可されていません";
			exit;
		}
		return $appID;
	}



	private function deleteOldLogs($userID, $old, $new) {
		//マルコフユーザーが違った場合
		if($new["character_mode"] == CHARA_MODE_TWITTER_USER) {
			if($new["target_character_id"] != $old["target_character_id"]) {
				//a_eelin_tweet_logsを削除
				$this->aeelin_model->DeleteByUserID($userID);
			}
		}

		//ニュースが違った場合
		if($new["is_news"] == 1) {
			if($new["news_keyword"] != $old["news_keyword"]) {
				//t_news削除
				$this->tnews_model->DeleteByUserID($userID);
			}
		}
	}
}
