<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // get all posts
    public function index()
    {
        try {
            $post = Post::with('postImage')->paginate(12);

            if ($post->isEmpty()) {
                return response()->json([
                    "error" => true,
                    "message" => "No posts found!",
                ], 404);
            }

            return response()->json([
                "data" => $post,
                "message" => "Post retrieved successfully!",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }
    //get post details
    public function details($id)
    {
        try {
            $post = Post::with('postImage')->findOrFail($id);

            return response()->json([
                "data" => $post,
                "message" => "Post retrieved successfully!",
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => "Post not found!",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }

    //create new post
    public function store(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                "title" => "required|string|max:255",
                "credit_by" => "required|string|max:255",
                "type_of_content" => "required|string|max:255",
                "articles" => "required|string",
                "post_images_id" => "required|exists:post_images,id",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()->first(),
                    "errors" => $validator->errors(),
                ], 422);
            }

            $post = Post::create($validator->validated());

            return response()->json([
                "data" => $post,
                "message" => "Post created successfully!",
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }

    //update post
    public function update(Request $r, $id)
    {
        try {
            $post = Post::findOrFail($id);

            $validator = Validator::make($r->all(), [
                "title" => "sometimes|required|string|max:255",
                "credit_by" => "sometimes|required|string|max:255",
                "type_of_content" => "sometimes|required|string|max:255",
                "articles" => "sometimes|required|string",
                "post_images_id" => "sometimes|required|exists:post_images,id",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()->first(),
                    "errors" => $validator->errors(),
                ], 422);
            }

            $post->update($validator->validated());

            return response()->json([
                "data" => $post,
                "message" => "Post updated successfully!",
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => "Post not found!",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }


    //delete post
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return response()->json([
                "data" => null,
                "message" => "Post deleted successfully!",
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => "Post not found!",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }
}

