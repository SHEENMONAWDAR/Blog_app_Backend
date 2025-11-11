<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    //
    protected $fillable = [
        'comment',
        'user_id',
        'post_id'
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
}
