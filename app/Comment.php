<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content'];


    public function post(){
        return $this->belongsTo(Post::class);
    }
}
