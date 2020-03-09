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

		$twitterApps = $this->getApp();
		$twitterApp = $twitterApps[0];
		//TwitterOAuth をインスタンス化
		$connection = new TwitterOAuth($twitterApp["consumerkey"], $twitterApp["consumersecret"]);
//
//		//コールバックURLをここでセット
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

		vr($twitterApp);

	}
	//情報取得
	protected function getApp() {
		$query = $this->db->query("SELECT * from twitter_apps where id = 14");
		return $query->result_array();
	}
}
