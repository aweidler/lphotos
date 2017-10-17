<?php

namespace Photos;

use Illuminate\Database\Eloquent\Model;
use Photos\Http\Controllers\UploadController;


class Fileentry extends Model
{
	protected $fillable = ['hash'];
	protected $table = 'file_entries';

	public function getInfo(){
		$info = $this->exif;
		if($info){
			return json_decode($info, true);
		}
		return null;
	}

	public function getImage($from = UploadController::DRIVER_MD){
		return photoUrl($this->filename, $from);
	}

	public function imageSize($from = UploadController::DRIVER_MD){
		$width = 0;
		$height = 0;

		if($this->width && $this->height){
			if($from == UploadController::DRIVER_FL){
				$width = $this->width;
				$height = $this->height;
			}
			else if(UploadController::$IMAGE_SIZES[$from]){
				$thresh = intval(UploadController::$IMAGE_SIZES[$from]);

				if($this->width == $this->height){
					$width = $height = $thresh;
				}
				else if($this->height > $this->width){
					$height = $thresh;
					$width = intval(ceil($this->width * ($thresh / $this->height)));
				}
				else if($this->height < $this->width){
					$width = $thresh;
					$height = intval(ceil($this->height * ($thresh / $this->width)));
				}
			}
		}

		return [$width, $height, $this->mime];
	}

	public function album(){
		return $this->belongsTo('Photos\Album', 'album_id');
	}

	public function widthForHeight($height, $from = UploadController::DRIVER_MD){
		list($w, $h) = $this->imageSize($from);
		if($w && $h){
			$height = min($height, $h);
			$newwidth = $height * $w / $h;
			return $newwidth;
		}
		return 0;
	}

	public function heightForWidth($width, $from = UploadController::DRIVER_MD){
		list($w, $h) = $this->imageSize($from);
		if($w && $h){
			$width = min($width, $w);
			$newheight = $width * $h / $w;
			return $newheight;
		}
		return 0;
	}
}
