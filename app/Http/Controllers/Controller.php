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

    // If image is a file object, store it in public/posts
    $storedPath = $image->store($path, 'public');

    // Return the public URL
    return url('storage/' . $storedPath);
}





}