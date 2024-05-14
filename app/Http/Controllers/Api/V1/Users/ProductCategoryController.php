<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\CategoryRequest;
use App\Models\ProductCategory;


class ProductCategoryController extends Controller
{
    /**
     * Display a listing of Product Categories.
     * @return JsonResponse
     */

    public function index()
    {
        $this->authorize('viewAny', ProductCategory::class);
        $categories = ProductCategory::all();
        return response()->json($categories, 200);
    }


    /**
     * To create and store a new category
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', ProductCategory::class);

        $category = ProductCategory::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->authorize('view', $category);
        return response()->json($category, 200);
    }


    /**
     * To update a category
     * @param CategoryRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->authorize('update', $category);

        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * To delete a category
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(String $id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->authorize('delete', $category);

        $category->delete();
        return response()->json(null, 204);
    }
}
