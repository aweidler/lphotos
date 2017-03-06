<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Http\Requests;
use Photos\Album;
use Photos\Fileentry;

class PhotosController extends MainController
{

	const COLS = 3;

	const BY_DATE = 0;
	const BY_RANDOM = 1;
	const BY_ALBUM = 2;

	public function __construct(){
		parent::__construct();
	}

	public function getPhotos($by = self::BY_DATE){
		$files = null;
		if($by == self::BY_RANDOM){
			$files = Fileentry::inRandomOrder()->get();
		}
		else if($by == self::BY_ALBUM){
			$files = Fileentry::orderBy('album')->get();
		}
		else{
			$files = Fileentry::orderBy('created_at', 'DESC')->get();
		}
		return $files;
	}

	public function index(Request $request = null){
		$files = $this->getPhotos($request->by);
		return view('components.photos', ['active' => 'photos', 'photos'=>$files, 'cols'=>self::COLS]);
	}
}
