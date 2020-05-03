<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define("CHARA_MODE_TWITTER_USER", 1);
define("CHARA_MODE_STORY_USER", 2);
class Appuser_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	//0 or 1 の要素。バリデーションに使用
	//type=checkbox の場合、checkが入っていない場合は値が送信されない。そのため、値が無い場合は０で更新
	public $checkboxs = array(
		"character_mode",	//0がある
		"is_search",
		"is_news",
		"is_reply",
		"is_replyreply",
		"fire_lv",
		"reply_retweet",
		"followback"
	);

	//更新を許可したカラム（idなどが更新されないよう制御）
	public $updateColumn = array(
		"exe_rate",
		"character_mode",
//		"target_screen_name",
//		"target_character_id",
		"search_rate",
		"search_count",
		"search_keyword",
		"search_option",
		"fire_lv",
		"is_search",
		"search_mode",
		"is_news",
		"news_keyword",
		"is_reply",
		"is_replyreply",
		"reply_retweet",
		"followback",
	);


	public function FindByID($userID) {
		$query = $this->db->query("SELECT  * from twitter_users where id = ?", $userID);
		$users = $query->result_array();
		if(!empty($users)) {
			return $users[0];
		}
		return array();
	}

	public function UpdateByID($id, $posts) {
		foreach( $this->checkboxs as $checkbox ) {
			$posts[$checkbox] = $this->check_checkbox($posts, $checkbox);
		}
//		vr($posts);
//		exit;
		return $this->db->update("twitter_users", $posts, array("id" => $id));
	}


	/**
	 * postの内容をバリデーション
	 * @param $posts
	 * @return array
	 * @throws \Exception
	 */
	public function ValidationUpdate($posts) {

		//更新対象Paramを格納
		$ups = array();
		foreach($this->updateColumn as $col) {
			if(isset($posts[$col])) {
				$ups[$col] = $posts[$col];
			}
		}
		$ups["search_keyword"] = trim($ups["search_keyword"]);
		$ups["news_keyword"] = trim($ups["news_keyword"]);

		if($ups["is_news"] == 1 && empty($ups["news_keyword"]) ) {
			throw new Exception("ニュースを検索する場合は、ニュース検索キーワードを指定してください");
		}
		if($ups["is_search"] == 1 && empty($ups["search_keyword"]) ) {
			throw new Exception("トレンドを検索する場合は、トレンド検索キーワードを指定してください");
		}
		if($ups["fire_lv"] > 10) {
			throw new Exception("リアクション数が多すぎます");
		}
		if($ups["search_count"] > 10) {
			throw new Exception("ツイートするランキング数が多すぎます");
		}


		//screen_nameからuser_idを取得
		if($posts["character_mode"] == CHARA_MODE_TWITTER_USER && !empty($posts["target_screen_name"])) {
			$twitter = $this->twitter_model->NewObject($this->session_model->UserID());
			$res = $twitter->get("users/show", array("screen_name" => $posts["target_screen_name"]));
			if(!isset($res->id_str) || empty($res->id_str)) {
				throw new Exception("Twitterユーザーが見つかりません。存在しないTwitterユーザー名が入力されています");
			}

			$ups["target_screen_name"] = $posts["target_screen_name"];
			$ups["target_user_id"] = $res->id_str;
		}

		if($ups["is_replyreply"] == 1 && empty($ups["target_user_id"])) {
			throw new Exception("「リプライにお返事」する場合、キャラクターの性格を設定する必要があります");
		}

		if(isset($posts["main_status"]) && $posts["main_status"] == 1){
			$statusReady = 3;
			$ups["is_deleted"] = $statusReady;	//go側の処理で0にする。check後、action済みにする
		} else {
			$ups["is_deleted"] = 1;
		}
		return $ups;
	}

	public function check_checkbox($params, $name) {
		if(!isset($params[$name])) {
			return 0;
		}
		return $params[$name];
	}
}
