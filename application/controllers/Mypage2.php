<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//mypageが膨れてきたので２を作成した
class Mypage2 extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session_model->IsLogin() == false ) {
			header( 'location: /auth/index' );
		}
	}


	public function index($appID=0)
	{

	}

	public function list($appID=0) {

	}

}
