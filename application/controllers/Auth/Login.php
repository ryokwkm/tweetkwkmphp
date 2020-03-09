<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Login extends CI_Controller {

	public function index()
	{
		$this->load->library('session');

		$twitterApps = $this->getApp();
		$twitterApp = $twitterApps[0];
		//TwitterOAuth をインスタンス化
		$connection = new TwitterOAuth($twitterApp["consumerkey"], $twitterApp["consumersecret"]);

		//コールバックURLをここでセット
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

		//callback.phpで使うのでセッションに入れる
		$_SESSION['account_name'] = "magialogin"; //$_POST['account_name']; FIXME:POSTにする
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		//Twitter.com 上の認証画面のURLを取得( この行についてはコメント欄も参照 )
		$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));

		//Twitter.com の認証画面へリダイレクト
		header( 'location: '. $url );
	}


	//情報取得
	protected function getApp() {
		$this->load->database();
		$query = $this->db->query("SELECT * from twitter_apps where id = 14");
		return $query->result_array();
	}
}
