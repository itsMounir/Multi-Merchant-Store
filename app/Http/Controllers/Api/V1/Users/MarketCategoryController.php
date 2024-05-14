<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\CategoryRequest;
use App\Models\MarketCategory;
use Illuminate\Http\JsonResponse;

class MarketCategoryController extends Controller
{
    /**
     * To get Markets categories
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', MarketCategory::class);

        $categories = MarketCategory::all();
        return response()->json($categories, 200);
    }
    /**
     * create new category
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', MarketCategory::class);
        $category = MarketCategory::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * Update category
     * @param CategoryRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = MarketCategory::findOrFail($id);
        $this->authorize('update', $category);

        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * delete category
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        $category = MarketCategory::findOrFail($id);
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json(null, 204);
    }
}
