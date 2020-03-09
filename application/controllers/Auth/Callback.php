<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Callback extends CI_Controller {

	public function index()
	{
			$this->load->library('session');
			$this->load->database();

			//login.phpでセットしたセッション
			$request_token = [];  // [] は array() の短縮記法。詳しくは以下の「追々記」参照
			$request_token['oauth_token'] = $_SESSION['oauth_token'];
			$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

			//Twitterから返されたOAuthトークンと、あらかじめlogin.phpで入れておいたセッション上のものと一致するかをチェック
			if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
				die( 'Error!' );
			}

			//OAuth トークンも用いて TwitterOAuth をインスタンス化
			$app_name = $_SESSION['account_name'];
			$twitterApps = $this->getApp();
			$twitterApp = $twitterApps[0];

			$key = $twitterApp["consumerkey"];
			$secret = $twitterApp["consumersecret"];
			$connection = new TwitterOAuth($key, $secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);

			//アプリでは、access_token(配列になっています)をうまく使って、Twitter上のアカウントを操作していきます
			try {
				$_SESSION['access_token'] = $connection->oauth("oauth/access_token", array("oauth_verifier" => $this->input->get("oauth_verifier", false)));
			} catch (\Exception $e) {
				//\Fuel\Core\Log::error($e->getMessage());
				vr($e->getMessage());
				exit;
			}

			/*
			ちなみに、この変数の中に、OAuthトークンとトークンシークレットが配列となって入っています。
			*/
			$data = array(
				"app_id" => $twitterApp["id"],
				"user_id" => $_SESSION['access_token']["user_id"],
				"screen_name" => $_SESSION['access_token']["screen_name"],
				"access_token" => $_SESSION['access_token']["oauth_token"],
				"access_secret" => $_SESSION['access_token']["oauth_token_secret"],
			);
			$this->db->insert("twitter_end_users", $data);

			//ログイン状態にする
			$_SESSION["is_login"] = true;
			//マイページへリダイレクト
			header( 'location: /auth/index' );

	}


	//情報取得
	protected function getApp() {
		$query = $this->db->query("SELECT * from twitter_apps where id = 14");
		return $query->result_array();
	}

	protected function saveEndUser($appId) {

		$this->db->insert();
	}
}
