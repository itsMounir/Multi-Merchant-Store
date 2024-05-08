<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\ProductRequest;
use App\Http\Requests\Api\V1\Users\ProductUpdaterequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Traits\Images;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use Images;

    /**
     * To get all products indexed by category
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('test', Product::class);

        $category = $request->query('category');
        if ($category)
            $products = Product::with('category:id,name')->where('product_category_id', $category)->get();
        else {
            $products = Product::with('category:id,name')->get();
        }
        return response()->json($products, 200);
    }
    /**
     * To get trashed products
     * @return JsoneResponse
     */
    public function trash()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::with('category:id,name')->onlyTrashed()->get();
        return response()->json($products, 200);
    }
    /**
     * To get Product by ID
     * @param string $id
     * @return JsonResponse
     */
    public function show(String $id)
    {
        $product = Product::with('category:id,name')->findOrFail($id);

        $this->authorize('view', $product);
        return response()->json($product, 200);
    }
    /**
     * To create a new product
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $this->authorize('create', Product::class);

        DB::beginTransaction();
        try {
            $product = Product::create($request->all());
            if ($request->hasFile('image')) {
                $request_image = $request->file('image');
                $image_name = $this->setImagesName([$request_image])[0];
                $product->image()->create(['url' => $image_name]);
                $this->saveImages([$request_image], [$image_name], 'public/products');
            }
            $id = $product->id;
            $product = Product::with('category:id,name')->findOrFail($id);
            DB::commit();
            return response()->json($product, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'something gose wrong', 'error' => $th->getMessage()], $th->getCode() ?: 500);
        }
    }
    /**
     * To update a product
     * @param ProductUpdaterequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(ProductUpdaterequest $request, String $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product);

        DB::beginTransaction();
        try {
            $product->update($request->all());
            if ($request->hasFile('image')) {
                $request_image = $request->file('image');

                $old_image = $product->image()->first();
                if ($old_image && Storage::exists('products' . '/' . $old_image->url)) {
                    Storage::delete('products' . '/' . $old_image->url);
                }

                $image_name = $this->setImagesName([$request_image])[0];
                $product->image()->updateOrCreate(
                    ['imageable_id' => $product->id],
                    ['url' => $image_name]
                );
                $this->saveImages([$request_image], [$image_name], 'products');
            }
            DB::commit();
            return response()->json($product, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    /**
     * To delete product
     * @param string $id
     * @return null
     */
    public function destroy(String $id)
    {
        $product = Product::findOrfail($id);
        $this->authorize('delete', $product);

        $product->delete();
        return response()->json(null, 204);
    }

    /**
     * To restore deleted product
     * @param string $id
     * @return JsonResponse
     */
    public function restore(String $id)
    {
        $product = Product::onlyTrashed()->with('category')->findOrFail($id);
        $this->authorize('restore', $product);

        $product->restore();
        return response()->json(['message' => 'Product restored ', 'product' => $product], 200);
    }
    /**
     * To delete a category
     * @param string $id
     * @return JsonResponse
     */
    public function destroyCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->authorize('delete', $category);

        $category->delete();
        return response()->json(null, 204);
    }
}
