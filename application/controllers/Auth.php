<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Auth extends MY_Controller {
	// ログインを許可するアプリ。エンドユーザー用
	public $loginApp = array("magialogin");

	// ソース側で定義してあるユーザーの場合、end_userではない
	public $myUser = array();	//array("priconnekwkm");
	public $myApp = array();	//array("priconne");



	public function __construct() {
		parent::__construct();
	}


	public function index()
	{
		$data = $this->getBaseTemplate();
		$data["contents"] = $this->load->view('admin/login', '', TRUE);
		$this->load->view('admin/noside_base', $data);
	}
	public function regist()
	{
		$data = $this->getBaseTemplate();
		$data["contents"] = $this->load->view('admin/regist', '', TRUE);
		$this->load->view('admin/noside_base', $data);
	}

	public function logout() {
		session_destroy();
		header( 'location: /auth/index' );
	}



	/**
	 * Login
	 * @throws \Abraham\TwitterOAuth\TwitterOAuthException
	 */
	public function login()
	{
		//バリデーション
		$appName = $this->input->post('account_name');
		if( !in_array($appName, $this->loginApp) && !in_array($appName, $this->myApp) ){
			echo $appName. " それは不正アクセス";
			exit;
		}

		$twitterApps = $this->getApp($appName);
		if(empty($twitterApps)) {
			echo " アプリが見つからない";
			exit;
		}
		$twitterApp = $twitterApps[0];
		//TwitterOAuth をインスタンス化
		$connection = new TwitterOAuth($twitterApp["consumerkey"], $twitterApp["consumersecret"]);

		//コールバックURLをここでセット
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

		//callback.phpで使うのでセッションに入れる
		$_SESSION['account_name'] = $appName; //magialogin
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		//Twitter.com 上の認証画面のURLを取得( この行についてはコメント欄も参照 )
		$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));

		//Twitter.com の認証画面へリダイレクト
		header( 'location: '. $url );
	}


	/**
	 * callback
	 */
	public function callback()
	{
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
		$twitterApps = $this->getApp($app_name);
		$twitterApp = $twitterApps[0];

		$key = $twitterApp["consumerkey"];
		$secret = $twitterApp["consumersecret"];
		$connection = new TwitterOAuth($key, $secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);

		//アプリでは、access_token(配列になっています)をうまく使って、Twitter上のアカウントを操作していきます
		try {
			$_SESSION['auth'] = $connection->oauth("oauth/access_token", array("oauth_verifier" => $this->input->get("oauth_verifier", false)));
		} catch (\Exception $e) {
			//\Fuel\Core\Log::error($e->getMessage());
			vr($e->getMessage());
			exit;
		}


		$twitterProfile = $this->twitter_model->getTwitterProfile($_SESSION['auth']["user_id"], $_SESSION['auth']["oauth_token"], $_SESSION['auth']["oauth_token_secret"], $_SESSION["account_name"]);

		// アプリがエンドユーザーに許可されたものか
		if(!in_array($_SESSION["account_name"], $this->loginApp)) {
			// そうでない場合、俺か？許可したTwitterユーザーであるか確認
			if(!in_array($twitterProfile->screen_name, $this->myUser)) {
				$errMsg = "ログイン先:". $_SESSION["account_name"].  " ユーザー：". $twitterProfile->screen_name;
				echo $errMsg. "<br> 不正な動作を検知しました";
				exit;
			}
		}


		if(in_array($twitterProfile->screen_name, $this->myUser)) {
			//BOT
			$data = array(
				"name" => $twitterProfile->name,
				"user_id" => $_SESSION['auth']["user_id"],
				"app_id" => $twitterApp["id"],
				"account_name" => $twitterProfile->screen_name,
				"access_token" => $_SESSION['auth']["oauth_token"],
				"access_secret" => $_SESSION['auth']["oauth_token_secret"],
				"function_id" => 15,  //マスターモード
				"language_id" => 95,  //日本語
			);
			$this->db->replace("twitter_users", $data);
		}

		//エンドユーザー
		$data = array(
			"app_id" => $twitterApp["id"],
			"user_id" => $_SESSION['auth']["user_id"],
			"access_token" => $_SESSION['auth']["oauth_token"],
			"access_secret" => $_SESSION['auth']["oauth_token_secret"],
			"screen_name" => $twitterProfile->screen_name,
			"display_name" => $twitterProfile->name,
			"image_url" => $twitterProfile->profile_image_url_https,
		);
		$this->db->replace("twitter_end_users", $data);


//		$twitterProfile = $this->getTwitterProfile($_SESSION['auth']["user_id"], $_SESSION['auth']["oauth_token"], $_SESSION['auth']["oauth_token_secret"], $_SESSION["account_name"]);

		//ログイン状態にする
		$_SESSION["is_login"] = TRUE;
		//マイページへリダイレクト
		header('location: /mypage/');
	}

}
