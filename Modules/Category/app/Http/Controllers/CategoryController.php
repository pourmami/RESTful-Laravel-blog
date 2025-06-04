<?php

namespace Modules\Category\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Category\app\Models\Category;
use Modules\Category\app\Http\Requests\StoreCategoryRequest;
use Modules\Category\app\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return response()->json($categories);
    }

    public function show($id): JsonResponse
    {
        $category = Category::with('children')->findOrFail($id);
        return response()->json($category);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return response()->json($category);
    }

    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
