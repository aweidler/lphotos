<?php

namespace Photos;

use Illuminate\Database\Eloquent\Model;
use Photos\Http\Controllers\UploadController;

class Fileentry extends Model
{
	protected $fillable = ['hash'];
    protected $table = 'file_entries';

    public function getImage($from = UploadController::DRIVER_MD){
    	if(file_exists(storage_path('photos/'.$from.'/'.$this->filename))){
    		return url('img/'.$from.'/'.$this->filename);
    	}
    	else if(file_exists(storage_path('photos/small/'.$this->filename))){
			return url('img/small/'.$this->filename);
    	}
    	else{
    		return url('img/full/'.$this->filename);
    	}
    }

}
