<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class AdminPostController extends Controller
{
    public function index() {
        return Post::latest()->paginate(15);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts',
            'body' => 'required',
        ]);

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post created!');
    }


    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug,' . $id,
            'body' => 'required',
        ]);

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated!');
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted!');
    }
}
