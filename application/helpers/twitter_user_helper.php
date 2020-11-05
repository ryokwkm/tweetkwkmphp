<?php

function TwitterFunc($mode) {
	switch ($mode) {
		case 1:
			return "スポーツ";
			break;
		case 2:
			return "Fate";
			break;
		case 3:
			return "エンタメ";
			break;
		case 9:
			return "サーチ";
			break;
		case 10:
			return "えーりん";
			break;
		case 12:
			return "キャラ";
			break;
	}
}

function IsAdminIP() {
	if(in_array($_SERVER["REMOTE_ADDR"], array('123.224.73.239', '127.0.0.1'))) {
		return true;
	}
	return false;
}

function TwitterMode($mode) {
	switch ($mode) {
		case 0:
			return "tweet";
			break;
		case 1:
			return "only search";
			break;
	}
}

function OnOffButton($value) {
	if($value>=1) {
		return '<button type="button" class="btn btn-success">ON</button>';
	} else {
		return '<button type="button" class="btn btn-secondary">OFF</button>';
	}
}

function PowerButton($is_deleted) {
	if($is_deleted==1) {
		return '<button type="button" class="btn btn-secondary">OFF</button>';
	} else {
		return '<button type="button" class="btn btn-success">ON</button>';
	}
}

function ConvertJson($array) {
	return json_encode($array, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

