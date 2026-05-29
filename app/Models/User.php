<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'name', 'surname', 'username', 'password_hash', 'dob', 'email', 'phone_number'
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function tours() {
        return $this->belongsToMany(Tour::class, 'user_tour', 'user_id', 'tour_id');
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    public function isManager(){
        return $this->roles()->where('name', 'manager')->exists();
    }

    public function isAdmin(){
        return $this->roles()->where('name', 'admin')->exists();
    }


}

