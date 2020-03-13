<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	//session_start() はコントローラーでやる
	public function __construct()
	{
		parent::__construct();
	}


	public function FindByID($userID) {
		$query = $this->db->query("SELECT  * from twitter_end_users where user_id = ?", $userID);
		$users = $query->result_array();
		if(!empty($users)) {
			return $users[0];
		}
		return array();
	}
}
