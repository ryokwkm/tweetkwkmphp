<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Userfollowers_model extends CI_Model {
	public static $StatusReady = 3;
	public function __construct()
	{
		parent::__construct();
	}


	public function FindByDate($start="", $end="", $ids=array()) {
		$this->db->select('*');
		$this->makeFindQuery($start, $end, $ids);
		$query = $this->db->get();

		return $query->result_array();
	}

	//キー用に日付のみ取得
	public function FindByDateOnly($start="", $end="", $ids=array()) {
		$this->db->select('created');
		$this->makeFindQuery($start, $end, $ids);
		$this->db->group_by("created");
		$query = $this->db->get();
		$dateTerm = $query->result_array();

		//値のみ返すようにする
		$ret = array();
		foreach ($dateTerm as $date) {
			$ret[] = $date["created"];
		}

		return $ret;
	}

	private function makeFindQuery($start="", $end="", $ids=array()) {
		if(empty($start)) {
			$start = date('Y-m-d', strtotime("-1 month"));
		}
		if(empty($end)) {
			$end = date('Y-m-d');
		}
		$this->db->from('twitter_user_followers');
		$this->db->where("created >=", $start);
		$this->db->where("created <=", $end);
		if(!empty($ids)) {
			$this->db->where_in("user_id", $ids);
		}
	}
}
