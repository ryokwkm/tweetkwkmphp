<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function index($beforeDay=0)
	{
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $beforeDay - 1, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $beforeDay, date('Y')));

		$this->load->database();
		$query = $this->db->query(
			'SELECT * FROM t_kwkmlight_tweet_logs WHERE created > ? AND created < ? AND parent_id = 0',
			array($targetStart, $targetEnd));
		$topArticles = $query->result_array();

		foreach($topArticles as $key => $topArticle ) {
			$reactions = $this->db->query(
				'SELECT * FROM t_kwkmlight_tweet_logs WHERE created > ? AND created < ? AND parent_id = ?',
				array($targetStart, $targetEnd, $topArticle["id"]))->result_array();

			$topArticles[$key]["reactions"] = $reactions;
		}

		var_dump($topArticles);
//		echo json_encode($result);
	}

}
