<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

public function saveImage($image, $path = 'posts')
{
    if (!$image) {
        return null;
    }

    // Create your own filename
    $filename = uniqid() . '.' . $image->getClientOriginalExtension();

    // Save inside storage/app/public/posts
    $storedPath = $image->storeAs($path, $filename, 'public');

    // Return only relative path (posts/filename.jpg)
    return $storedPath;
}







}