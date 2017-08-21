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
			$files = Fileentry::orderBy('album_id')->get();
		}
		else{
			$files = Fileentry::with('album')->orderBy('created_at', 'DESC')->get();
		}
		return $files;
	}

	public function index(Request $request = null){
		$files = $this->getPhotos($request->by);
		$sorts = [self::BY_DATE => "Date", self::BY_ALBUM => "Album", self::BY_RANDOM => "Random"];
		return view('components.photos', ['active' => 'photos', 
										  'photos'=>$files, 
										  'cols'=>self::COLS, 
										  'sorts' => $sorts,
										  'selectedSort' => isset($sorts[$request->by]) ? $request->by : self::BY_DATE ]);
	}
}