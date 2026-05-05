<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessComments;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($post_id) {
        $post = Post::find($post_id);
        $comments = Comment::where('post_id', $post_id)->paginate(10);
        return response()->json(['data' => $comments], 200);
    }

    public function store(Request $request, $post_id)
    {
        $validated = $request->validate([
            'comment' => 'required',
        ]);

        $post = Post::find($post_id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comment = Comment::create([
            'post_id' => $post_id,
            'comment' => $validated['comment'],
            'author_id' => Auth::id(),
        ]);
        ProcessComments::dispatch($comment)
        return response()->json(['message' => 'Comment created'], 201);
    }


    public function destroy($comment_id) {
        return response()->json(['message' => 'Can not delete comments now!'], 400);
    }

    public function update(Request $request, $comment_id) {
        $validated = $request->validate([
            'comment' => 'required',
        ]);

        $comment = Comment::find($comment_id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->update($validated);
        ProcessComments::dispatch($comment);
        return response()->json(['message' => 'Comment updated'], 200);
    }
}
