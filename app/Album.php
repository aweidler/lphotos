<?php

namespace Photos;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
	protected $table = 'albums';
	
	public function allFiles(){
		return Fileentry::where('album_id', '=', $this->id)->orderBy('sortindex')->get();
	}

	public function maxSortindex(){
		return intval(Fileentry::where('album_id', '=', $this->id)->max('sortindex'));
	}

	public function groupedFiles(){
		$files = $this->allFiles();
	}

    public function files(){
		return $this->hasMany('Photos\Fileentry', 'album_id');
    }

}
