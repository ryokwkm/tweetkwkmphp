<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter_model extends CI_Model {
	//session_start() はコントローラーでやる
	public function __construct()
	{
		parent::__construct();
	}


	// twitter_end_usersに登録済みユーザーの情報をTwitterAPIを使って取得
	public function NewObject($userID) {
		$query = $this->db->query("
			SELECT a.*, u.access_token, u.access_secret
			from twitter_end_users u 
			INNER JOIN twitter_apps a ON u.app_id = a.id   
			where u.user_id = ?", $userID);
		$app = $query->row();

		$connection = new TwitterOAuth($app->consumerkey, $app->consumersecret, $app->access_token, $app->access_secret);
		return $connection;
	}

	// エンドユーザー登録用のアプリを１つ取得
	public function GetEnduserNewApp() {
		$apps = $this->db->query("
			SELECT a.id, a.account_name, count(u.id) cnt
			FROM twitter_apps a
			LEFT JOIN twitter_users u on a.id = u.app_id and u.is_deleted <> 1
			WHERE a.is_enduser = 1
			GROUP BY a.id
			ORDER BY a.id
			")->result_array();

		foreach($apps as $app) {
			if($app["cnt"] < $this->config->item('create_app_limit')) {
				return $app["account_name"];
			}
		}
		return "";
	}

	/**
	 * Twitterからプロフィール情報を取得する
	 * @return array|object
	 */
	function GetTwitterProfile($userID, $oauth_token, $oauth_token_secret, $account_name) {
		$query = $this->db->query("
			SELECT *  
			from twitter_apps a
			where account_name = ?", $account_name);
		$app = $query->row();


		//twitterのプロフィールを取得
		$connection = new TwitterOAuth($app->consumerkey, $app->consumersecret, $oauth_token, $oauth_token_secret);
		$user_data = $connection->get("users/show", array("user_id" => $userID));
		return $user_data;
	}
}
