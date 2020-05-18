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
