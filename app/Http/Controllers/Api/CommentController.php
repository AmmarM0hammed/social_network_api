<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Validator;

class CommentController extends Controller
{
    public function index($id)
    {
        $post = Post::find($id);
        if (!$post) {
            $responce = [
                "success" => false,
                'posts' => "Post Not Found."
            ];
            return response()->json($responce, 400);
        }

        $comments = $post->comments()->with('user:id,name,photo')->get();
        $responce = [
            "success" => true,
            'comments' => $comments
        ];
        return response()->json($responce, 400);
    }
    public function create(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            $responce = [
                "success" => false,
                'posts' => "Post Not Found."
            ];
            return response()->json($responce, 400);
        }
        $validated = Validator::make($request->all(), [
            'comment' => 'required|max:255',
        ]);

        if ($validated->fails()) {
            $responce = [
                "success" => false,
                'message' => $validated->errors()
            ];
            return response()->json($responce, 400);
        }

        $comment = Comment::create([
            "comment" => $request->comment,
            "user_id" => auth()->user()->id,
            "post_id" => intval($id)
        ]);

        $responce = [
            "success" => true,
            'comments' => $comment
        ];
        return response()->json($responce, 400);
    }
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            $responce = [
                "success" => false,
                'message' => "Comment not found"
            ];
            return response()->json($responce, 400);
        }

        if ($comment->user_id != auth()->user()->id) {
            $responce = [
                "success" => false,
                'message' => "Permission denied"
            ];
            return response()->json($responce, 400);
        }

        $validated = Validator::make($request->all(), [
            'comment' => 'required|max:255',
        ]);

        if ($validated->fails()) {
            $responce = [
                "success" => false,
                'message' => $validated->errors()
            ];
            return response()->json($responce, 400);
        }

        $comment->update([
            'comment' => $request->comment
        ]);

        $responce = [
            "success" => true,
            'message' => "Comment Updated"
        ];
        return response()->json($responce, 200);
    }
    public function delete($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            $responce = [
                "success" => false,
                'message' => "Comment not found"
            ];
            return response()->json($responce, 400);
        }

        if($comment->user_id != auth()->user()->id)
        {
            $responce = [
                "success" => false,
                'message' => "Permission denied"
            ];
            return response()->json($responce, 400);
            
        }

        $comment->delete();

        $responce = [
            "success" => true,
            'message' => "Deleted"
        ];
        return response()->json($responce, 200);
    }
}