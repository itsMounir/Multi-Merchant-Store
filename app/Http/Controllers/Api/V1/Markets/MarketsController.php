<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Markets\UpdateMarketRequest;
use App\Models\Market;
use App\Models\User;
use App\Notifications\RenewSubscription;
use App\Notifications\UpdateMarketDataNotification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class MarketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Market $market): JsonResponse
    {
        throw_if(Auth::user()->id != $market->id, new AuthorizationException);
        return $this->indexOrShowResponse('market', $market);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Market $market)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarketRequest $request, Market $market)
    {
        $market->update([
            'first_name' => $request->first_name ?? $market->first_name,
            'middle_name' => $request->middle_name ?? $market->middle_name,
            'last_name' => $request->last_name ?? $market->last_name,
        ]);
        return response()->json([
            'message' => '.تم تحديث الاسم بنجاح',
            'market' => $market,
        ]);
    }

    // /**
    //  * send a request to the admin for updating market credentials.
    //  */
    // public function sendUpdateRequest(UpdateMarketRequest $request, Market $market)
    // {
    //     $admins = User::role('admin')->get();
    //     Notification::send($admins, new UpdateMarketDataNotification($request->validated()));
    //     return response()->json([
    //         'message' => '.تم ارسال الطلب بنجاح',
    //     ]);
    // }

    public function sendRenewSubscriptionRequest()
    {
        $market = Auth::user();
        $supervisors = User::role('supervisor')->get();
        Notification::send($supervisors, new RenewSubscription($market));
        return response()->json([
            'message' => '.تم ارسال الطلب بنجاح'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Market $market)
    {
        //
    }


    public function getDeviceToken(Request $request)
    {
        $data = $request->validate(['device_token' => 'required']);
        $market = Auth::user();
        $market->update(['deviceToken' => $data['device_token']]);
        return $this->sudResponse('تم تحديث التوكن بنجاح');
    }
}
