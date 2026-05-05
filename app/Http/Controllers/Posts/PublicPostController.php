<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Jobs\DownVotesJob;
use App\Jobs\UpVotesJob;
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

    public function upvote(Request $request, $post_id){
        UpVotesJob::dispatch($post_id);
    }

    public function downvote(Request $request, $post_id){
        DownVotesJob::dispatch($post_id);
    }
}
