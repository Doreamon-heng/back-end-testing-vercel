<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Categories::with('categories_image')->get();

            return response()->json([
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve categories');
        }
    }

    public function show($id)
    {
        try {
            $category = Categories::with('categories_image')->find($id);

            if (! $category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            return response()->json([
                'data' => $category,
                'message' => 'Category retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve category details');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', Rule::unique('categories')],
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $category = Categories::create($validator->validated());

            return response()->json([
                'data' => $category,
                'message' => 'Category created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create category');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Categories::find($id);

            if (! $category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $category->fill($validator->validated());
            $category->save();

            return response()->json([
                'data' => $category,
                'message' => 'Category updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update category');
        }
    }

    public function destroy($id)
    {
        try {
            $category = Categories::find($id);

            if (! $category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete category');
        }
    }
}
