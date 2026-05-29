<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destinations';
    public $timestamps = false;
    protected $primaryKey = 'destination_id';

    protected $fillable = [
        'name',
        'description',
    ];

    public function tours(){
        return $this->belongsToMany(Tour::class, 'tour_destination', 'destination_id', 'tour_id');
    }
}
