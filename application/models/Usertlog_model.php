<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//UserTwitterLogの略
class Usertlog_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * アクション済みにする。これをしないと今までの未返信ツイート全てに対してActionすることになり、大量の通知を飛ばすことになる
	 * @return bool
	 */
	public function UpdateActioned($userID) {
		$result = $this->db->simple_query("
				update a_user_tweet_logs set is_action = 1 
				where user_id = ? 
				AND tweet_user_id <> ?
				AND is_action = 0", $userID, $userID);
		return $result;
	}

}
