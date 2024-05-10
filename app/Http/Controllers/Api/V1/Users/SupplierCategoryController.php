<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Users\CategoryRequest;
use App\Models\SupplierCategory;
use Illuminate\Http\JsonResponse;

class SupplierCategoryController extends Controller
{
    /**
     * To get Markets categories
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', SupplierCategory::class);
        $categories = SupplierCategory::all();
        return response()->json($categories, 200);
    }


    /**
     * To create new category
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', SupplierCategory::class);

        $category = SupplierCategory::create(['type' => $request->name]);
        return response()->json($category, 201);
    }



    /**
     * To Update category
     * @param CategoryRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = SupplierCategory::findOrFail($id);
        $this->authorize('update', $category);
        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * To delete category
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        $category = SupplierCategory::findOrFail($id);
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json(null, 204);
    }
}
