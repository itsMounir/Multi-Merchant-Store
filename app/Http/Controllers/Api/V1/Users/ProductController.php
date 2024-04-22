<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Users\CategoryRequest;
use App\Http\Requests\Api\V1\Users\ProductRequest;
use App\Http\Requests\Api\V1\Users\ProductUpdaterequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * To get all products indexed by category
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        if ($category)
            $products = Product::with('category:id,name')->where('product_category_id', $category)->get();
        else {
            $products = Product::with('category:id,name')->all();
        }
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('category:id,name')->findOrFail($id);
        return response()->json($product);
    }
    /**
     * To create a new product
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());
        $id = $product->id;
        $product = Product::with('category:id,name')->findOrFail($id);
        return response()->json($product, 201);
    }
    /**
     * To update a product
     * @param ProductUpdaterequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(ProductUpdaterequest $request, $id)
    {
        $product = Product::with('category:id,name')->findOrFail($id);
        $product->update($request->all());
        return response()->json($product, 200);
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }

    /**
     * To restore deleted product
     * @param string $id
     * @return JsonResponse
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->with('category')->findOrFail($id);
        $product->restore();
        return response()->json(['message' => 'Product restored ', 'product' => $product], 200);
    }

    /**
     * To get category products
     * @return JsonResponse
     */
    public function getCategories()
    {
        $categories = ProductCategory::all();
        return response()->json($categories);
    }
    /**
     * To create a new category
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function createCategory(CategoryRequest $request)
    {
        $category = ProductCategory::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * To update a category
     * @param CategoryRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function updateCategory(CategoryRequest $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * To delete a category
     * @param string $id
     * @return JsonResponse
     */
    public function destroyCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
