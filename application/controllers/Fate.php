<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fate extends MY_Controller {

	public $beforeDay = 0;
	public $country;
	public $appName = 7;

	public function get()
	{
		if($this->input->get("country") != NULL) {
			$country = $this->input->get("country");	//limit に使う
		}
		if($this->input->get("page") != NULL) {
			$beforeDay = $this->input->get("page");		//日付に使う
		}

		$this->beforeDay = $beforeDay;
		$this->country = $country;

		$topArticles = $this->getTopArticles();
		
		if (empty($topArticles)) {
			$this->beforeDay += 1;
			$topArticles = $this->getTopArticles();
			if (empty($topArticles)) {
				$this->beforeDay += 1;
				$topArticles = $this->getTopArticles();
			}
		}

		

		//html特殊文字を変換
		$topArticles = $this->htmlDecode($topArticles);
		//$topArticles = $this->sortById($topArticles);

//		var_dump($topArticles);
		header('Access-Control-Allow-Origin: *');
		echo json_encode($topArticles);
	}

	//情報取得
	protected function getTopArticles() {
		$tableName = $this->getTable();
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay + 1, date('Y')));

		$this->load->database();
		$keywords = $this->getTopKeywords();

		if(!empty($keywords)) {

			$query = $this->db->query(
				"SELECT * FROM {$tableName}
				WHERE created >= ? AND created < ?
				AND keyword IN ?
				",
				array($targetStart, $targetEnd, $keywords));
			$result = $query->result_array();
			
			$result = $this->keyword_sort($result, $keywords);
		}

		// echo $this->db->last_query();
		return $result;
	}

	protected function keyword_sort($articles, $keywords) {

		$ret = array();
		foreach($articles as $article ) {;
			foreach($keywords as $key => $keyword ) {
				if($article["keyword"] == $keyword){
					$article["favorite"] = (int)$article["favorite"];
					$ret[] = $article;
				}
			}
		}

		$favorites = array();
		foreach($ret as $key => $re ) {
			$favorites[] = $re["favorite"];
		}
		
		array_multisort($favorites, SORT_DESC, SORT_NUMERIC, $ret);
		
		return $ret;
	}

	protected function getTopKeywords() {
		$tableName = $this->getTable();
		$targetStart = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay, date('Y')));
		$targetEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') - $this->beforeDay + 1, date('Y')));

		$query = $this->db->query(
			"SELECT keyword FROM {$tableName}
			WHERE created >= ? AND created < ?
			GROUP BY keyword
			ORDER BY sum(favorite) DESC
			LIMIT ?,  5",
			array($targetStart, $targetEnd, (int)$this->country));
		$result = $query->result_array();

		$keywords = array();
		if (!empty($result)) {
			foreach($result as $value) {
				$keywords[] = $value["keyword"];
			}
		}

		return $keywords;
	}


}
