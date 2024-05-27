<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\CategoryRequest;
use App\Models\SupplierCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierCategoryController extends Controller
{
    /**
     * To get Markets categories
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', SupplierCategory::class);

        $categories = SupplierCategory::orderBy('position', 'asc')->get();
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
        $category->update(['type' => $request->name]);
        return response()->json($category, 200);
    }

    /**
     * reorder the catigries positions
     */
    public function reorder(Request $request)
    {
        $this->authorize('create', SupplierCategory::class);

        $category_IDs = $request->input('category_ids');

        foreach ($category_IDs as $position => $id) {
            SupplierCategory::where('id', $id)->update(['position' => $position]);
        }
        $categories = $this->index();
        return response()->json($categories, 200);
    }
    /**
     * update position to specific category
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePosition(string $id, Request $request)
    {
        $category = SupplierCategory::findOrFail($id);
        $this->authorize('update', $category);
        $category->update(['position' => $request->position]);
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
