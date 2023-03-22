<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;




// Public Route
Route::controller(AuthController::class)->group(function (){
    Route::post('/login' , "login");
    Route::post('/register' , "register");
});

// Protected Route
Route::middleware(['auth:sanctum'])->group(function () {

    // ================ Post ================
    Route::get('/posts', [PostController::class, 'index']); // get all posts
    Route::post('/posts', [PostController::class, 'store']); // store post
    Route::get('/posts/{id}', [PostController::class, 'show']); // show singl post
    Route::put('/posts/{id}', [PostController::class, 'update']);// update post
    Route::delete('/posts/{id}', [PostController::class, 'delete']); // delete post

    // ================ Comments ================
    Route::get('/posts/{id}/comment', [CommentController::class, 'index']); // show comment for post
    Route::post('/posts/{id}/comment', [CommentController::class, 'create']); // create comment for post   
    Route::put('/comments/{id}', [CommentController::class, 'update']); // update a comment
    Route::delete('/comments/{id}', [CommentController::class, 'delete']); // delete a comment

    // ================ Like and Dislike ================
    Route::get('/posts/{id}/likes', [LikeController::class, 'likeDislike']);// like or dislike

    // ================ User ================
    Route::get('/user' , [AuthController::class,"user"]);

    Route::get('/user/{id}/posts' , [PostController::class,"user_posts"]);

    Route::post('/user' , [AuthController::class,"update"]);
    Route::get('/logout' , [AuthController::class,"logout"]);

});