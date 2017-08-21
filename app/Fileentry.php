<?php

namespace Photos;

use Illuminate\Database\Eloquent\Model;
use Photos\Http\Controllers\UploadController;


class Fileentry extends Model
{
	protected $fillable = ['hash'];
	protected $table = 'file_entries';

	public function getInfo(){
		$path = $this->getImageOnDisk(UploadController::DRIVER_FL);
		return exif_read_data($path, 0, true);
	}

	public function getImage($from = UploadController::DRIVER_MD){
		if(file_exists($this->getImageOnDisk($from))){
			return url('img/'.$from.'/'.$this->filename);
		}
		else if(file_exists($this->getImageOnDisk(UploadController::DRIVER_SM))){
			return url('img/'.UploadController::DRIVER_SM.'/'.$this->filename);
		}
		else{
			return url('img/'.UploadController::DRIVER_FL.'/'.$this->filename);
		}
	}

	public function album(){
		return $this->belongsTo('Photos\Album', 'album_id');
	}

	public function widthForHeight($height, $from = UploadController::DRIVER_MD){
		$file = $this->getImageOnDisk($from);
		if($file){
			list($w, $h) = getimagesize($file);
			$height = min($height, $h);
			$newwidth = $height * $w / $h;
			return $newwidth;
		}
		return 0;
	}

	public function heightForWidth($width){
		$file = $this->getImageOnDisk($from);
		if($file){
			list($w, $h) = getimagesize($file);
			$width = min($width, $w);
			return $width * $h / $w;
		}
		return 0;
	}

	private function getImageOnDisk($from = UploadController::DRIVER_MD){
		return storage_path('photos/'.$from.'/'.$this->filename);
	}

}
