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


		$this->vd["twitterUsers"] = $this->appuser_model->GetPublicUsers();

		$this->vd["contents"] = $this->load->view('general/list', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function followers()
	{

		$this->vd += $this->session_model->GetFlash();
		//ユーザー一覧を先に取得しておく
		$users = $this->appuser_model->GetPublicUsers();

		$posts = $this->input->post();
		$formDefault = array();
		$targetUserArray = array();
		$errMessage = "";
		if(!empty($posts)) {
			$start = $posts["start"];
			$end = $posts["end"];
			$targetUserArray = explode(",", $posts["targetUser"]);
			//バリデーション
			if($start > $end) {
				$errMessage .= "日付が間違っています<br>";
			}
			if(empty($targetUserArray)){
				$errMessage .= "ユーザーが選択されていません<br>";
			}
		}
		if(empty($posts)) {
			//直接アクセス
			$start = date('Y-m-d', strtotime("-10 days"));
			$end = date('Y-m-d');

			//対象ユーザーのデフォルト作成
			foreach ($users as $user) {
				$targetUserArray[] = $user["id"];
			}
		}
		$formDefault["start"] = $start;
		$formDefault["end"] = $end;
		$formDefault["targetUser"] = implode(",", $targetUserArray);

		$followers = $this->userfollowers_model->FindByDate($start, $end, $targetUserArray);
		//ラベル用に日付を取得
		$dateTerm = $this->userfollowers_model->FindByDateOnly($start, $end, $targetUserArray);

		//ユーザー名と結合、データ整形。不要なデータがあった場合は削除
		$userFollowers = array();
		foreach ($users as $user) {
			$userFollowers[$user["id"]] = array(
				"user_id" => $user["user_id"],
				"name" => $user["name"],
				"followers" => array(),
			);
		}




		foreach ($followers as $follower) {
			$user_id = $follower["user_id"];
			$created = $follower["created"];
			$userFollowers[$user_id]["followers"][$created] = $follower["follower"];
		}

		//データが無いものを削除
		foreach ($userFollowers as $key => $userFollower) {
			if(count($userFollower["followers"]) <= 1) {
				unset($userFollowers[$key]);
			}
		}

		//日付分のデータがなければ、ない部分は0で埋める
		foreach ($userFollowers as $key => $userFollower) {
			foreach ($dateTerm as $date) {
				if(!isset($userFollower["followers"][$date])) {
					$userFollowers[$key]["followers"][$date] = 0;
				}
			}
		}

		//ソート
		foreach ($userFollowers as $key => $userFollower) {
			ksort($userFollowers[$key]["followers"]);
		}
		$this->vd["userFollowers"] = $userFollowers;
		$this->vd["users"] = $users;
		$this->vd["formDefault"] = $formDefault;
		$this->vd["err"] = $errMessage;

		$this->vd["contents"] = $this->load->view('general/followers', $this->vd, TRUE);
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
		$this->vd += $this->session_model->GetFlash();
		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

}
