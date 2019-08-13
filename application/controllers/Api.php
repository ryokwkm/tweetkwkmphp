<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

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
		$this->appName = $this->input->get("v");


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
		$topArticles = $this->sortById($topArticles);

		$topArticles = $this->getChildArticles($topArticles);


//		var_dump($topArticles);
		header('Access-Control-Allow-Origin: *');
		echo json_encode($topArticles);
	}




}
