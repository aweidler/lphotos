<?php

namespace Photos\Http\Controllers;

use Illuminate\Http\Request;
use Photos\Album;
use Photos\Fileentry;
use Photos\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Exception\NotFoundException;
use Illuminate\Http\UploadedFile;

class UploadController extends MainController
{
	const DRIVER_QUALITY = 75;

	const DRIVER_RAW = 'raw';
	const DRIVER_SM = 'small';
	const DRIVER_MD = 'medium';
	const DRIVER_LG = 'large';
	const DRIVER_FL = 'full';
	const DRIVER_XMP = 'rawedits';

	// Do not change these without resizing all previous images
	// They are used for calculations
	// This is so we don't have to frequently hit Azure's endpoint
	public static $IMAGE_SIZES = [
		self::DRIVER_SM => 480,
		self::DRIVER_MD => 740,
		self::DRIVER_LG => 1920
	];

	public static $DRIVER_MAP = [ // first entry is the "database driver"
		'image/jpeg' => self::DRIVER_FL,
		'image/x-dcraw' => self::DRIVER_RAW,
		'application/octet-stream' => self::DRIVER_XMP
	];

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request = null)
	{
		ini_set('memory_limit','500M');
		$albums = Album::all();
		$selected = null;
		if($request){
			$selected = Album::find($request->id);
		}

		$allFiles = [];
		if($selected){
			$allFiles = $selected->allFiles();
		}

		$albumkeys = array('' => 'Select Album&hellip;');
		foreach($albums as $album){
			$albumkeys[$album->id] = $album->name;
		}
		return view('components.upload', ['albums'=>$albums, 'files'=>$allFiles, 'selected'=>$selected, 'albumkeys'=>$albumkeys]);
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
			$album->location = $request->newlocation;
			$album->parent = $request->newparent;
			$album->save();
		}

		return $this->index();
	}

	public function saveFile(Request $request){
		$myFile = Fileentry::find($request->file);
		if($myFile){
			$myFile->tags = $request->tags;
			$myFile->save();
			return 'File Saved';
		}
		return 'Could Not Find File';
	}

	public function deleteFile(Request $request){
		$myFile = Fileentry::find($request->id);
		if($myFile){
			$album = $myFile->album_id;
			$this->unlinkFile($myFile);

			// shift the order down
			$files = Fileentry::where('album_id', '=', intval($album))->where('sortindex', '>', intval($myFile->sortindex))->get();
			foreach($files as $file){
				if($file->sortindex > 1){
					$file->sortindex = $file->sortindex - 1;
					$file->save();
				}
			}

			$myFile->delete();
			$request->id = $album;
		}
		else{
			$request->id = null;
		}
		return $this->index($request);
	}

	private function unlinkFile(Fileentry $myFile){
		// remove all image refs
		$keys = array_keys(self::$IMAGE_SIZES);
		$keys[] = self::DRIVER_FL;
		foreach($keys as $storage){
			if (Storage::disk($storage)->exists($myFile->filename)){
				Storage::disk($storage)->delete($myFile->filename);
			}
		}

		// remove CR2 and xmp refs
		if (Storage::disk(self::DRIVER_XMP)->exists($myFile->hash.'.CR2')){
			Storage::disk(self::DRIVER_XMP)->delete($myFile->hash.'.CR2');
		}

		if (Storage::disk(self::DRIVER_XMP)->exists($myFile->hash.'.xmp')){
			Storage::disk(self::DRIVER_XMP)->delete($myFile->hash.'.xmp');
		}
	}

	public function saveOrder(Request $request){
		if($request->order){
			foreach($request->order as $id=>$index){
				$file = Fileentry::find($id);
				if($file){
					$file->sortindex = intval($index);
					$file->save();
				}
			}
			return (string)true;
		}
		return (string)false;
	}

	public function save(Request $request){
		$myalbum = Album::find($request->id);
		if(isset($request->save)){
			$myalbum->location = $request->location;
			$myalbum->parent = $request->parent;
			$myalbum->save();

			$this->validate($request, [
				'name' => 'unique:albums|max:100',
			]);

			$myalbum->name = $request->name;
			$myalbum->save();
		}
		else if(isset($request->delete)){
			$photoController = new PhotosController();
			$fileEntries = $photoController->getPhotos(PhotosController::BY_ALBUM, $myalbum->id);
			foreach($fileEntries as $fileEntry){
				$this->unlinkFile($fileEntry);
			}
			$myalbum->delete();
		}
		else if(isset($request->photoup)){
			// Upload some images
			set_time_limit(4000);
			ini_set('memory_limit','2G');
			foreach($request->newfiles as $file){
				$this->addFile($file, $myalbum);
			}
			return $this->index($request);
		}
		return $this->index();
	}

	private function addFile(UploadedFile $file, Album $inalbum){
		$mimetype = $file->getClientMimeType();

		if(self::$DRIVER_MAP[$mimetype] && $inalbum){
			$hashname = $newname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			//$newname = substr($hashname, 0, 32); // I want to replace the old files given the file name
			$extension = $file->getClientOriginalExtension();
			$newnameExtended = $newname.'.'.$extension;

			if(Storage::disk(self::$DRIVER_MAP[$mimetype])->put($newnameExtended, File::get($file))){
				if($mimetype == key(self::$DRIVER_MAP)){
					$info = exif_read_data($file->getPathName(), 0, true);

					// Create a new File that we have uploaded
					$entry = Fileentry::firstOrNew(array('hash'=>$newname));
					$entry->hash = $newname;
					$entry->filename = $newnameExtended;
					$entry->mime = $mimetype;
					$entry->original_filename = $file->getClientOriginalName();
					$entry->album_id = $inalbum->id;
					$entry->size = File::size($file);
					$entry->shot_at = isset($info['EXIF']['DateTimeOriginal']) ? (string)$info['EXIF']['DateTimeOriginal'] : null;
					$entry->sortindex = (isset($entry->sortindex) ? intval($entry->sortindex) : $inalbum->maxSortindex() + 1);
					$entry->exif = json_encode($info);
					
					$img = \Image::make($file);
					if($img->height() && $img->width()){
						$entry->height = intval($img->height());
						$entry->width = intval($img->width());
					}

					$entry->save();

					// Update our album time, we added photos to this album
					$inalbum->updated_at = date('Y-m-d H:i:s');
					$inalbum->save();

					// Make our different sizes
					$this->saveThumbs($file, $newnameExtended);
				}
			}
		}
	}

	private function saveThumbs(UploadedFile $file, string $savename){
		if($file->getClientMimeType() != key(self::$DRIVER_MAP)){
			return false;
		}

		foreach(self::$IMAGE_SIZES as $driver=>$size){
			$img = \Image::make($file);

			$h = $w = $size;
			if($img->height() > $img->width()){
				$w = null;
			}
			else{
				$h = null;
			}

			$img->resize($w, $h, function($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});

			$data = $img->encode(pathinfo($savename, PATHINFO_EXTENSION), self::DRIVER_QUALITY);
			if(!Storage::disk($driver)->put($savename, (string)$data)){
				throw new NotWritableException(
					"Can't write image data to path ({$driver}/{$path})"
				);
				return false;
			}

			//$path = Storage::disk($driver)->getDriver()->getAdapter()->getPathPrefix();
			//$img->save($path.$savename, self::DRIVER_QUALITY);
		}
		return true;
	}

}
