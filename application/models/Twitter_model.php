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
}
