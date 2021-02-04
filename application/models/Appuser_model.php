<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define("CHARA_MODE_TWITTER_USER", 1);
define("CHARA_MODE_STORY_USER", 2);
class Appuser_model extends CI_Model {
	public static $StatusReady = 3;
	public function __construct()
	{
		parent::__construct();
	}

	//0 or 1 の要素。バリデーションに使用
	//type=checkbox の場合、checkが入っていない場合は値が送信されない。そのため、値が無い場合は０で更新
	public $checkboxs = array(
		"character_mode",	//0がある
		"is_search",
		"serif_relation_retweet",
		"is_news",
		"is_reply",
		"is_replyreply",
		"is_wasshoi",
		"fire_lv",
		"reply_retweet",
		"followback"
	);

	//更新を許可したカラム（idなどが更新されないよう制御）
	public $updateColumn = array(
		"exe_rate",
		"serif_rate",
		"character_mode",
		"target_character_id",
		"target_screen_name",
		"search_rate",
		"search_count",
		"search_keyword",
		"search_genre",
		"panda_keyword",
		"search_option",
		"fire_lv",
		"is_search",
		"serif_relation_retweet",
		"search_mode",
		"is_news",
		"news_keyword",
		"is_reply",
		"is_replyreply",
		"is_wasshoi",
		"reply_retweet",
		"followback",
		"memo",
	);

	public $csvColumn = array(
		"target_character_id",
		"search_keyword",
		"panda_keyword",
		"news_keyword",
	);

	public $keywordLimit = 15;



	public function FindByID($userID) {
		$query = $this->db->query("SELECT  * from twitter_users where id = ?", $userID);
		$users = $query->result_array();
		if(!empty($users)) {
			return $users[0];
		}
		return array();
	}

	public function GetUserByIDs($ids) {
		return $this->db->where_in("id", $ids)->get('twitter_users')->result_array();
	}

	public function GetPublicUsers() {
		return $this->db->query("SELECT  * from twitter_users where is_public = 1 and is_debug = 0")->result_array();
	}

