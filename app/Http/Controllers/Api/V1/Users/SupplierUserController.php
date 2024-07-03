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
        $user = Supplier::findOrFail($id);
        $this->authorize('update', $user);

        $user->update($request->all());
        $user = Supplier::with('image', 'distributionLocations')->findOrFail($id);

        $user->category_name = $user->category->type;
        $user->city_name = $user->city->name;

        return response()->json(['message' => 'User has been updated successfully', 'user' => $user], 200);
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

            $request_image = $request->file('image');
            $image_name = $this->setImagesName([$request_image])[0];

            if ($supplier->image != null) {
                if (Storage::exists('public/Supplier/' . $supplier->image->url))
                    Storage::delete('public/Supplier/' . $supplier->image->url);
                $supplier->image()->update(['url' => $image_name]);
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
            if ($supplier->image == null) {
                return response()->json(['message' => 'لا يوجد صورة لهذا المورد'], 422);
            }
            if (Storage::exists('public/Supplier/' . $supplier->image->url))
                Storage::delete('public/Supplier/' . $supplier->image->url);
            $supplier->image->delete();
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
        $user = Supplier::with('image', 'bills.market')->findOrFail($id);
        $this->authorize('view', $user);

        $user->category_name = $user->category->type;
        $user->city_name = $user->city->name;
        return response()->json(['user' => $user]);
    }

    /**
     * Display the Supplier with his products
     * @param string $id
     * @return JsonResponse
     */
    public function userWithProducts($id)
    {
        $user = Supplier::with('image', 'products')->findOrFail($id);
        $this->authorize('view', $user);

        $user->catgory_name = $user->category->type;
        $user->city_name = $user->city->name;
        return response()->json(['user' => $user]);
    }

    /**
     * Display the Supplier with his distribution location
     * @param string $id
     * @return JsonResponse
     */
    public function userWithDistributionLocations(string $id)
    {
        $user = Supplier::with('image', 'distributionLocations')->findOrFail($id);
        $this->authorize('view', $user);

        $user->catgory_name = $user->category->type;
        $user->city_name = $user->city->name;
        return response()->json(['user' => $user]);
    }

    public function addDistributionLocation(Request $request, string $id)
    {
        $this->authorize('update', Supplier::class);
        $request->validate([
            'to_city_id' => ['required', 'exists:cities,id'],
            'min_bill_price' => ['required']
        ]);
        $supplier = Supplier::findOrFail($id);
        $existingLocation = $supplier->distributionLocations()->where('to_city_id', $request->to_city_id)->first();
        if ($existingLocation) {
            return response()->json(['message' => 'تمت إضافة هذه المدينة سابقاً'], 400);
        }
        $supplier->distributionLocations()->create([
            'to_city_id' => $request->to_city_id,
            'min_bill_price' => $request->min_bill_price
        ]);
        $supplier->load('distributionLocations');
        return response()->json($supplier, 200);
    }

    public function deleteDistributionLocation(string $supplierId, string $locationId)
    {
        $this->authorize('update', Supplier::class);
        $supplier = Supplier::findOrFail($supplierId);
        $distributionLocation = $supplier->distributionLocations()->findOrFail($locationId);
        $distributionLocation->delete();
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

            $user->category_name = $user->category->type;
            $user->city_name = $user->city->name;

            return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
