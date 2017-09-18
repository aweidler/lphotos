<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;

use Photos\Http\Requests;

class ContactController extends MainController
{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$view = view('components.contact', ['active' => 'contact']);
		return $view;
	}
}
