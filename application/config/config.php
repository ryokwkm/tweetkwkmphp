<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config["version"] = 1;
$config["site_title"] = "キュレーター・メーカー（仮）";

$config["create_app"] = "createapp";	//エンドユーザーによるBot作成
$config["create_app_admin"] = "createadmin"; //adminによるBot作成
$config["create_app_sisters"] = "createsisters"; //sister作成
$config["create_app_limit"] = 5;	//1つのTwitterアプリが担当できるエンドユーザー数 checkの間隔がネック。今は100s 置きに実行しているが、この間隔を増やせばここも増やせる。
$config["create_app_sisters_limit"] = 30;	//sistersで登録できるユーザー数

$config["login_app"] = "loginapp";

// title_only => true の場合、サイドバーには出さず、タイトル定義のみする
$config["my_pages"] = array(
	array(
		"url" => "/mypage/index",
		"icon" => "dashboard",
		"title" => "Curator list",
	),
	array(
		"url" => "/mypage/user",
		"icon" => "person",
		"title" => "Editing the Curator",
		"title_only" => true,
	),
	array(
		"url" => "/mypage/sisters",
		"icon" => "person",
		"title" => "Sisters",
	),

//	array(
//		"url" => "/mypage/test_user",
//		"icon" => "local_hospital",
//		"title" => "テスト",
//	),
);

$config["general_pages"] = array(
	array(
		"default" => true,
		"url" => "/user/index",
		"icon" => "person",
		"title" => "Users",
	),
	array(
		"url" => "/user/sisters",
		"icon" => "person",
		"title" => "Sisters",
	),

	array(
		"url" => "/user/view",
		"icon" => "person",
		"title" => "Curator details",
		"title_only" => true,
	),

	array(
		"url" => "/check/limit",
		"icon" => "timeline",
		"title" => "Users Active",
	),
	array(
		"url" => "/check/sisters_limit",
		"icon" => "timeline",
		"title" => "Sisters Active",
	),

	array(
		"url" => "/check/followers",
		"icon" => "auto_graph",
		"title" => "Users Follower",
	),

	array(
		"url" => "/check/sisters_followers",
		"icon" => "auto_graph",
		"title" => "Sisters Follower",
	),

);

$config['trend_genre'] = array(
	"s" => "スポーツ",
	"e" => "エンタメ",
	"b" => "ビジネス",
	"h" => "健康",
	"m" => "主要ニュース",
	"t" => "サイエンス＆テクノロジー",
	"all" => "全て",
);

$config['base_url'] = '';

$config['index_page'] = 'index.php';

$config['uri_protocol']	= 'REQUEST_URI';

$config['url_suffix'] = '';

$config['language']	= 'english';

$config['charset'] = 'UTF-8';

$config['enable_hooks'] = FALSE;

$config['subclass_prefix'] = 'MY_';

//$config['composer_autoload'] = APPPATH . 'vendor/abraham/twitteroauth/autoload.php'; // FCPATH . 'vendor/autoload.php';
$config['composer_autoload'] = true;

$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

$config['allow_get_array'] = TRUE;

$config['log_threshold'] = 0;

$config['log_path'] = '';

$config['log_file_extension'] = '';

$config['log_file_permissions'] = 0644;

$config['log_date_format'] = 'Y-m-d H:i:s';

$config['error_views_path'] = '';

$config['cache_path'] = '';

$config['cache_query_string'] = FALSE;

$config['encryption_key'] = '';

$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

$config['standardize_newlines'] = FALSE;

$config['global_xss_filtering'] = FALSE;

$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

$config['compress_output'] = FALSE;

$config['time_reference'] = 'local';

$config['rewrite_short_tags'] = FALSE;

$config['proxy_ips'] = '';
