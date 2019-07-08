<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function get($beforeDay=0,$country=0)
	{
		if($this->input->get("country") != NULL) {
			$country = $this->input->get("country");
		}
		if($this->input->get("page") != NULL) {
			$beforeDay = $this->input->get("page");
		}

		$tableName = $this->getTable();
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $beforeDay, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $beforeDay + 1, date('Y')));

		$this->load->database();
		$query = $this->db->query(
			"SELECT * FROM {$tableName}
			WHERE created >= ? AND created < ?
			AND country = ?
			AND parent_id = 0",
			array($targetStart, $targetEnd, $country));
		$topArticles = $query->result_array();
		// echo $this->db->last_query();

		//html特殊文字を変換
		$topArticles = $this->htmlDecode($topArticles);
		$topArticles = $this->sortById($topArticles);

		foreach($topArticles as $key => $topArticle ) {
			$reactions = $this->db->query(
				"SELECT * FROM {$tableName}
				WHERE created >= ? AND created < ?
				AND country = ?
				AND parent_id = ?",
				array($targetStart, $targetEnd, $country, $topArticle["id"]))->result_array();

			//いいね順にソート。Html特殊文字変換
			$reactions = $this->htmlDecode($reactions);
			$reactions = $this->sortById($reactions, "favorite");
			$topArticles[$key]["reactions"] = $reactions;
		}

//		var_dump($topArticles);
		header('Access-Control-Allow-Origin: *');
		echo json_encode($topArticles);
	}

	//ソート
	private function sortById($articles, $keyName="id") {
		foreach ($articles as $key => $value) {
		    $sort[$key] = $value[$keyName];
		}

		array_multisort($sort, SORT_DESC, $articles);
		return $articles;
	}

	//html特殊文字をもとに戻す
	private function htmlDecode($articles) {
		foreach ($articles as $key => $article) {
			$articles[$key]["head"] = htmlspecialchars_decode($article["head"], ENT_QUOTES);
			$articles[$key]["body"] = htmlspecialchars_decode($article["body"], ENT_QUOTES);
		}
		return $articles;
	}

	private function getTable() {
		if(IsProduction()) {
			return "t_sportskwkm_tweet_logs";
		} else {
			return "t_kwkmlight_tweet_logs";
		}
	}

}
