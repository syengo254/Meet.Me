<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = ['hometown', 'city', 'phone', 'dob', 'about', 'avatar', 'friends', 'friend_requests', 'blocked_users', 'user_id', 'gender'];
    protected $casts = ["friends" => "array", "friend_requests" => "array"];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
