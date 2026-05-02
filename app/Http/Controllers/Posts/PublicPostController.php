<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    public function show($slug){
        return Post::where('slug', $slug)->firstOrFail();
    }

    public function index()
    {
        return Post::latest()->paginate(15);
    }
}
