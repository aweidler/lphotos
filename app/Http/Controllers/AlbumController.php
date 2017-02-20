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

		$this->_albums = Album::all();
	}

	public function index(){
		$view = view('components.albums', ['active' => 'albums', 'albums'=>$this->_albums]);
		return $view;
	}

}
