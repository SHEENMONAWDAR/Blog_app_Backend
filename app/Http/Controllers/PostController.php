<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // 🔹 Get all posts
public function index()
{
    $userId = auth()->id();

    $posts = Post::with(['user:id,name,image'])
        ->withCount(['comments', 'likes'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($post) use ($userId) {
            // ✅ Add 'is_liked' property for current user
            $post->is_liked = $post->likes()->where('user_id', $userId)->exists();
            return $post;
        });

    return response([
        'posts' => $posts
    ], 200);
}


    // 🔹 Get single post
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)
                ->withCount('comments', 'likes')
                ->get()
        ], 200);
    }

    // 🔹 Create a post (form-data only)
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;

            // Store file inside storage/app/public/posts
            $path = $file->storeAs('posts', $filename, 'public');

            // ✅ Save only relative path
            $imagePath = 'storage/' . $path; // e.g. storage/posts/filename.jpg
        }

        $post = Post::create([
            'body' => $request->body,
            'user_id' => auth()->id(),
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post
        ], 200);
    }

    // 🔹 Update a post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response(['message' => 'Post not found.'], 404);
        }

        if ($post->user_id != auth()->id()) {
            return response(['message' => 'Permission denied.'], 403);
        }

        $request->validate([
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $post->image; // keep old image if not replaced

        // ✅ Handle new image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;

            $path = $file->storeAs('posts', $filename, 'public');
            $imagePath = 'storage/' . $path;
        }

        $post->update([
            'body' => $request->body,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Post updated successfully.',
            'post' => $post
        ], 200);
    }

    // 🔹 Delete a post
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response(['message' => 'Post not found.'], 404);
        }

        if ($post->user_id != auth()->id()) {
            return response(['message' => 'Permission denied.'], 403);
        }

        // delete comments & likes first
        $post->comments()->delete();
        $post->likes()->delete();

        // delete post itself
        $post->delete();

        return response(['message' => 'Post deleted.'], 200);
    }
}
