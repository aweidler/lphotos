<?php

namespace Photos;

use Illuminate\Database\Eloquent\Model;

class Fileentry extends Model
{
	protected $fillable = ['hash'];
    protected $table = 'file_entries';
}
