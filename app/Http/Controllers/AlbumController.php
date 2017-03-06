<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Http\Requests;
use Photos\Album;

class AlbumController extends MainController
{
	private $_albums;

	public function __construct(){
		parent::__construct();

		$this->_albums = Album::where('foralbumticker', '=', 1)->paginate(1);
	}

	public function showAlbum(Request $request){
		$album = Album::find($request->id);
		if(!$album){
			return $this->index($request);
		}

		// retun view('')

	}

	public function index(Request $request = null){
		if($request && $request->page){
			$view = view('components.albumrow', ['albums'=>$this->_albums]);
		}
		else{
			$view = view('components.albums', ['active' => 'albums', 'albums'=>$this->_albums]);
		}
		return $view;
	}

}
