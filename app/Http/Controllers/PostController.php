<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // 🔹 Get posts created by a specific user
    public function userPosts($userId)
    {
        $authUser = auth()->id();

        $posts = Post::where('user_id', $userId)
            ->with(['user:id,name,image'])
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($post) use ($authUser) {
                $post->is_liked = $post->likes()->where('user_id', $authUser)->exists();
                return $post;
            });

        return response()->json(['posts' => $posts], 200);
    }

    // 🔹 Get all posts
    public function index()
    {
        $userId = auth()->id();

        $posts = Post::with(['user:id,name,image'])
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($post) use ($userId) {
                $post->is_liked = $post->likes()->where('user_id', $userId)->exists();
                return $post;
            });

        return response()->json(['posts' => $posts], 200);
    }

    // 🔹 Create a post
public function store(Request $request)
{
    $request->validate([
        'body' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $this->saveImage($request->file('image'), 'posts');
    }

    $post = Post::create([
        'body' => $request->body,
        'user_id' => auth()->id(),
        'image' => $imagePath,
    ]);

    return response()->json(['post' => $post], 200);
}

public function update(Request $request, $id)
{
    $post = Post::find($id);

    if (!$post) return response(['message' => 'Post not found'], 404);
    if ($post->user_id != auth()->id()) return response(['message' => 'Permission denied'], 403);

    $request->validate([
        'body' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imagePath = $post->image;

    if ($request->hasFile('image')) {
        $imagePath = $this->saveImage($request->file('image'), 'posts');
    }

    $post->update([
        'body' => $request->body,
        'image' => $imagePath,
    ]);

    return response()->json(['post' => $post], 200);
}


    // 🔹 Delete post
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) return response(['message' => 'Post not found'], 404);
        if ($post->user_id != auth()->id()) return response(['message' => 'Permission denied'], 403);

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response(['message' => 'Post deleted'], 200);
    }
}
