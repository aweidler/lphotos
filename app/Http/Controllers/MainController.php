<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Http\Requests;

class MainController extends Controller
{
	private $asides = 6;

	public function __construct(){

	}

	public function index(){
		return view('layouts.main', ['asides'=>$this->asides]);
	}

}
