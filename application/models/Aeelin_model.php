<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aeelin_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public $table = "a_eelin_tweet_logs";


	//マルコフユーザーが更新された場合、古い情報を削除
	public function DeleteByUserID($userID) {
		$this->db->delete($this->table, array("user_id" => $userID));
	}

}
