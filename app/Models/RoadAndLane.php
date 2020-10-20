<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadAndLane extends Model
{
    use HasFactory;

    protected $fillable = [
    	'zip_id', 'filename_id', 'name', 'abc'	
    ];

    public function area() {
    	return $this->belongsTo('App\Models\Area');
    }
}
