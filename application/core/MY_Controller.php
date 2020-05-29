<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public $beforeDay;
	public $country;
	public $appName;	//twitter_usersテーブルのID

	public $vd;	//viewにわたすData

	public $defaultAppID = 15;

	public function __construct() {
		parent::__construct();
		session_start();
		$this->load->database();
		$this->load->model("session_model");
		$this->load->model("user_model");
		$this->load->model("appuser_model");
		$this->load->model("usertlog_model");
		$this->load->model("twitter_model");
		$this->load->model("acharacter_model");
		$this->load->model("aeelin_model");
		$this->load->model("tnews_model");
		$this->load->model("mypage_model");
		$this->load->library('params');

		//テンプレートにわたす変数を初期化
		$this->vd = array(
			"debug" => false,
			"general" => false,
			"login" => $this->session_model->IsLogin());
	}


	/**
	 * マイページのBaseテンプレート
	 */
	function getBaseTemplate($mypage=true) {
//		$data = $this->vd;
//
//		$data["jsBase"] = $this->load->view('general/parts/js_base', '', TRUE);
//
//		if($mypage && $this->session_model->IsLogin()) {
//			$data["my_pages"] = $this->config->item("my_pages");
//		} else {
//			$data["my_pages"] = $this->config->item("general_pages");
//		}
//
//		if($this->session_model->IsLogin()) {
//			$data["user_data"] = $this->user_model->FindByID($this->session_model->UserID());
//		}
//		$data["navBar"] = $this->load->view('admin/parts/nav_bar', $data, TRUE);
//		$data["sideBar"] = $this->load->view('admin/parts/side_bar', $data, TRUE);
//
////		$data["fixedSetting"] = $this->load->view('general/parts/fixed_setting', '', TRUE);
//		$data["footer"] = $this->load->view('general/parts/footer', '', TRUE);
//		$this->vd = $data;
//		return $this->vd;
		return $this->mypage_model->GetBaseTemplate($this, $mypage);
	}


	function debugMode() {
		$this->vd["debug"] = true;
	}

	//情報取得
	protected function getTopArticles() {
		$tableName = $this->getTable();
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay + 1, date('Y')));

		$this->load->database();
		$query = $this->db->query(
			"SELECT * FROM {$tableName}
			WHERE created >= ? AND created < ?
			AND country = ?
			AND parent_id = 0",
			array($targetStart, $targetEnd, $this->country));
		$result = $query->result_array();
		//echo $this->db->last_query();
		return $result;
	}

	protected function getChildArticles($topArticles) {
		$tableName = $this->getTable();
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay + 1, date('Y')));

		foreach($topArticles as $key => $topArticle ) {
			$reactions = $this->db->query(
				"SELECT * FROM {$tableName}
				WHERE created >= ? AND created < ?
				AND country = ?
				AND parent_id = ?",
				array($targetStart, $targetEnd, $this->country, $topArticle["id"]))->result_array();

			//いいね順にソート。Html特殊文字変換
			$reactions = $this->htmlDecode($reactions);
			$reactions = $this->sortById($reactions, "favorite");
			$topArticles[$key]["reactions"] = $reactions;
		}
		//echo $this->db->last_query();
		return $topArticles;
	}

	//ソート
	protected function sortById($articles, $keyName="id") {
		if(empty($articles)) return $articles;
		foreach ($articles as $key => $value) {
		    $sort[$key] = $value[$keyName];
		}

		array_multisort($sort, SORT_DESC, $articles);
		return $articles;
	}

	//html特殊文字をもとに戻す
	protected function htmlDecode($articles) {
		foreach ($articles as $key => $article) {
			$articles[$key]["head"] = str_replace(array('&nbsp;', '&emsp;', '&ensp;'), '', $articles[$key]["head"]);
			$articles[$key]["body"] = str_replace(array('&nbsp;', '&emsp;', '&ensp;'), '', $articles[$key]["body"]);
			$articles[$key]["head"] = htmlspecialchars_decode($articles[$key]["head"], ENT_QUOTES);
			$articles[$key]["body"] = htmlspecialchars_decode($articles[$key]["body"], ENT_QUOTES);
		}
		return $articles;
	}

	//情報取得
	protected function getApp($appName) {
		$query = $this->db->query("SELECT * from twitter_apps where account_name = ?", $appName);
		return $query->result_array();
	}

	protected function getTable() {
		// if(true){	//if(IsProduction()) {
		// 	return "t_sportskwkm_tweet_logs";
		// }
		$this->load->database();
		$query = $this->db->query(
			"SELECT * FROM twitter_users
			WHERE id = ?",$this->appName);
		if(!empty($result = $query->result_array())) {
			return "t_". $result[0]["account_name"]. "_tweet_logs";
		}
	}
}

