<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\PrivateMarketNotification;
use App\Notifications\PrivateSupplierNotification;
use App\Traits\FirebaseNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    use FirebaseNotification;
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        return response()->json($notifications, 200);
    }

    /**
     * Mark notification as read
     * @param string $id
     * @return JsonResponse
     */
    public function markAsRead(string $id)
    {
        $user = User::find(Auth::user()->id);
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['message' => 'تم قراءة الاشعار بنجاح'], 200);
    }

    /**
     * Mark all notification as read
     * 
     * @return JsonResponse
     */
    public function markAllAsRead()
    {
        $user = User::find(Auth::user()->id);
        $user->unreadNotifications->markAsRead();
        return response()->json(['message' => 'تم قراءة جميع الاإشعارات بنجاح'], 200);
    }

    /**
     * Delete the notification from database
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        $user = User::find(Auth::user()->id);
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();
        return response()->json(['message' => 'تم حذف الاشعار بنجاح'], 200);
    }

    /**
     * Send Private Notifiacation [ database and firebase] to specific market user
     * 
     */
    public function sendNotificationToMarket(string $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'title' => ['required'],
                'body' => ['required'],
            ]);
            $market = Market::findOrfail($id);
            Notification::send($market, new PrivateMarketNotification($request->title, $request->body));
            $this->sendNotification($market->deviceToken, $request->title, $request->body);
            DB::commit();
            return response()->json(['message' => 'notofication sent successfully', 'notification' => ['title' => $request->title, 'body' => $request->body]], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
    }


    /**
     * Send Private Notifiacation [ database and firebase] to specific supplier user
     * 
     */
    public function sendNotificationToSupplier(string $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'title' => ['required'],
                'body' => ['required'],
            ]);
            $supplier = Supplier::findOrfail($id);
            Notification::send($supplier, new PrivateSupplierNotification($request->title, $request->body));
            $this->sendNotification($supplier->deviceToken, $request->title, $request->body);
            DB::commit();
            return response()->json(['message' => 'notofication sent successfully', 'notification' => ['title' => $request->title, 'body' => $request->body]], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
    }
}
