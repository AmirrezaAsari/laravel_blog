<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessComments;
use App\Jobs\UpdateCommentsJob;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use App\Jobs\StoreCommentsJob;

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
            'parent_id' => 'nullable|exists:comments,id|integer',
        ]);

        $post = Post::find($post_id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $data = [
            'post_id' => $post_id,
            'comment' => $validated['comment'],
            'author_id' => Auth::id(),
            'parent_id' => $validated['parent_id'],
        ];
        $comment = StoreCommentsJob::dispatch($data);
        return response()->json(['message' => 'Comment created', 'data'=> $comment], 201);
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

        UpdateCommentsJob::dispatch($validated);
        return response()->json(['message' => 'Comment updated'], 200);
    }
}
