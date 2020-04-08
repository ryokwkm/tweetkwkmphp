<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appuser_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	//0 or 1 の要素。バリデーションに使用
	//type=checkbox の場合、checkが入っていない場合は値が送信されない。そのため、値が無い場合は０で更新
	public $checkboxs = array(
		"is_search",
		"is_news",
		"is_reply",
		"is_replyreply",
		"reply_retweet",
		"followback"
	);


	public function FindByID($userID) {
		$query = $this->db->query("SELECT  * from twitter_users where id = ?", $userID);
		$users = $query->result_array();
		if(!empty($users)) {
			return $users[0];
		}
		return array();
	}

	public function UpdateByID($posts, $id) {
		foreach( $this->checkboxs as $checkbox ) {
			$posts[$checkbox] = $this->check_checkbox($posts, $checkbox);
		}

		return $this->db->update("twitter_users", $posts, array("id" => $id));
	}

	public function check_checkbox($params, $name) {
		if(!isset($params[$name])) {
			return 0;
		}
		return $params[$name];
	}
}
