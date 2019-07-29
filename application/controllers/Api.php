<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public $beforeDay;
	public $country;

	public function get($beforeDay=0,$country=0)
	{
		if($this->input->get("country") != NULL) {
			$country = $this->input->get("country");
		}
		if($this->input->get("page") != NULL) {
			$beforeDay = $this->input->get("page");
		}

		$this->beforeDay = $beforeDay;
		$this->country = $country;


		$topArticles = $this->getTopArticles();
		if (empty($topArticles)) {
			$this->beforeDay += 1;
			$topArticles = $this->getTopArticles();
		}

		//html特殊文字を変換
		$topArticles = $this->htmlDecode($topArticles);
		$topArticles = $this->sortById($topArticles);

		$topArticles = $this->getChildArticles($topArticles);


//		var_dump($topArticles);
		header('Access-Control-Allow-Origin: *');
		echo json_encode($topArticles);
	}

	//情報取得
	private function getTopArticles() {
		$tableName = $this->getTable();
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay + 1, date('Y')));

		$this->load->database();
		$query = $this->db->query(
			"SELECT * FROM {$tableName}
			WHERE created >= ? AND created < ?
			AND country = ?
			AND parent_id = 0",
			array($targetStart, $targetEnd, $this->country));
		return $query->result_array();
	}

	private function getChildArticles($topArticles) {
		$tableName = $this->getTable();
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay + 1, date('Y')));

		foreach($topArticles as $key => $topArticle ) {
			$reactions = $this->db->query(
				"SELECT * FROM {$tableName}
				WHERE created >= ? AND created < ?
				AND country = ?
				AND parent_id = ?",
				array($targetStart, $targetEnd, $this->country, $topArticle["id"]))->result_array();

			//いいね順にソート。Html特殊文字変換
			$reactions = $this->htmlDecode($reactions);
			$reactions = $this->sortById($reactions, "favorite");
			$topArticles[$key]["reactions"] = $reactions;
		}
		return $topArticles;
	}

	//ソート
	private function sortById($articles, $keyName="id") {
		if(empty($articles)) return $articles;
		foreach ($articles as $key => $value) {
		    $sort[$key] = $value[$keyName];
		}

		array_multisort($sort, SORT_DESC, $articles);
		return $articles;
	}

	//html特殊文字をもとに戻す
	private function htmlDecode($articles) {
		foreach ($articles as $key => $article) {
			$articles[$key]["head"] = str_replace(array('&nbsp;', '&emsp;', '&ensp;'), '', $articles[$key]["head"]);
			$articles[$key]["body"] = str_replace(array('&nbsp;', '&emsp;', '&ensp;'), '', $articles[$key]["body"]);
			$articles[$key]["head"] = htmlspecialchars_decode($articles[$key]["head"], ENT_QUOTES);
			$articles[$key]["body"] = htmlspecialchars_decode($articles[$key]["body"], ENT_QUOTES);
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
