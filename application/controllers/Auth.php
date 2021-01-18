<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Auth extends MY_Controller {
	// ログインを許可するアプリ。エンドユーザー用

	public $loginApp = "magialogin";	//

	// ソース側で定義してあるユーザーの場合、end_userではない
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

		$d = array();
		//通常のBot作成
		$d["account_name"] = $this->config->item("create_app");
		$d["title"] = "Regist";
		$data["contents"] = $this->load->view('admin/regist', $d, TRUE);

		$this->load->view('admin/noside_base', $data);
	}

	//sisters作成
	public function sisters_regist()
	{
		$data = $this->getBaseTemplate();

		$d = array();
		//Sisters
		$d["account_name"] = $this->config->item("create_app_sisters");
		$d["title"] = "Sisters登録";
		$users = $this->appuser_model->GetPublicParents();
		foreach ($users as $user) {
			$d["users"][] = array("id"=>$user["id"], "name"=>$user["name"]);
		}

		$data["contents"] = $this->load->view('admin/regist', $d, TRUE);

		$this->load->view('admin/noside_base', $data);
	}

	public function logout() {
		session_destroy();
		header( 'location: /' );
	}



	/**
	 * ログイン、Botの作成など、全てはここを経由して行われる！
	 */
	public function login()
	{
		//バリデーション
		$appName = $this->input->post('account_name');

		$appName = $this->setAuthSession($appName);


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
		$_SESSION['account_name'] = $appName;
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		//Twitter.com 上の認証画面のURLを取得( この行についてはコメント欄も参照 )
		$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));

		//Twitter.com の認証画面へリダイレクト
		header( 'location: '. $url );
	}

	//認証タイプを判定し、sessionをセット。$appName を返却
	private function setAuthSession($appName) {
		switch ($appName) {
			//enduserによる作成
			case $this->config->item("create_app"):
				$_SESSION['create_mode'] = $this->config->item("create_app");

				$appName = $this->twitter_model->GetEnduserNewApp();
				if(empty($appName)) {
					echo " 現在アクセスが集中しています。アプリの作成が出来ません <br> 管理者にお問い合わせ下さい";
					exit;
				}
				break;

			//Sisters作成
			case $this->config->item("create_app_sisters"):
				$_SESSION['create_mode'] = $this->config->item("create_app_sisters");
				$_SESSION['parent_id'] = $this->input->post('parent_id');

				$appName = $this->twitter_model->GetSistersuserNewApp();
				if(empty($appName)) {
					echo " 現在アクセスが集中しています。アプリの作成が出来ません <br> 管理者にお問い合わせ下さい";
					exit;
				}
				break;

			//管理者による作成
			case $this->config->item("create_app_admin"):
				//逐一ソースをいじって追加していきます
				if(in_array($appName, $this->myApp)) {
					$_SESSION['create_mode'] = $this->config->item("create_app_admin");
				}
				break;

			//ログイン
			case $this->loginApp:
				$_SESSION['create_mode'] = $this->config->item("login_app");
				break;

			default:
				echo '不正アクセス';
				exit;

		}

		return $appName;
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


		if($_SESSION['create_mode'] == $this->config->item("create_app") ||
				$_SESSION['create_mode'] == $this->config->item("create_app_admin") ||
				$_SESSION['create_mode'] == $this->config->item("create_app_sisters")
		){
			//作成
			$data = array(
				"name" => $twitterProfile->name,
				"user_id" => $_SESSION['auth']["user_id"],
				"app_id" => $twitterApp["id"],
				"account_name" => $twitterProfile->screen_name,
				"access_token" => $_SESSION['auth']["oauth_token"],
				"access_secret" => $_SESSION['auth']["oauth_token_secret"],
				"function_id" => 15,  //マスターモード
				"language_id" => 95,  //日本語
				"location_id" => 1,  //日本
				"is_deleted" => 1,	// 削除状態で作成
				"is_public" => 1,	// リストにて公開
			);
			//管理者による作成の場合、管理者フラグをOn
			if($_SESSION['create_mode'] == $this->config->item("create_app_admin")) {
				$data["is_admin"] = 1;
			}
			//sisters
			if($_SESSION['create_mode'] == $this->config->item("create_app_sisters")) {
				$data["parent_id"] = $_SESSION['parent_id'];
			}
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
		header('location: /mypage/index');
	}

}
