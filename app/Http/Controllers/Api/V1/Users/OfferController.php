<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\OfferRequest;
use App\Models\Offer;
use App\Models\Supplier;
use App\Services\MobileNotificationServices;
use App\Traits\Images;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    use Images;
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
         $this->authorize('viewAny',Offer::class);
        $offers = Offer::all();
        foreach ($offers as $offer) {
            $offer->image = asset("storage/$offer->image");
        }
        return response()->json($offers, 200);
    }

    /**
     * Show specific offer
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id)
    {
        $offer = Offer::findOrFail($id);
        $this->authorize('view',$offer);
        $offer->image = asset("storage/$offer->image");
        return response()->json($offer, 200);
    }
    /**
     * To create a new resource.
     * @param OfferRequest $request
     * @return JsonResponse
     */
    public function create(OfferRequest $request)
    {
        $this->authorize('create', Offer::class);
        try {
            $supplier = Supplier::findOrFail($request->supplire_id);
            $path = $request->file('image')->store('Offer', 'public');
            $Offer = Offer::create([
                'supplier_id' => $supplier->id,
                'image' => $path,
            ]);
            /** 
             *$notification = new MobileNotificationServices;
             *$title = "عرض جديد";
             *$body = 'المورد' . $supplier->store_name . 'قام بإضافة عرض جديد';
             *$notification->sendNotificationToTopic('market', $title, $body);
             */
            $Offer->image = asset("storage/$path");
            return response()->json($Offer, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param OfferRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(OfferRequest $request, string $id)
    {

        DB::beginTransaction();
        try {
            $offer = Offer::findOrFail($id);
            $this->authorize('update', $offer);

            if ($request->hasFile('image')) {
                $old_image = $offer->image;
                if (Storage::exists('public/' . $old_image)) {
                    Storage::delete('public/' . $old_image);
                } else {
                    throw new \Exception("Old image not found");
                }

                $path = $request->file('image')->store('Offer', 'public');
            } else {
                $path = $offer->image;
            }

            $offer->update([
                'supplier_id' => $request->supplier_id,
                'image' => $path,
            ]);

            DB::commit();

            $offer->image = asset("storage/$path");
            $offer->image = asset("storage/$path");
            return response()->json($offer, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return null
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $offer = Offer::findOrFail($id);
            $old_image = $offer->image;
            $this->authorize('delete', $offer);

            if (Storage::exists('public/' . $old_image)) {
                Storage::delete('public/' . $old_image);
            } else {
                throw new \Exception("Old image not found");
            }
            $offer->delete();
            DB::commit();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}
