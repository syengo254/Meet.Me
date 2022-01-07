<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post', 'has_image', 'hidden', 'deleted', 'likes'];

    protected $casts = [
        "likes" => "array"
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-M-Y'). " at ". $date->format('h:i A');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
