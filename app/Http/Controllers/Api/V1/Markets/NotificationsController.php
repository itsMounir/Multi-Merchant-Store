<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use App\Models\DatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->indexOrShowResponse('notifications',Auth::user()->unreadNotifications);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return $this->sudResponse('تم قراءة الاشعار بنجاح');
    }
}
