<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\SupplierProfileRequest;
use App\Models\Supplier;
use App\Traits\Images;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupplierUserController extends Controller
{
    use Images;

    /**
     * Display listing of suppliers (filtered on category and status) 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Supplier::class);

        $category = $request->query('category');
        $status = $request->query('status');

        $query = Supplier::query()->with('image', 'distributionLocations');
        if ($category) {
            $query->where('supplier_category_id', $category);
        }
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $suppliers = $query->orderBy('first_name', 'asc')->paginate(20, ['*'], 'p');

        foreach ($suppliers as $supplier) {
            $supplier->makeHidden('min_bill_price');
            $supplier->category_name = $supplier->category->type;
            $supplier->city_name = $supplier->city->name;
        }

        return response()->json(['supplier users' => $suppliers]);
    }

    /**
     * Display User Profile.
     * @param string $id
     * @return JsonResponse
     */
    public function show($id)
    {

        $supplier = Supplier::with('image', 'distributionLocations')->findOrFail($id);
        $this->authorize('view', $supplier);

        $supplier->makeHidden('min_bill_price');
        $supplier->category_name = $supplier->category->type;
        $supplier->city_name = $supplier->city->name;

        return response()->json(['user' => $supplier], 200);
    }

    /**
     * Display list of Supplier that match the inserted name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $this->authorize('viewAny', Supplier::class);
        try {
            $name = $request->query('name');
            $supplier = Supplier::where('store_name', 'like', '%' . $name . '%')->orderBy('first_name', 'asc')->paginate(20, ['*'], 'p');
            $supplier->makeHidden('min_bill_price');
            return response()->json($supplier, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * To change supplier profile
     * @param SupplierProfileRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(SupplierProfileRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->authorize('update', $supplier);

        $supplier->update($request->all());
        $supplier = Supplier::with('image', 'distributionLocations')->findOrFail($id);

        $supplier->makeHidden('min_bill_price');
        $supplier->category_name = $supplier->category->type;
        $supplier->city_name = $supplier->city->name;

        return response()->json(['message' => 'User has been updated successfully', 'user' => $supplier], 200);
    }

    /**
     * change the supplier profile Image
     * @param Request $request
     * @param string $id
     * @return JsonResponse 
     */
    public function changeImageProfile(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $request->validate(['image' => 'nullable|image']);
            $supplier = Supplier::findOrFail($id);
            $image = $supplier->image()->first();

            $request_image = $request->file('image');
            $image_name = $this->setImagesName([$request_image])[0];

            if ($image != null) {
                if (Storage::exists('public/Supplier/' . $image->url))
                    Storage::delete('public/Supplier/' . $image->url);
                $image->update(['url' => $image_name]);
            } else {
                $supplier->image()->Create(['url' => $image_name]);
            }
            $this->saveImages([$request_image], [$image_name], 'public/Supplier');
            DB::commit();
            return response()->json(['message' => 'تم تغيير الصورة بنجاح'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
    }


    /**
     * Delete the supplier profile Image
     * @param string $id
     * @return JsonResponse
     */
    public function deleteImageProfile(string $id)
    {
        DB::beginTransaction();
        try {
            $supplier = Supplier::with('image')->findOrFail($id);
            $image = $supplier->image()->first();
            if ($image == null) {
                return response()->json(['message' => 'لا يوجد صورة لهذا المورد'], 422);
            }
            if (Storage::exists('public/Supplier/' . $image->url))
                Storage::delete('public/Supplier/' . $image->url);
            $supplier->image()->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display the Supplier with his incoming Bills
     * @param string $id
     * @return JsonResponse
     */
    public function userWithBills($id)
    {
        $supplier = Supplier::with('image', 'bills.market')->findOrFail($id);
        $this->authorize('view', $supplier);

        $supplier->makeHidden('min_bill_price');
        $supplier->category_name = $supplier->category->type;
        $supplier->city_name = $supplier->city->name;
        return response()->json(['supplier' => $supplier]);
    }

    /**
     * Display the Supplier with his products
     * @param string $id
     * @return JsonResponse
     */
    public function userWithProducts($id)
    {
        $supplier = Supplier::with('image', 'products')->findOrFail($id);
        $this->authorize('view', $supplier);

        $supplier->makeHidden('min_bill_price');
        $supplier->catgory_name = $supplier->category->type;
        $supplier->city_name = $supplier->city->name;
        return response()->json(['supplier' => $supplier]);
    }

    /**
     * Display the Supplier with his distribution location
     * @param string $id
     * @return JsonResponse
     */
    public function userWithDistributionLocations(string $id)
    {
        $supplier = Supplier::with('image', 'distributionLocations')->findOrFail($id);
        $this->authorize('view', $supplier);

        $supplier->makeHidden('min_bill_price');
        $supplier->catgory_name = $supplier->category->type;
        $supplier->city_name = $supplier->city->name;
        return response()->json(['supplier' => $supplier]);
    }
    /**
     * Add or Update a distribution location to supplier
     * @param Request $requset
     * @param string $id 
     * @return JsonResponse
     */
    public function createOrUpdateDistributionLocation(Request $request, string $id)
    {
        $request->validate([
            'to_city_id' => ['required', 'exists:cities,id'],
            'min_bill_price' => ['required']
        ]);
        $supplier = Supplier::findOrFail($id);
        $this->authorize('update', $supplier);
        $supplier->distributionLocations()->updateOrCreate([
            'to_city_id' => $request->to_city_id
        ], [
            'min_bill_price' => $request->min_bill_price
        ]);

        $supplier->makeHidden('min_bill_price');
        $supplier->load('distributionLocations');
        return response()->json($supplier, 200);
    }

    public function deleteDistributionLocation(string $supplierId, string $locationId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $this->authorize('update', $supplier);
        $distributionLocation = $supplier->distributionLocations()->findOrFail($locationId);
        $distributionLocation->delete();

        $supplier->makeHidden('min_bill_price');
        $supplier->load('distributionLocations');
        return response()->json(null, 204);
    }

    /**
     * Activate Supplier user 
     * @param string $id
     * @return JsonResponse
     */
    public function activate($id)
    {
        $user = Supplier::with('image', 'distributionLocations')->findOrFail($id);
        $this->authorize('update', $user);
        try {
            if (!$user)
                return response()->json(['message' => 'User not found'], 404);
            if ($user->status == 'نشط')
                return response()->json(['message' => 'User is alredy Activated....'], 200);
            $user->status = 'نشط';
            $user->save();

            $user->makeHidden('min_bill_price');
            $user->category_name = $user->category->type;
            $user->city_name = $user->city->name;
            return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Ban Supplier user
     * @param string $id
     * @return JsonResponse
     */
    public function ban($id)
    {
        $user = Supplier::with('image', 'distributionLocations')->findOrFail($id);
        $this->authorize('update', $user);

        try {
            if ($user->status == 'محظور')
                return response()->json(['message' => 'User is alredy Banned...'], 200);
            $user->status = 'محظور';
            $user->tokens()->delete();
            $user->save();

            $user->makeHidden('min_bill_price');
            $user->category_name = $user->category->type;
            $user->city_name = $user->city->name;

            return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
