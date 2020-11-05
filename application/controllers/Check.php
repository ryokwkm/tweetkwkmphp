<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
//		$this->debugMode();
		$this->vd = $this->getBaseTemplate(false);
		$this->vd["general"] = true;
	}

	/**
	 * APIリミットをチェック
	 */
	public function limit()
	{

		$this->vd += $this->session_model->GetFlash();
		//ユーザー一覧を先に取得しておく
		$users = $this->appuser_model->GetPublicUsers();
		$params = $this->validation($users, $this->input->get());

		$this->vd["users"] = $users;
		$this->vd["formDefault"] = $params;


		$this->vd["contents"] = $this->load->view('general/check_limit', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}


	/**
	 * フォロワーチェック
	 */
	public function followers()
	{
		$this->vd += $this->session_model->GetFlash();
		//ユーザー一覧を先に取得しておく
		$users = $this->appuser_model->GetPublicUsers();
		$params = $this->validation($users, $this->input->get());


		$followers = $this->userfollowers_model->FindByDate($params["start"], $params["end"], $params["userIDs"]);
		//ラベル用に日付を取得
		$dateTerm = $this->userfollowers_model->FindByDateOnly($params["start"], $params["end"], $params["userIDs"]);

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

		//chart.jsのデータに整形
		/*
array(2) {
  ["datasets"]=>
  array(1) {
    [0]=>
    array(4) {
      ["label"]=>
      string(12) "スポーツ"
      ["data"]=>
      array(4) {
        [0]=>
        string(3) "898"
        [1]=>
        string(3) "898"
        [2]=>
        string(3) "899"
        [3]=>
        string(3) "899"
      }
      ["backgroundColor"]=>
      string(13) "rgba(0,0,0,0)"
      ["hoverBorderWidth"]=>
      int(10)
    }
  }
  ["labels"]=>
  array(4) {
    [0]=>
    string(10) "2020-06-06"
    [1]=>
    string(10) "2020-06-07"
    [2]=>
    string(10) "2020-06-08"
    [3]=>
    string(10) "2020-06-09"
  }
}
		 */
		$chartArray = array();
		$chartArray["datasets"] = array();
		//ラベル作成。key()は最初の要素のキーを返す
		$labels = array();
		if(!empty($userFollowers)) {
			foreach ($userFollowers[key($userFollowers)]["followers"] as $date => $follower) {
				$labels[] = $date;
			}
		}
		$chartArray["labels"] = $labels;

		//データ作成
		foreach ($userFollowers as $followers) {
			$u_followers = array();
			foreach ($followers["followers"] as $f) {
				$u_followers[] = $f;
			}
			if(!isset($followers["name"])) {
				$followers["name"] = "";
			}
			$chartArray["datasets"][] = array(
				"label" => $followers["name"],
				"data"=> $u_followers,
				"backgroundColor" => "rgba(0,0,0,0)",
				"hoverBorderWidth" => 10,
			);
		}
		$this->vd["chartArray"] = $chartArray;
//		vr($chartArray);exit;


		$this->vd["userFollowers"] = $userFollowers;
		$this->vd["users"] = $users;
		$this->vd["formDefault"] = $params;


		$this->vd["contents"] = $this->load->view('general/followers', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}


	private function makeChartJSDefault($label) {

	}

	//バリデーション
	private function validation($users, $params) {
		if(!empty($params)) {
			$start = $params["start"];
			$end = $params["end"];
			$targetUserArray = explode(",", $params["targetUser"]);

			//バリデーション
			$errMessage = "";
			if($start > $end) {
				$errMessage .= "日付が間違っています<br>";
			}
			if(empty($targetUserArray)){
				$errMessage .= "ユーザーが選択されていません<br>";
			}
			$this->vd["err"] = $errMessage;

		} else if(empty($params)) {
			//直接アクセス
			$start = date('Y-m-d', strtotime("-10 days"));
			$end = date('Y-m-d');

			//対象ユーザーのデフォルト作成
			foreach ($users as $user) {
				$targetUserArray[] = $user["id"];
			}
		}
		$formDefault = array();
		$formDefault["start"] = $start;
		$formDefault["end"] = $end;
		$formDefault["userIDs"] = $targetUserArray; //array() コードで使用
		$formDefault["targetUser"] = implode(",", $targetUserArray);

		return $formDefault;
	}

}
