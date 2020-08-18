<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
//		$this->debugMode();
		$this->vd = $this->getBaseTemplate(false);
		$this->vd["general"] = true;
	}

	public function index()
	{
		$this->list();
	}

	public function list()
	{

		$this->vd += $this->session_model->GetFlash();


		$this->vd["twitterUsers"] = $this->appuser_model->GetPublicUsers();

		$this->vd["contents"] = $this->load->view('general/list', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function followers()
	{

		$this->vd += $this->session_model->GetFlash();

		$start = date('Y-m-d', strtotime("-10 days"));
		$end = date('Y-m-d');
		$followers = $this->userfollowers_model->FindByDate($start, $end, array(16, 17, 18, 19));
		//ラベル用に日付を取得
		$dateTerm = $this->userfollowers_model->FindByDateOnly($start, $end, array(16, 17, 18, 19));

		//ユーザー名と結合、データ整形。不要なデータがあった場合は削除
		$users = $this->appuser_model->GetPublicUsers();
		$userFollowers = array();
		foreach ($users as $user) {
			$userFollowers[$user["id"]] = array(
				"user_id" => $user["user_id"],
				"name" => $user["name"],
				"followers" => array(),
			);
		}

		foreach ($followers as $follower) {
			$user_id = $follower["user_id"];
			$created = $follower["created"];
			$userFollowers[$user_id]["followers"][$created] = $follower["follower"];
		}

		//データが無いものを削除
		foreach ($userFollowers as $key => $userFollower) {
			if(count($userFollower["followers"]) <= 1) {
				unset($userFollowers[$key]);
			}
		}

		//日付分のデータがなければ、ない部分は0で埋める
		foreach ($userFollowers as $key => $userFollower) {
			foreach ($dateTerm as $date) {
				if(!isset($userFollower["followers"][$date])) {
					$userFollowers[$key]["followers"][$date] = 0;
				}
			}
		}

		//ソート
		foreach ($userFollowers as $key => $userFollower) {
			ksort($userFollowers[$key]["followers"]);
		}
		$this->vd["userFollowers"] = $userFollowers;

		$this->vd["contents"] = $this->load->view('general/followers', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

	public function view($appID)
	{
		if(empty($appID)) {
			echo "エラー";
			exit;
		}

		$this->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$this->vd["characters"] = $this->acharacter_model->FindByStoryID(1);
		$this->vd += $this->session_model->GetFlash();
		$this->vd["contents"] = $this->load->view('admin/user', $this->vd, TRUE);
		$this->load->view('admin/base', $this->vd);
	}

}
