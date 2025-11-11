<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;


class Like extends Model
{
    //
        protected $fillable = [
        'user_id',
        'post_id'
    ];
}
