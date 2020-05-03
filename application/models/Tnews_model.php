<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tnews_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public $table = "t_news";


	//マルコフユーザーが更新された場合、古い情報を削除
	public function DeleteByUserID($userID) {
		$this->db->delete($this->table, array("user_id" => $userID));
	}

}
