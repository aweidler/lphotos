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

if(!function_exists('photoUrl')){

	function photoUrl($file, $drive = Photos\Http\Controllers\UploadController::DRIVER_MD){
		// Laravel has made this so complex for me
		if(method_exists(Storage::drive($drive)->getDriver()->getAdapter(), 'getUrl')){
			return Storage::drive($drive)->url($file);
		}
		else if(Storage::drive($drive)->getDriver()->getAdapter() instanceof League\Flysystem\Adapter\Local){
			$root = config("filesystems.disks.{$drive}")['url'];
			return url($root.'/'.$file);
		}
		return false;
	}

}