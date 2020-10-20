<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
    	'city_id', 'zip', 'filename', 'area'
    ];

    public function city() {
    	return $this->belongsTo('App\Models\City');
    }

    public function roads() {
    	return $this->hasMany('App\Models\RoadAndLane');
    }
}
