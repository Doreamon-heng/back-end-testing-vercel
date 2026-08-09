<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Categories_image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Category_imageController extends Controller
{
    public function getAllCateImage()
    {
        try {
            $category_image = Categories_image::with('category')->get();

            if ($category_image->isEmpty()) {
                return response()->json([
                    'message' => 'No category images found'
                ], 404);
            }

            return response()->json([
                'data' => $category_image,
                'message' => 'Category images retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve category image');
        }
    }

    public function detailsCateImage($id)
    {
        try {
            $category_image = Categories_image::with('category')->find($id);

            if (! $category_image) {
                return response()->json([
                    'message' => 'Category image not found'
                ], 404);
            }

            return response()->json([
                'data' => $category_image,
                'message' => 'Category image retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve category image details');
        }
    }

    public function createCateImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|integer|exists:categories,id',
                'image_url' => 'nullable|url',
                'file_name' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $path = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('uploads', 'public');
                $data['image_url'] = $data['image_url'] ?? asset('storage/' . $path);
                $data['file_name'] = $data['file_name'] ?? $file->getClientOriginalName();
            }

            $cateImage = Categories_image::create($data);

            return response()->json([
                'data' => $cateImage,
                'message' => 'Category image created successfully',
                'url' => $path ? asset('storage/' . $path) : null,
                'path' => $path
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create category image');
        }
    }

    public function updateCateImage(Request $request, $id = null)
    {
        try {
            $id = $id ?? $request->id;

            if (! $id) {
                return response()->json([
                    'message' => 'Category image ID is required for update.'
                ], 422);
            }

            $cateImage = Categories_image::find($id);
            if (! $cateImage) {
                return response()->json([
                    'message' => 'Category image not found.'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'category_id' => 'sometimes|required|integer|exists:categories,id',
                'image_url' => 'nullable|url',
                'file_name' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $data = $validator->validated();

            if ($request->hasFile('image')) {
                if (! empty($cateImage->file_path) && Storage::disk('public')->exists($cateImage->file_path)) {
                    Storage::disk('public')->delete($cateImage->file_path);
                }

                $file = $request->file('image');
                $path = $file->store('uploads', 'public');
                $data['file_path'] = $path;
                $data['image_url'] = asset('storage/' . $path);
                $data['file_name'] = $data['file_name'] ?? $file->getClientOriginalName();
            }

            $cateImage->fill($data);
            $cateImage->save();

            return response()->json([
                'data' => $cateImage,
                'message' => 'Category image updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update category image');
        }
    }

    public function destroyCateImage($id)
    {
        try {
            $cateImage = Categories_image::find($id);

            if (! $cateImage) {
                return response()->json([
                    'message' => 'Category image not found.'
                ], 404);
            }

            $cateImage->delete();

            return response()->json([
                'message' => 'Category image deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete category image');
        }
    }
}
