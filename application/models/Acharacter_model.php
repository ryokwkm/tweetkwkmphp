<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acharacter_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}


	public function FindByStoryID($id) {
		$query = $this->db->query("SELECT  * from a_character_names where story_id = ?", $id);

		//フロント側で表示するため、参考情報を削除
		$ret = array();
		foreach ($query->result_array() as $character) {
			unset($character["url"]);
			$ret[] = $character;
		}

		return $ret;
	}

	public function FindMaxID() {
		$query = $this->db->query("SELECT max(id) as id from a_character_names ");

		//フロント側で表示するため、参考情報を削除
		$ret = 0;
		foreach ($query->result_array() as $character) {
			$ret = $character["id"];
		}

		return $ret;
	}

}
