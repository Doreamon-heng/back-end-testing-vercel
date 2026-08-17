<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostImageController extends Controller
{
    //get all image

    public function index()
    {
        try {
            $postImages = PostImage::paginate(12);

            if ($postImages->isEmpty()) {
                return response()->json([
                    "error" => true,
                    "message" => "No post images found!",
                ], 404);
            }

            return response()->json([
                "data" => $postImages,
                "message" => "Post images retrieved successfully!",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }

    //get details
    // get details
    public function details($id)
    {
        try {
            $postImage = PostImage::findOrFail($id);

            return response()->json([
                "data" => $postImage,
                "message" => "Post image retrieved successfully!",
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => "Post image not found!",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }

    //create new image
    public function store(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                "sub_title" => "required|string|max:255",
                "image" => "required|file|image|mimes:jpg,jpeg,png,webp|max:2048",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()->first(),
                    "errors" => $validator->errors(),
                ], 422);
            }

            $file = $r->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('post_images', $fileName, 'public');

            $postImage = PostImage::create([
                "sub_title" => $r->sub_title,
                "file_name" => $fileName,
                "image" => asset('storage/' . $path),
            ]);

            return response()->json([
                "data" => $postImage,
                "message" => "Post image created successfully!",
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing post image.
     */
    public function update(Request $r, $id)
    {
        try {
            $postImage = PostImage::findOrFail($id);

            $validator = Validator::make($r->all(), [
                "sub_title" => "sometimes|required|string|max:255",
                "image" => "sometimes|required|file|image|mimes:jpg,jpeg,png,webp|max:2048",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()->first(), 
                    "errors" => $validator->errors(),
                ], 422);
            }

            $data = [];

            if ($r->has('sub_title')) {
                $data['sub_title'] = $r->sub_title;
            }

            if ($r->hasFile('image')) {
                // delete old file from storage before saving the new one
                if ($postImage->file_name) {
                    Storage::disk('public')->delete('post_images/' . $postImage->file_name);
                }

                $file = $r->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('post_images', $fileName, 'public');

                $data['file_name'] = $fileName;
                $data['image'] = asset('storage/' . $path);
            }

            $postImage->update($data);

            return response()->json([
                "data" => $postImage,
                "message" => "Post image updated successfully!",
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => "Post image not found!",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
                "exception" => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $postImage = PostImage::findOrFail($id);

            if ($postImage->file_name) {
                \Storage::disk('public')->delete('post_images/' . $postImage->file_name);
            }

            $postImage->delete();

            return response()->json([
                "data" => null,
                "message" => "Post image deleted successfully!",
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => "Post image not found!",
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
