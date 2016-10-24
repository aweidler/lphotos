<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Http\Requests;

class HomeController extends MainController
{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$view = view('components.home');
		return $view;
	}

}
