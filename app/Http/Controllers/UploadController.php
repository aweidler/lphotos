<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Album;
use Photos\Http\Requests;

class UploadController extends MainController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request = null)
	{
		$albums = Album::all();
		$aid = null;
		if($request){
			$aid = $request->id;
		}

		$albumkeys = array('' => 'Select Album&hellip;');
		foreach($albums as $album){
			$albumkeys[$album->id] = $album->name;
		}
		return view('components.upload', ['albums'=>$albums, 'aid'=>$aid, 'albumkeys'=>$albumkeys]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'newalbum' => 'unique:albums,name|max:100',
		]);

		if($request->newalbum){
			$album = new Album;
			$album->name = $request->newalbum;
			$album->save();
		}

		return $this->index();
	}

	public function save(Request $request){


		if(isset($request->save)){
			$this->validate($request, [
				'name' => 'unique:albums|max:100',
			]);

			$myalbum = Album::find($request->id);
			$myalbum->name = $request->name;
			$myalbum->save();
		}
		else if(isset($request->delete)){
			$myalbum = Album::find($request->id);
			$myalbum->delete();
		}

		return $this->index();
	}

}
