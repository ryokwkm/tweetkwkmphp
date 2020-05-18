<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$route['default_controller'] = 'welcome';
$route['admin/(:any)'] = "admin/$1";
//$route['auth/index'] = "auth/auth/index";
//$route['auth/(:any)'] = "auth/$1";
$route['mypage/list'] = "mypage2/list";

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
