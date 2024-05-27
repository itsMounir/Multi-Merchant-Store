<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\CategoryRequest;
use App\Models\MarketCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketCategoryController extends Controller
{
    /**
     * Display List markets categories
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', MarketCategory::class);

        $categories = MarketCategory::orderBy('position', 'asc')->get();
        return response()->json($categories, 200);
    }
    /**
     * Store newly created category
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
     * Update specific category
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
     * reorder the catigries positions
     */
    public function reorder(Request $request)
    {
        $this->authorize('create', MarketCategory::class);

        $category_IDs = $request->input('category_ids');

        foreach ($category_IDs as $position => $id) {
            MarketCategory::where('id', $id)->update(['position' => $position]);
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
    public function updatePosition(String $id, Request $request)
    {
        $category = MarketCategory::findOrFail($id);
        $this->authorize('update', $category);
        $category->update(['position' => $request->position]);
        return response()->json($category, 200);
    }

    /**
     * Delete specific category
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
