<?php

if (!function_exists('bustAsset')){


	function bustAsset($file){
		static $buster;

		if (is_null($buster)) {
			$buster = json_decode(file_get_contents(public_path('busters.json')), true);
		}

		$path = '/'.trim($file, '/');
		if (isset($buster[$file])) {
			$path .= '?v='.trim($buster[$file], '/');
		}

		return $path;
	}

}