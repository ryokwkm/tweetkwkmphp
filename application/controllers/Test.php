<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Test extends CI_Controller {

	/**
	 * index
	 */
	public function index()
	{
		session_start();
		$this->load->database();

		//login.phpでセットしたセッション
		$request_token = [];  // [] は array() の短縮記法。詳しくは以下の「追々記」参照
		$request_token['oauth_token'] = $_SESSION['oauth_token'];
		$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
		//OAuth トークンも用いて TwitterOAuth をインスタンス化
		$app_name = $_SESSION['account_name'];
		$twitterApps = $this->getApp();
		$twitterApp = $twitterApps[0];

	}
	//情報取得
	protected function getApp() {
		$query = $this->db->query("SELECT * from twitter_apps where id = 14");
		return $query->result_array();
	}
}