	//リストページ表示用
	//sisters親機も表示
	public function GetPublicAndNotSisters() {
		return $this->db->query("SELECT  * from twitter_users 
			where is_public = 1  and parent_id = 0")->result_array();
	}

	//sistersを表示
	public function GetSisters() {
		return $this->db->query("SELECT  * from twitter_users 
			where is_public = 1 and is_debug = 0 
			and (parent_id < 0 OR parent_id > 0 )")->result_array();
	}

	//管理者用。デバッグユーザーも表示
	public function GetUsersByAdmin() {
		return $this->db->query("SELECT  * from twitter_users 
			where (is_public = 1 OR is_debug = 1) ")->result_array();
	}

	public function GetPublicParents() {
		return $this->db->query("SELECT  * from twitter_users where is_public = 1 and is_debug = 0 and parent_id = -1")->result_array();
	}

	public function UpdateByID($id, $posts) {
		$this->db->update("twitter_users", $posts, array("id" => $id));
		echo $this->db->last_query();
		return;
	}

	public function SetDefault($posts) {
		foreach( $this->checkboxs as $checkbox ) {
			$posts[$checkbox] = $this->check_checkbox($posts, $checkbox);
		}
		return $posts;
	}

	//対象parentIDを持つユーザーを親の設定にコピー
	public function CopyParentByID($parentID) {
		$parent = $this->FindByID($parentID);

		//更新対象のカラムを全てアップデート
		$update = array();
		foreach ($this->updateColumn as $col) {
			$update[$col] = $parent[$col];
		}
		$update["is_deleted"] = $parent["is_deleted"];
		unset($update["memo"]); //memoは対象外

		$this->db->update("twitter_users", $update, array("parent_id" => $parentID));
	}


	/**
	 * postの内容をバリデーション
	 * @param $posts
	 * @return array
	 * @throws \Exception
	 */
	public function ValidationUpdate($posts) {
		$posts = $this->SetDefault($posts);

		//更新対象Paramを格納
		$ups = array();
		foreach($this->updateColumn as $col) {
			if(isset($posts[$col])) {
				//csvなら空白をtrim
				if(in_array($col,$this->csvColumn)) {
					$posts[$col] = $this->csvTrim($posts[$col]);
				}

				$ups[$col] = strip_tags(trim($posts[$col]));
			}
		}

		// TODO: 不正な値かどうかをチェック。あとですべての項目に対して行う必要あり !!!
		if($ups["serif_rate"] > 100 || $ups["serif_rate"] < 0 ) {
			throw new Exception("セリフの実行確率が間違っています");
		}

		if($ups["search_rate"] > 100 || $ups["search_rate"] < 0 ) {
			throw new Exception("トレンドを検索の実行確率が間違っています");
		}

		// 制限チェック
		if($ups["fire_lv"] > 20) {
			throw new Exception("リアクション数が多すぎます");
		}
		if($ups["search_count"] > 15) {
			throw new Exception("ツイートするランキング数が多すぎます");
		}

		// TODO: 組み合わせのエラー
		if($ups["is_news"] == 1 && empty($ups["news_keyword"]) ) {
			throw new Exception("ニュースを検索する場合は、ニュース検索キーワードを指定してください");
		}
		if(!empty($ups["news_keyword"])) {
			$spaceCount = explode(",", $ups["news_keyword"]);
			if(count($spaceCount) > $this->keywordLimit) {
				throw new Exception("ニュース検索キーワードはカンマ区切りで ". $this->keywordLimit . "つまで指定できます");
			}
		}

		if($ups["is_search"] == 1 && empty($ups["search_keyword"])) {
			//トレンド検索の場合（リアルタイム検索でない場合）
			if( $ups["search_mode"] == 2 || $ups["search_mode"] == 3 ) {
				throw new Exception("トレンドを検索する場合は、トレンド検索キーワードを指定してください");
			}
		}
		if(!empty($ups["search_keyword"])) {
			$spaceCount = explode(",", $ups["search_keyword"]);
			if(count($spaceCount) > $this->keywordLimit) {
				throw new Exception("トレンド検索キーワードはカンマ区切りで ". $this->keywordLimit ."つまで指定できます");
			}
		}

		if($ups["is_reply"] == 1 && (empty($ups["reply_retweet"]) && empty($ups["is_replyreply"])) ) {
			throw new Exception("リプライアクションを実行する場合、リプライアクションを最低１つは指定してください");
		}


		if($ups["character_mode"] == CHARA_MODE_TWITTER_USER && empty($ups["target_screen_name"])) {
			throw new Exception("性格をTwitterユーザーにする場合は、Twitterユーザー名（スクリーンネーム）を指定してください");
		}
		else if($ups["character_mode"] == CHARA_MODE_STORY_USER && empty($ups["target_character_id"])) {
			throw new Exception("性格をキャラクターにする場合は、キャラクターを指定してください");
		}



		//screen_nameからuser_idを取得
		if($ups["character_mode"] == CHARA_MODE_TWITTER_USER ) {
			$twitter = $this->twitter_model->NewObject($this->session_model->UserID());
			$res = $twitter->get("users/show", array("screen_name" => $ups["target_screen_name"]));
			if(!isset($res->id_str) || empty($res->id_str)) {
				throw new Exception("Twitterユーザーが見つかりません。存在しないTwitterユーザー名が入力されています");
			}

			$ups["target_screen_name"] = $posts["target_screen_name"];
			$ups["target_user_id"] = $res->id_str;
			unset($ups["target_character_id"]);	//twitter userを更新する場合キャラクターは更新しない
		}
		else if($posts["character_mode"] == CHARA_MODE_STORY_USER ) {
			//キャラクターIDが正しいものかチェック
			$character_ids = explode(",", $ups["target_character_id"]);
			$maxID = $this->acharacter_model->FindMaxID();
			foreach($character_ids as $character_id) {
				if ($character_id > $maxID || $character_id < 0) {
					throw new Exception("不正なキャラクターが指定されています");
				}
			}

			unset($ups["target_screen_name"]);	//キャラクターを更新する場合、Twitter Userは更新しない
		}

		if($ups["is_reply"] == 1 && $ups["is_replyreply"] == 1 && empty($ups["character_mode"])) {
			throw new Exception("「リプライにお返事」する場合、キャラクターの性格を設定する必要があります");
		}

		if(isset($posts["main_status"]) && $posts["main_status"] == 1){
			$ups["is_deleted"] = $this::$StatusReady;	//go側の処理で0にする。check後、action済みにする
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


	//csv文字列から空白を削除して返す
	function csvTrim($str) {
		$csvs = explode(",", $str);
		$ret = array();
		foreach	($csvs as $csv) {
			//全角スペースをtrimしたいので、半角スペースに変換したあとtrim
			$ret[] = trim(mb_convert_kana($csv, "s", 'UTF-8'));
		}
		return implode(",", $ret);
	}
}
