<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ACharacter_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}


	public function FindByStoryID($id) {
		$query = $this->db->query("SELECT  * from a_character_names where story_id = ?", $id);
		return $query->result_array();
	}

}
