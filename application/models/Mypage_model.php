<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

class Mypage_model extends CI_Model {
	//session_start() はコントローラーでやる
	public function __construct()
	{
		parent::__construct();
	}



	// エンドユーザー登録用のアプリを１つ取得
	public function MakeUserTpl($CI, $appID=0) {
		$appID = $this->checkAppID($appID);
		$this->GetBaseTemplate($CI);
		$CI->vd["appuser"] = $this->appuser_model->FindByID($appID);
		$CI->vd["characters"] = $this->acharacter_model->FindByStoryID(1);
		$CI->vd += $this->session_model->GetFlash();
	}

	/**
	 * マイページのBaseテンプレート
	 */
	public function GetBaseTemplate($CI, $mypage=true) {
		$data = $CI->vd;

		$data["jsBase"] = $this->load->view('general/parts/js_base', '', TRUE);

		if($mypage && $this->session_model->IsLogin()) {
			$data["my_pages"] = $this->config->item("my_pages");
		} else {
			$data["my_pages"] = $this->config->item("general_pages");
		}

		if($this->session_model->IsLogin()) {
			$data["user_data"] = $this->user_model->FindByID($this->session_model->UserID());
		}
		$data["navBar"] = $this->load->view('admin/parts/nav_bar', $data, TRUE);
		$data["sideBar"] = $this->load->view('admin/parts/side_bar', $data, TRUE);

//		$data["fixedSetting"] = $this->load->view('general/parts/fixed_setting', '', TRUE);
		$data["footer"] = $this->load->view('general/parts/footer', '', TRUE);
		$CI->vd = $data;
		return $CI->vd;
	}

	//権限チェック。表示のみ
	public function checkAppID($CI, $appID) {
		if(empty($appID)) {
			$appID = $CI->defaultAppID;
		}

		if(!in_array($appID, $CI->myApps)) {
			echo "アプリの編集が許可されていません";
			exit;
		}
		return $appID;
	}

	public function checkAppIDEdit($CI, $appID) {

	}

}
