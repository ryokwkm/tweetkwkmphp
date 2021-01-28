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



	public function index()
	{
//		$this->user();
		$this->getBaseTemplate();
		$this->vd += $this->session_model->GetFlash();
		$this->vd["twitterUsers"] = $this->appuser_model->GetUsersByAdmin();
		$this->vd["contents"] = $this->load->view('admin/list', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function user($appID=0) {

		$this->mypage_model->MakeUserTpl($this, $appID);
		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}


	public function userupdate() {
		if(empty($this->input->post())) {
			header( 'location: /mypage/user/' );
		}

		$posts = $this->input->post();
		$posts = $this->appuser_model->SetDefault($posts);
		$appID = $this->mypage_model->checkAppID($this, $posts["id"]);
		//バリデーション＆更新
		try {
			$new = $this->appuser_model->ValidationUpdate($posts);
			$old = $this->appuser_model->FindByID($appID);
			$this->deleteOldLogs($old["user_id"], $old, $new);
			$this->appuser_model->UpdateByID($appID, $new);
		} catch(Exception $e) {
			$this->session_model->SetFlash("err", $e->getMessage());
			$this->mypage_model->MakeUserTpl($this, $appID);
			$this->debugMode();

			if($posts["main_status"] > 0) {
				$this->vd["appuser"]["is_deleted"] = Appuser_model::$StatusReady;
			}
			$this->vd["appuser"] = $this->params->OverWrite($this->vd["appuser"], $posts);

			$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
			$this->load->view('admin/base', $this->vd);
			return;
		}

		//action済みにする
		$appuser = $this->appuser_model->FindByID($appID);
		$this->usertlog_model->UpdateActioned($appuser["user_id"]);

		//親機なら設定を子機にコピー
		if ($appuser["parent_id"] == -1) {
			$this->appuser_model->CopyParentByID($appuser["id"]);
		}

		$this->session_model->SetFlash("message", "更新しました");
		header( 'location: /mypage/user/'. $appID );
	}


	public function test_user($appID=0) {

		$appID = $this->mypage_model->checkAppID($this, $appID);
		$output = "";
		$posts = $this->input->post();
		if(!empty($posts)){
			//チェック実行
			shell_exec("ls");
			if (IsProduction()) {
				$output = shell_exec("sh /virtual/vacation/public_html/www.2chx.net/test-mac.sh ". $posts["id"]);
			} else {
				$output = shell_exec("sh ~/source/GAS/.go/src/github.com/ryokwkm/trends/test-mac.sh ". $posts["id"]);
			}
		}

		$this->getBaseTemplate();
//		$this->debugMode();
		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$this->vd["characters"] = $this->acharacter_model->FindByStoryID(1);

		$this->vd += $this->session_model->GetFlash();
		$this->vd["output"] = $output;

		$this->vd["contents"] = $this->load->view('admin/test_user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
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
