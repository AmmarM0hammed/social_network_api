<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->with('user:id,name,photo')
            ->withCount('comments', 'likes')
            ->with('likes', function($like){
                return $like->where('user_id', auth()->user()->id)
                    ->select('id', 'user_id', 'post_id')->get();
            })
            ->get();

        $responce = [
            "success" => true,
            'posts' => $posts
        ];
        return response()->json($responce, 400);
    }

    public function show($id)
    {
        $post = Post::where('id', $id)->withCount('comments', 'likes')->with('user:id,name,photo')->get();
        $responce = [
            "success" => true,
            'posts' => $post
        ];
        return response()->json($responce, 400);
    }

    public function store(Request $request)
    {
        //validate fields
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            "images" => "required",
            "images.*" => "required|image|mimes:png,jpg,jpeg|max:500000",

        ]);

        if ($validated->fails()) {
            $responce = [
                "success" => false,
                'message' => $validated->errors()
            ];
            return response()->json($responce, 400);
        }

        $images = $request->file('images');
        $image_array = array();

        if ($request->hasFile('images')) {
            foreach ($images as $image) {
                $new_name = Str::ulid() . "." . $image->getClientOriginalExtension();
                $image->move(public_path('/images'), $new_name);
                $image_array[] = $new_name;
            }

            $post = Post::create([
                "user_id" => auth()->user()->id,
                "title" => $request->title,
                "images" => implode(',', $image_array),

            ]);

            $responce = [
                "success" => true,
                'post' => $post
            ];
            return response()->json($responce, 200);

        } else {
            return response()->json("null");
        }

    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        // to check post if existing
        if (!$post) {
            $responce = [
                'success' => false,
                'message' => "Post not found"
            ];
            return response()->json($responce, 400);
        }

        // to check if post for this user
        if ($post->user_id != auth()->user()->id) {
            $responce = [
                'success' => false,
                'message' => "Permission denied."
            ];
            return response()->json($responce, 400);
        }

        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);

        if ($validated->fails()) {
            $responce = [
                "success" => false,
                'message' => $validated->errors()
            ];
            return response()->json($responce, 400);
        }

        $post->update([
            'title' => $request->title
        ]);

        $responce = [
            'success' => true,
            'message' => "Post updated."
        ];
        return response()->json($responce, 200);

    }

    public function delete($id)
    {
        $post = Post::find($id);
        if (!$post) {
            $responce = [
                'success' => false,
                'message' => "Post not found"
            ];
            return response()->json($responce, 400);
        }

        // to check if post for this user
        if ($post->user_id != auth()->user()->id) {
            $responce = [
                'success' => false,
                'message' => "Permission denied."
            ];
            return response()->json($responce, 400);
        }


        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        $responce = [
            'success' => true,
            'message' => "Post deleted"
        ];
        return response()->json($responce, 400);

    }


    public function user_posts(Request $request , $id){

        $user = User::find($id);

        if (!$user) {
            $responce = [
                'success' => false,
                'message' => "User not found"
            ];
            return response()->json($responce, 400);
        }

        $responce = [
            'success' => true,
            'posts' => $user->posts
        ];
        return response()->json($responce, 400);
    }
}