<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'comment', 'parent_id', 'likes', 'deleted'];
    protected $casts = ['likes' => 'array'];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
