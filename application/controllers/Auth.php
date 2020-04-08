<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Auth extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}


	/**
	 * index
	 */
	public function index()
	{

		$data = $this->getBaseTemplate();
		$data["contents"] = $this->load->view('admin/login', '', TRUE);
		$this->load->view('admin/noside_base', $data);
	}
//
//	public function logout() {
//		session_destroy();
//		header( 'location: /auth/index' );
//	}
//
//
//
//	/**
//	 * Login
//	 * @throws \Abraham\TwitterOAuth\TwitterOAuthException
//	 */
//	public function login()
//	{
//		//バリデーション
//		$appName = $this->input->post('account_name');
//		if($appName != "magialogin") {
//			vr($appName);
//			echo "エラーです";
//			exit;
//		}
//
//		$twitterApps = $this->getApp($appName);
//		$twitterApp = $twitterApps[0];
//		//TwitterOAuth をインスタンス化
//		$connection = new TwitterOAuth($twitterApp["consumerkey"], $twitterApp["consumersecret"]);
//
//		//コールバックURLをここでセット
//		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
//
//		//callback.phpで使うのでセッションに入れる
//		$_SESSION['account_name'] = $appName; //magialogin
//		$_SESSION['oauth_token'] = $request_token['oauth_token'];
//		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
//
//		//Twitter.com 上の認証画面のURLを取得( この行についてはコメント欄も参照 )
//		$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
//
//		//Twitter.com の認証画面へリダイレクト
//		header( 'location: '. $url );
//	}
//
//
//	/**
//	 * callback
//	 */
//	public function callback()
//	{
//
//		//login.phpでセットしたセッション
//		$request_token = [];  // [] は array() の短縮記法。詳しくは以下の「追々記」参照
//		$request_token['oauth_token'] = $_SESSION['oauth_token'];
//		$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
//
//		//Twitterから返されたOAuthトークンと、あらかじめlogin.phpで入れておいたセッション上のものと一致するかをチェック
//		if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
//			die( 'Error!' );
//		}
//
//		//OAuth トークンも用いて TwitterOAuth をインスタンス化
//		$app_name = $_SESSION['account_name'];
//		$twitterApps = $this->getApp($app_name);
//		$twitterApp = $twitterApps[0];
//
//		$key = $twitterApp["consumerkey"];
//		$secret = $twitterApp["consumersecret"];
//		$connection = new TwitterOAuth($key, $secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);
//
//		//アプリでは、access_token(配列になっています)をうまく使って、Twitter上のアカウントを操作していきます
//		try {
//			$_SESSION['auth'] = $connection->oauth("oauth/access_token", array("oauth_verifier" => $this->input->get("oauth_verifier", false)));
//		} catch (\Exception $e) {
//			//\Fuel\Core\Log::error($e->getMessage());
//			vr($e->getMessage());
//			exit;
//		}
//
//		$twitterProfile = $this->getTwitterProfile($_SESSION['auth']["user_id"], $_SESSION['auth']["oauth_token"], $_SESSION['auth']["oauth_token_secret"]);
//		$data = array(
//			"app_id" => $twitterApp["id"],
//			"user_id" => $_SESSION['auth']["user_id"],
//			"access_token" => $_SESSION['auth']["oauth_token"],
//			"access_secret" => $_SESSION['auth']["oauth_token_secret"],
//			"screen_name" => $twitterProfile->screen_name,
//			"display_name" => $twitterProfile->name,
//			"image_url" => $twitterProfile->profile_image_url_https,
//		);
//		$this->db->replace("twitter_end_users", $data);
//
//		//ログイン状態にする
//		$_SESSION["is_login"] = true;
//		//マイページへリダイレクト
//		header( 'location: /mypage/index' );
//
//	}


	/**
	 * Twitterからプロフィール情報を取得する
	 * @return array|object
	 */
	function getTwitterProfile($userID, $oauth_token, $oauth_token_secret) {
		$query = $this->db->query("
			SELECT a.*, u.screen_name 
			from twitter_end_users u 
			INNER JOIN twitter_apps a
			ON u.app_id = a.id   
			where u.user_id = ?", $userID);
		$app = $query->row();


		//twitterのプロフィールを取得
		$connection = new TwitterOAuth($app->consumerkey, $app->consumersecret, $oauth_token, $oauth_token_secret);
		$user_data = $connection->get("users/show", array("screen_name" => $app->screen_name));
		return $user_data;
	}


}
