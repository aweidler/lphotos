<?php

namespace Photos;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
	protected $table = 'albums';
	
	public function allFiles(){
		return Fileentry::where('album', '=', $this->id)->get();
	}

	public function groupedFiles(){
		$files = $this->allFiles();

		
	}

}
