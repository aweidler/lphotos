<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Album;
use Photos\Fileentry;
use Photos\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
		$selected = null;
		if($request){
			$selected = Album::find($request->id);
		}

		$albumkeys = array('' => 'Select Album&hellip;');
		foreach($albums as $album){
			$albumkeys[$album->id] = $album->name;
		}
		return view('components.upload', ['albums'=>$albums, 'selected'=>$selected, 'albumkeys'=>$albumkeys]);
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
		$myalbum = Album::find($request->id);
		if(isset($request->save)){
			$this->validate($request, [
				'name' => 'unique:albums|max:100',
			]);

			$myalbum->name = $request->name;
			$myalbum->save();
		}
		else if(isset($request->delete)){
			$myalbum->delete();
		}
		else if(isset($request->photoup)){
			// Upload some images
			foreach($request->newfiles as $file){
				$extension = $file->getClientOriginalExtension();
				if(Storage::disk('public')->put($file->getFilename().'.'.$extension,  File::get($file))){
					$entry = new Fileentry();
					$entry->mime = $file->getClientMimeType();
					$entry->original_filename = $file->getClientOriginalName();
					$entry->filename = $file->getFilename().'.'.$extension;
					$entry->save();
				}
			}
			die();
		}

		return $this->index();
	}

}
