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

	public function limit() {

		$users = $this->appuser_model->GetPublicAndNotSisters();
		$this->getLimit($users);
	}

	public function sisters_limit() {

		$users = $this->appuser_model->GetSisters();
		$this->getLimit($users);
	}



	/**
	 * APIリミットをチェック
	 */
	private function getLimit($users)
	{

		$this->vd += $this->session_model->GetFlash();

		$params = $this->validation($users, $this->input->get(), 3);

		//API Limit グラフデータ作成
		$limitsData = $this->lapilimit_model->FindByDate($params["start"], $params["end"], $params["userIDs"]);
		list($limitGraph, $limitLabels) = $this->makeLimitGraphData($limitsData);
		$this->vd["graphData"] = $limitGraph;
		$this->vd["labels"] = $limitLabels;

			//check active グラフデータ作成
		$activeData = $this->laction_model->FindByDate($params["start"], $params["end"], $params["userIDs"]);

		if (!empty($activeData)) {
			list($activeGraph, $activeLabels) = $this->makeActiveGraphData($activeData);
			$this->vd["activeGraphData"] = $activeGraph;
			$this->vd["activeLabels"] = $activeLabels;
		} else {
			$this->vd["activeGraphData"] = array();
			$this->vd["activeLabels"] = array();
		}


		$this->vd["users"] = $users;
		$this->vd["formDefault"] = $params;


		$this->vd["contents"] = $this->load->view('general/check_limit', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}


	private function makeActiveGraphData($activeData) {
		$labels = array(); //日付
		$graphData = array(); // [activeName][userID][date]

		//カラム名からグラフにすべきカラムを抽出
		$activeNames = array();
		$excludeNames = array("id", "user_id", "created", "modified"); //除外するカラム名
		foreach ($activeData[0] as $activeName => $value) {
			if(!in_array($activeName, $excludeNames)) {
				$activeNames[] = $activeName;
			}
		}

		foreach ($activeData as $active) {
			foreach ($activeNames as $activeName) {
				$dateName = date('Y-m-d-H', strtotime($active["created"]));
				$graphData[$activeName][$active["user_id"]][$dateName] = (int)$active[$activeName];

				//ラベル追加
				if(!in_array($dateName, $labels)) {
					$labels[] = $dateName;
				}
			}
		}

		return array($graphData, $labels);
	}

	/**
	 *　API Limit グラフ作成
	 */
	private function makeLimitGraphData($limitsData) {
		$labels = array();
		$graphData = array(); // [apiName] => array(graphData, labels)
		//表示させないAPI
		$excludeApiNames = array(
			"/application/rate_limit_status",
			"/friends/ids",
			"/followers/ids",
		);
		if(!empty($limitsData)) {
			//使用したAPIを抽出
			$apiNames = array();
			foreach ($limitsData as $l) {
				$json = json_decode($l["json"], TRUE);
				foreach ($json["resources"] as $resource) {
					foreach ($resource as $apiName => $api) {
						if ($api["limit"] != $api["remaining"]) {
							if(!in_array($apiName, $excludeApiNames) && !in_array($apiName, $apiNames)) {
								$apiNames[] = $apiName;
							}
						}
					}
				}
			}

			foreach ($apiNames as $apiName) {
				list($limits, $labels, $max) = $this->makeGraphArray($limitsData, $apiName);
				$graphData[$apiName] = array($limits, $labels, $max);
			}
		}
		return array($graphData, $labels);
	}

	private function makeGraphArray($limits, $targetAPI) {
		$labels = array();
		$ret = array();
		$max = 0;
		foreach ($limits as $l) {
			$graphData = array();
			$json = json_decode($l["json"], true);
			$resources = $json["resources"];

			foreach($resources as $resource) {
				foreach ($resource as $apiName => $api) {
					if($targetAPI != $apiName) {
						continue;
					}
					if($api["limit"] != $api["remaining"]) {
						$graphData["api_name"] = $apiName;
						$graphData["use"] = $api["limit"] - $api["remaining"];
						$max = $api["limit"];
					}
				}
			}

			//追加
			$userID = $l["user_id"];
			$dateName = date('Y-m-d-H', strtotime($l["created"]));
			$ret[$userID][$dateName] = $graphData;
			if(!in_array($dateName, $labels)) {
				$labels[] = $dateName;
			}
		}
		return array($ret, $labels, $max);
	}

	public function followers() {
		$users = $this->appuser_model->GetPublicAndNotSisters();
		$this->makeFollowers($users);
	}

	public function sisters_followers() {
		$users = $this->appuser_model->GetSisters();
		$this->makeFollowers($users);
	}

	/**
	 * フォロワーチェック
	 */
	private function makeFollowers($users)
	{
		$this->vd += $this->session_model->GetFlash();
		//ユーザー一覧を先に取得しておく

		$params = $this->validation($users, $this->input->get(), 30);


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



	//バリデーション
	private function validation($users, $params, $startDefault=10) {
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
			$start = date('Y-m-d', strtotime("-". $startDefault. " days"));
			$end = date('Y-m-d', strtotime("+1 days"));

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
