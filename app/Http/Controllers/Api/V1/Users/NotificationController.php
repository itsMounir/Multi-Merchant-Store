<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
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
}
