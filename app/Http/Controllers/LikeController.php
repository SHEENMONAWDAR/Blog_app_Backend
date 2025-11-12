<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    // like or unlike
    public function likeOrUnlike($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post not found.'
            ], 404);
        }

        $userId = auth()->user()->id;
        $like = $post->likes()->where('user_id', $userId)->first();

        if (!$like) {
            // ✅ Like the post
            Like::create([
                'post_id' => $id,
                'user_id' => $userId
            ]);

            $status = 'liked';
        } else {
            // ✅ Unlike the post
            $like->delete();
            $status = 'unliked';
        }

        // ✅ Recalculate total likes
        $likeCount = $post->likes()->count();

        return response([
            'message' => ucfirst($status),
            'is_liked' => $status === 'liked',
            'likes_count' => $likeCount,
        ], 200);
    }
}
