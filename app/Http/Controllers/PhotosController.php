<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Http\Requests;
use Photos\Album;
use Photos\Fileentry;
use Illuminate\Support\Facades\Storage;

class PhotosController extends MainController
{

	const TICKER_AMOUNT = 6;
	const COLS = 3;

	const BY_DATE = 0;
	const BY_RANDOM = 1;
	const BY_ALBUM = 2;

	public function __construct(){
		parent::__construct();
	}

	public function getPhotos($by = self::BY_DATE, $album = null, $query = null, $seed = ''){
		$files = Fileentry::with('album');

		if($album){
			$files = $files->where('album_id', '=', intval($album));
		}
		else if($query){
			$files = $files->where('tags', 'LIKE', '%'.$query.'%');
		}


		if($by == self::BY_RANDOM){
			$files = $files->inRandomOrder($seed ? $seed : '')->paginate(self::TICKER_AMOUNT);
		}
		else if($by == self::BY_ALBUM){
			$files = $files->orderBy('album_id')->paginate(self::TICKER_AMOUNT);
		}
		else{
			$files = $files->orderBy('created_at', 'DESC')->paginate(self::TICKER_AMOUNT);
		}
		return $files;
	}

	public function download(Request $request, $id){
		if(!$id){
			return $this->index($request);
		}

		ini_set('memory_limit','35MB');

		$entry = Fileentry::find($id);
		if(!$entry){
			$entry = Fileentry::where('hash', '=', $id)->first();
			if(!$entry){
				$entry = Fileentry::where('filename', '=', $id)->first();
			}
		}

		$storage = Storage::disk(UploadController::DRIVER_FL);

		if($entry && $storage->exists($entry->filename)){
			$path = $storage->getDriver()->getAdapter()->getPathPrefix().$entry->filename;
			$mime = mime_content_type($path);
			return response()->download($path, null, ['Content-Type' => $mime]);
		}

		abort(403, 'The file cannot be found or has been removed.');
	}

	public function index(Request $request = null){
		$seed = isset($request->seed) ? $request->seed : time();
		if($request->album){
			$album = Album::find(intval($request->album));
		}

		$files = $this->getPhotos($request->by, $request->album, $request->q, $seed);

		if($request->page){
			$views = [];
			foreach($files as $file){
				$view = view('components.photo', ['photo' => $file]);
				$views[] = $view->render();
			}

			return response()->json($views);
		}
		else{
			$sorts = [self::BY_DATE => "Date", self::BY_ALBUM => "Album", self::BY_RANDOM => "Random"];
			$listlabel = 'All Photos';
			if(isset($album) && $album){
				$listlabel = $album->name.' Photos';
			}
			else if($request->q){
				$listlabel = $request->q;
			}

			return view('components.photos', ['active' => 'photos', 
											  'photos'=>$files, 
											  'cols'=>self::COLS, 
											  'sorts' => $sorts,
											  'seed' => $seed,
											  'listLabel' => $listlabel,
											  'selectedSort' => isset($sorts[$request->by]) ? $request->by : self::BY_DATE ]);
		}
	}
}
