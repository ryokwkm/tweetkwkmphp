<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Params  {

	public function OverWrite($target, $new) {
		foreach ($target as $key => $value) {
			if(isset($new[$key])) {
				$target[$key] = $new[$key];
			}
		}
		return $target;
	}
}
