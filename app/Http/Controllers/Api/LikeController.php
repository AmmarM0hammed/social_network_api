<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
class LikeController extends Controller
{
    public function likeDislike($id)
    {
        $post = Post::find($id);
        if(!$post)
        {
            $responce = [
                "success" => false,
                'posts' => "Post Not Found."
            ];
            return response()->json($responce, 400);
        }

        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        //like
        if(!$like)
        {
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]);

            $responce = [
                "success" => true,
                'posts' => "Liked"
            ];
            return response()->json($responce, 200);
        }
        //dislike
        $like->delete();

        $responce = [
            "success" => true,
            'posts' => "Dislike"
        ];
        return response()->json($responce, 200);
    }
}
