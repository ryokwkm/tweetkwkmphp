<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LAction_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public $table = "l_action_count";


	public function FindByDate($start="", $end="", $ids=array()) {
		$this->db->select('*');
		$this->makeFindQuery($start, $end, $ids);
		$query = $this->db->get();

		return $query->result_array();
	}


	private function makeFindQuery($start="", $end="", $ids=array()) {
		if(empty($start)) {
			$start = date('Y-m-d', strtotime("-1 month"));
		}
		if(empty($end)) {
			$end = date('Y-m-d');
		}
		$this->db->from($this->table);
		$this->db->where("created >=", $start);
		$this->db->where("created <=", $end);
		if(!empty($ids)) {
			$this->db->where_in("user_id", $ids);
		}
	}
}
