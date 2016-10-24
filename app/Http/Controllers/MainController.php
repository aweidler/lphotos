<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Http\Requests;
use View;

class MainController extends Controller
{
	private $asides = 8;

	public function __construct(){
		View::share('aperture_sides', $this->asides);
	}

	public function index(){
		// This is the default view, it has nothing... like no content
		return view('layouts.main');
	}

}
