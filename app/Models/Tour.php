<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $primaryKey = 'tour_id';
    protected $table = 'tours';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'price',
        'capacity',
        'start_date',
        'end_date',
        'thumbnail',
        'description'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'user_tour', 'tour_id', 'user_id');
    }

    public function destinations(){
        return $this->belongsToMany(Destination::class, 'tour_destination', 'tour_id', 'destination_id');
    }

}